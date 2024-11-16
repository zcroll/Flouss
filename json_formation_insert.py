import json
import mysql.connector

# Establish a connection to the MySQL database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="theproject2"
)

# Create a cursor object to execute SQL queries
cursor = conn.cursor()

# SQL query to insert data into the degree_formation_matches table
insert_query = """
    INSERT INTO degree_formation_matches (degree_id, degree_name, formation_id, formation_name, similarity_score)
    VALUES (%s, %s, %s, %s, %s)
"""

# Load the JSON data from the degreeforamtion.json file
with open('degreeforamtion.json', 'r') as file:
    json_data = file.read()

try:
    # Parse the entire file as a single JSON object
    parsed_data = json.loads(json_data)

    # Iterate over each response in the parsed data
    for response in parsed_data:
        # Extract the relevant data from the response
        response_text = response['response'].strip()
        if response_text.startswith('```json'):
            response_text = response_text[7:]  # Remove '```json' prefix
        if response_text.endswith('```'):
            response_text = response_text[:-3]  # Remove '```' suffix
        response_text = response_text.strip()

        try:
            response_data = json.loads(response_text)
        except json.JSONDecodeError as json_error:
            print(f"Error decoding JSON for response: {response_text[:100]}...")  # Print first 100 chars
            print(f"JSON decode error: {json_error}")
            continue

        degree_id = response_data.get('degree_id')
        degree_name = response_data.get('degree_name')

        # Extract the formation matches from the response
        formation_matches = response_data.get('matches', [])

        # Iterate over each formation match and insert the data into the database
        for formation_match in formation_matches:
            formation_id = formation_match.get('formation_id')
            formation_name = formation_match.get('formation_name')
            similarity_score = formation_match.get('similarity_score')

            # Check if all required fields are present and have the correct type
            if all([degree_id, degree_name, formation_id, formation_name, similarity_score]):
                try:
                    # Convert formation_id to int if it's a string
                    if isinstance(formation_id, str):
                        formation_id = int(formation_id)

                    # Execute the SQL query to insert or update the data
                    cursor.execute(insert_query, (degree_id, degree_name, formation_id, formation_name, similarity_score))
                except (mysql.connector.Error, ValueError) as error:
                    print(f"Error inserting data: {error}")
                    print(f"Problematic data: {formation_match}")
            else:
                print(f"Skipping incomplete or invalid data: {formation_match}")

    # Commit the changes to the database
    conn.commit()
    print("Data inserted successfully!")

except json.JSONDecodeError as json_error:
    print(f"Error decoding JSON file: {json_error}")
except mysql.connector.Error as mysql_error:
    print(f"Error interacting with MySQL: {mysql_error}")

finally:
    # Close the cursor and database connection
    if cursor:
        cursor.close()
    if conn:
        conn.close()
