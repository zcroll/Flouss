import mysql.connector
from googletrans import Translator
import time

# Connect to the MySQL database
try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="theproject2"
    )
    cursor = conn.cursor()
except Exception as e:
    print(f"Failed to connect to the database: {e}")
    exit()

# Initialize the translator
translator = Translator()


def add_column_if_not_exists(table_name, column_name, column_definition, after_column=None):
    try:
        cursor.execute(f"""
            SELECT COUNT(*)
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{table_name}'
            AND COLUMN_NAME = '{column_name}'
        """)
        if cursor.fetchone()[0] == 0:
            # Modify the query to place the new column after a specific column if provided
            alter_table_query = f"ALTER TABLE {table_name} ADD COLUMN {column_name} {column_definition}"
            if after_column:
                alter_table_query += f" AFTER {after_column}"

            cursor.execute(alter_table_query)
            print(f"Added column {column_name} to table {table_name}")
        else:
            print(f"Column {column_name} already exists in table {table_name}")
    except Exception as e:
        print(f"Failed to add column {column_name} to table {table_name}: {e}")


# Function to translate and update the database
def translate_column(table_name, column_name, translated_column_name, start_id):
    try:
        # Fetch all rows from the table starting from the specified ID
        cursor.execute(f"SELECT id, {column_name} FROM {table_name} WHERE id >= %s", (start_id,))
        rows = cursor.fetchall()

        for row in rows:
            record_id, english_text = row
            if english_text:  # Check if there's text to translate
                # Check if the text is already in the target language (French)
                if translator.detect(english_text).lang != 'fr':
                    # Translate the text to French
                    if english_text == 'English':
                        translated_text = 'anglais'
                    else:
                        try:
                            translated_text = translator.translate(english_text, src='en', dest='fr').text
                        except Exception as e:
                            print(f"Translation failed for ID {record_id}: {e}")
                            continue

                    # Update the translated column in the database
                    cursor.execute(f"""
                        UPDATE {table_name}
                        SET {translated_column_name} = %s
                        WHERE id = %s
                    """, (translated_text, record_id))

                    # Commit the changes
                    conn.commit()

                    print(f"Translated and updated row {record_id}: {english_text} -> {translated_text}")
                else:
                    print(f"Row {record_id} is already in French: {english_text}")
            time.sleep(1)  # Sleep for a second to avoid hitting the Google Translate rate limit
    except Exception as e:
        print(f"Failed to translate and update the database: {e}")


# Add the new column if it doesn't exist, and place it after a specific column
add_column_if_not_exists('job_degrees', 'degree_title_fr', 'TEXT', after_column='degree_title')

# Usage example: specify the starting ID
try:
    translate_column('job_degrees', 'degree_title', 'degree_title_fr', start_id=639)
except Exception as e:
    print(f"Failed to translate and update the database: {e}")

# Close the connection
try:
    cursor.close()
    conn.close()
except Exception as e:
    print(f"Failed to close the connection: {e}")
