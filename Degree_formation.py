import mysql.connector
import os
import asyncio
from mistralai import Mistral
import json

# Set up Mistral client
api_key = os.environ["MISTRAL_API_KEY"]
model = "pixtral-12b-2409"
client = Mistral(api_key=api_key)

def create_connection():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="theproject2"
        )
        return conn
    except mysql.connector.Error as e:
        print(f"Error connecting to database: {e}")
        return None

def fetch_degrees_with_options(conn):
    excluded_degrees = [
        "Animal Sciences", "Agricultural Economics", "Food Science", "Naval Engineering",
        "Marketing", "Linguistics", "Zoology", "Journalism", "Mass Communication and Media Studies",
        "Statistics", "International Business", "Molecular Biology", "Natural Resource Management",
        "Actuarial Science", "Mathematics", "Soil Science", "Biomedical Engineering",
        "Forestry", "Visual and Performing Arts", "Fine Arts", "Neuroscience", "Petroleum Engineering",
        "Art History", "Civil Engineering", "Geological Engineering", "Industrial Engineering",
        "Physiology", "Humanities", "Pharmacology", "Pre-Medicine", "Chemical Engineering",
        "Microbiology", "Management Information Systems", "Human Resources Management",
        "Aerospace Engineering", "Horticultural Business Management", "Architectural Engineering",
        "Recreation and Leisure Studies", "Electronics Engineering Technology", "Nuclear Engineering",
        "Surveying Engineering", "Informatics", "Business", "Naturopathic Medicine", "Biology",
        "Electrical Engineering", "Mechanical Engineering", "Finance", "Cinema Studies",
        "Environmental Science", "Botany", "Ecology", "Genetics", "Cognitive Science", "Accounting",
        "Advertising", "Computer Science", "Applied Mathematics", "Elementary Education", "Education",
        "Architecture", "Engineering", "Computer Engineering", "Environmental Engineering",
        "Materials Science", "Nutrition Science", "Communicative Disorders",
        "Foreign Languages and Literatures", "Diversity Studies", "Theology", "History",
        "Court Reporting", "Criminal Justice", "Public Administration", "Public Policy",
        "Physical Science", "Astrophysics", "Meteorology", "Chemistry", "Oceanography", "Physics",
        "Psychology", "Clinical Psychology", "Counseling Psychology",
        "Industrial and Organizational Psychology", "Social Psychology", "Social Science",
        "Economics", "Criminology", "Geography", "International Relations", "Sociology",
        "Graphic Design", "Photography", "Women's Studies", "Carpentry", "Interior Design",
        "Plumbing", "Aviation Management", "Cosmetology", "Acting", "Screenwriting", "Fashion Design",
        "Landscape Architecture", "Religious Studies", "Urban Planning", "Veterinary Medicine",
        "African Studies", "Agricultural Engineering", "Agriculture", "American Studies",
        "Anthropology", "Applied Psychology", "Archaeology", "Astronomy", "Audiology",
        "Automotive Engineering Technology", "Baking and Pastry Arts", "Behavioral Science",
        "Biochemistry", "Bioethics", "Biomedical Sciences", "Biotechnology", "Brewing Science",
        "Cabinetmaking", "Ceramics", "Chiropractic", "Clinical Laboratory Science",
        "Construction Management", "Creative Writing", "Cybersecurity", "Data Science",
        "Database Management", "Dental Assisting", "Dentistry", "Divinity", "Geology",
        "Engineering Physics", "Engineering Science", "English", "Environmental Design",
        "Forensic Science", "French Language", "Gender Studies", "German Language",
        "Refrigeration, Air Conditioning, Heating and Gas Technology", "Illustration", "Immunology",
        "Interior Architecture", "Islamic Studies", "Law", "Kinesiology", "Latin American Studies",
        "Manufacturing Engineering", "Marine Biology", "Marriage and Family Therapy", "Massage Therapy",
        "Robotics Engineering", "Medicine", "Museum Studies", "Music Therapy", "Native American Studies",
        "Optometry", "Osteopathic Medicine", "Paleontology", "Pharmacy", "Philosophy",
        "Industrial Design", "Public Health", "Broadcast Technology", "Respiratory Therapy Assisting",
        "American Sign Language", "Spanish Language", "Computer Software Engineering", "Music",
        "Structural Engineering", "Theater Arts", "Urban Studies", "Visual Communication",
        "Medieval Studies", "Music Theory And Composition", "Agribusiness", "Agricultural Mechanics",
        "Aquaculture", "Crop Production", "Equine Science", "Turfgrass Management", "Dairy Science",
        "Poultry Science", "Plant Sciences", "Agronomy", "Horticulture", "Natural Resource Conservation",
        "Environmental Studies", "Environmental Planning", "Fisheries Sciences and Management",
        "Forest Sciences and Biology", "Pulp and Paper Engineering", "Wildlife Science and Management",
        "Asian Studies", "Russian Studies", "European Studies", "Canadian Studies", "French Studies",
        "German Studies", "Italian Studies", "Ethnic Studies", "African American Studies",
        "Chicano Studies", "Deaf Studies", "Speech Communication and Rhetoric", "Broadcast Journalism",
        "Radio and Television", "Public Relations", "Organizational Communication",
        "Political Communication", "Health Communications", "Sports Communications",
        "Desktop and Web Publishing", "Animation", "Artificial Intelligence", "Information Technology",
        "Web Design", "Computer Graphics", "Simulation Programming", "Network Systems Administration",
        "Network Management", "Information Technology Management", "Mortuary Science", "Makeup Artistry",
        "Esthetics and Skincare", "Culinary Arts", "Food Service Management", "Bilingual Education",
        "Curriculum and Instruction", "Educational Administration",
        "International and Comparative Education", "Special Education Teaching",
        "Early Childhood Education", "Computer Hardware Engineering", "Telecommunications Engineering",
        "Engineering Mechanics", "Materials Science and Engineering", "Ocean Engineering",
        "Plastics Engineering", "Construction Engineering", "Operations Research",
        "Paper Science and Engineering"
    ]
    placeholders = ', '.join(['%s'] * len(excluded_degrees))

    cursor = conn.cursor(dictionary=True)
    cursor.execute(f"""
    SELECT d.id as degree_id, d.name as degree_name,
           dpo.id as option_id, dpo.description,
           dpo.associate_title, dpo.bachelors_title, dpo.masters_title, dpo.doctoral_title,
           dpo.associate_description, dpo.bachelors_description, dpo.masters_description, dpo.doctoral_description

    FROM degrees d
    JOIN degree_program_options_3 dpo ON d.id = dpo.degree_id
    WHERE d.name NOT IN ({placeholders})
    """, excluded_degrees)
    return cursor.fetchall()

def fetch_all_formations(conn):
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, nom, diplomeLibelleFr FROM formation")
    return cursor.fetchall()

def create_degree_formation_analysis_table(conn):
    cursor = conn.cursor()
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS degree_formation_matches_test (
        id INT AUTO_INCREMENT PRIMARY KEY,
        degree_id INT,
        degree_name VARCHAR(255),
        formation_id INT,
        formation_name VARCHAR(255),
        similarity_score FLOAT,
        analysis_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    """)
    conn.commit()

def create_raw_responses_table(conn):
    cursor = conn.cursor()
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS raw_responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        response TEXT,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    """)
    conn.commit()

def generate_analysis_prompt(degree, formations):
    degree_info = f"Degree: {degree['degree_name']}\n"
    for level in ['associate', 'bachelors', 'masters', 'doctoral']:
        if degree[f'{level}_title']:
            degree_info += f"{level.capitalize()} Program: {degree[f'{level}_title']}\n"
            if degree[f'{level}_description']:
                degree_info += f"Description: {degree[f'{level}_description']}\n"

    formation_names = ", ".join([f"{f['id']}: {f['nom']}" for f in formations])

    prompt = f"""Analyze the degree program and compare to formations.
Score similarity 0-1 (1=perfect match).
Consider field of study and education level and your AI thinking to determine an appropriate similarity score between 0-1.

Degree Program:
{degree_info}

Formations: {formation_names}

Return the result as a JSON object with this structure:
{{
  "degree_id": {degree['degree_id']},
  "degree_name": "{degree['degree_name']}",
  "matches": [
    {{
      "formation_name": "Name of formation",
      "formation_id": "ID of formation",
      "similarity_score": 0.85
    }},
    ...
  ]
}}
Include only formations with a similarity score above 0.6 and return the result as a JSON object without any more text.

"""

    return prompt

async def analyze_degree_and_formations(conn, degree, formations):
    prompt = generate_analysis_prompt(degree, formations)

    try:
        chat_response = client.chat.complete(
            model=model,
            messages=[
                {"role": "system", "content": "You are an AI assistant that analyzes and compares educational programs and return the result as a JSON object with the correct structure."},
                {"role": "user", "content": prompt}
            ]
        )

        response = chat_response.choices[0].message.content.strip()
        response = response.replace("'", "'").replace("'", "'")

        try:
            analysis_result = json.loads(response)
            await insert_degree_formation_data(conn, analysis_result)
        except json.JSONDecodeError as e:
            print(f"Error decoding JSON: {e}")
            print(f"response: {response}")

        # Store the raw response in the database
        await insert_raw_response(conn, response)
    except Exception as e:
        print(f"An error occurred during API call: {e}")
        # Implement exponential backoff or other error handling strategy here

async def insert_degree_formation_data(conn, analysis_result):
    cursor = conn.cursor()
    for match in analysis_result['matches']:
        sql = """
        INSERT INTO degree_formation_matches_test
        (degree_id, degree_name, formation_id, formation_name, similarity_score)
        VALUES (%s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
        similarity_score = VALUES(similarity_score),
        analysis_timestamp = CURRENT_TIMESTAMP
        """
        values = (analysis_result['degree_id'], analysis_result['degree_name'],
                  match['formation_id'], match['formation_name'], match['similarity_score'])
        try:
            cursor.execute(sql, values)
        except mysql.connector.Error as e:
            print(f"Error inserting/updating data: {e}")
    conn.commit()

async def insert_raw_response(conn, response):
    cursor = conn.cursor()
    sql = "INSERT INTO raw_responses (response) VALUES (%s)"
    try:
        cursor.execute(sql, (response,))
        conn.commit()
    except mysql.connector.Error as e:
        print(f"Error inserting raw response: {e}")

async def link_degrees_to_formations(conn, degrees, formations):
    tasks = [analyze_degree_and_formations(conn, degree, formations) for degree in degrees]
    await asyncio.gather(*tasks)

async def main():
    conn = create_connection()
    if not conn:
        return

    try:
        create_degree_formation_analysis_table(conn)
        create_raw_responses_table(conn)
        formations = fetch_all_formations(conn)
        degrees = fetch_degrees_with_options(conn)
        await link_degrees_to_formations(conn, degrees, formations)

    except mysql.connector.Error as e:
        print(f"Database error: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    finally:
        if conn.is_connected():
            conn.close()
            print("MySQL connection closed")

if __name__ == "__main__":
    asyncio.run(main())
