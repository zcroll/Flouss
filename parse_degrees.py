import json
import re

def clean_json_content(content):
    """Clean JSON content by removing comments and fixing formatting"""
    # Remove single-line comments
    content = re.sub(r'//.*$', '', content, flags=re.MULTILINE)
    
    # Remove multi-line comments
    content = re.sub(r'/\*.*?\*/', '', content, flags=re.DOTALL)
    
    # Remove trailing commas in objects and arrays
    content = re.sub(r',(\s*[}\]])', r'\1', content)
    
    # Fix any double commas that might have been created
    content = re.sub(r',,+', ',', content)
    
    # Remove empty lines
    content = '\n'.join(line for line in content.splitlines() if line.strip())
    
    return content

def create_sql_inserts():
    try:
        # Read and clean the JSON file
        with open('Cleaned_degrees_json/cleaned_degrees_lettre.json', 'r', encoding='utf-8') as f:
            content = f.read()
            
        # Clean the JSON content
        cleaned_content = clean_json_content(content)
        
        # Parse the cleaned JSON
        data = json.loads(cleaned_content)
        
        # Create SQL file
        with open('degree_courses_Droit.sql', 'w', encoding='utf-8') as f:
            # Write table creation
            f.write("""-- Create the table if it doesn't exist
CREATE TABLE IF NOT EXISTS degree_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    degree_id INT,
    degree_name VARCHAR(255),
    similarity_score FLOAT,
    INDEX (course_id)
);\n\n""")

            # Start INSERT statement
            f.write("-- Insert values\n")
            f.write("INSERT INTO degree_courses\n")
            f.write("(course_id, degree_id, degree_name, similarity_score)\n")
            f.write("VALUES\n")

            # Track if we need a comma
            first_row = True

            # Iterate through each degree
            for degree_id, degree_info in data.items():
                if not isinstance(degree_info, dict) or 'name' not in degree_info:
                    continue
                    
                degree_name = degree_info['name']
                courses = degree_info.get('courses', [])

                # Get category and area based on degree ID ranges

                # Add each course for this degree
                for course_id in courses:
                    if not first_row:
                        f.write(",\n")
                    first_row = False

                    # Clean up the course_id (remove any comments)
                    if isinstance(course_id, str):
                        course_id = course_id.split('/*')[0].strip()

                    # Escape single quotes in degree_name
                    safe_degree_name = degree_name.replace("'", "''")

                    # Write the INSERT value
                    f.write(f"({course_id}, {degree_id}, '{safe_degree_name}', 1.0)")

            f.write(";\n")
            
    except json.JSONDecodeError as e:
        print(f"JSON parsing error: {str(e)}")
        print("Please check the JSON file format")
        raise
    except Exception as e:
        print(f"Error processing file: {str(e)}")
        raise

    

if __name__ == "__main__":
    create_sql_inserts() 