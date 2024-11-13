import os
import sys
import mysql.connector
from mistralai import Mistral
import time
import random

try:
    # Initialize Mistral client
    api_key = os.environ["MISTRAL_API_KEY"]
    model = "pixtral-12b-2409"
    client = Mistral(api_key=api_key)

    # Connect to MySQL Platform
    conn = mysql.connector.connect(
        user="root",
        password="",
        host="localhost",
        port=3306,
        database="theproject2"
    )

    # Get Cursor
    cur = conn.cursor()

    # Create table to store the data
    cur.execute("""
        CREATE TABLE IF NOT EXISTS course_degree_similarity (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT,
            category VARCHAR(255),
            area_name VARCHAR(255),
            degree_id INT,
            degree_name VARCHAR(255),
            similarity_score FLOAT,
            FOREIGN KEY (course_id) REFERENCES course_category_scores(course_id)
        )
    """)

    # Get the starting course ID from command line arguments
    if len(sys.argv) > 1:
        start_course_id = int(sys.argv[1])
    else:
        start_course_id = 2556

    # Execute query to get all courses starting from the specified course ID
    cur.execute("SELECT course_id, category FROM course_category_scores WHERE course_id >= %s", (start_course_id,))

    # Fetch all courses
    all_courses = cur.fetchall()

    # Process each course
    for course_id, category in all_courses:
        try:
            # Execute query to get matching degrees for the course
            cur.execute("""
                SELECT aos.area_name, d.id AS degree_id, d.name AS degree_name
                FROM area_of_study aos
                JOIN degrees d ON aos.degree_id = d.id
                WHERE aos.area_name = %s
            """, (category,))

            # Fetch matching degrees
            matching_degrees = cur.fetchall()

            # Process each matching degree
            for area_name, degree_id, degree_name in matching_degrees:
                try:
                    # Get model predictions using Mistral API
                    chat_response = client.chat.complete(
                        model=model,
                        messages=[
                            {
                                "role": "system",
                                "content": f"Evaluate how well the course with ID '{course_id}' in category '{category}' matches the degree '{degree_name}' (ID: {degree_id}) in area of study '{area_name}'. Provide a similarity score between 0 and 1, where 1 indicates a perfect match. Format your response as a single number with no other text."
                            },
                            {
                                "role": "user",
                                "content": f"Course ID: {course_id}, Category: {category}, Area of Study: {area_name}, Degree ID: {degree_id}, Degree Name: {degree_name}"
                            }
                        ]
                    )

                    # Parse response
                    similarity_score = float(chat_response.choices[0].message.content.strip())

                    # Insert data into the table
                    cur.execute("""
                        INSERT INTO course_degree_similarity (course_id, category, area_name, degree_id, degree_name, similarity_score)
                        VALUES (%s, %s, %s, %s, %s, %s)
                    """, (course_id, category, area_name, degree_id, degree_name, similarity_score))

                    print(f"Course ID: {course_id}")
                    print(f"Category: {category}")
                    print(f"Area of Study: {area_name}")
                    print(f"Degree ID: {degree_id}")
                    print(f"Degree Name: {degree_name}")
                    print(f"Similarity Score: {similarity_score:.2f}")
                    print("-------------------")

                    # Random delay between 1-3 seconds to avoid rate limits
                    time.sleep(random.uniform(1, 3))

                except Exception as e:
                    if "429" in str(e):
                        # If rate limited, wait longer (5-10 seconds) and retry
                        time.sleep(random.uniform(5, 10))
                        continue
                    print(f"Error processing course {course_id} with degree {degree_id}: {e}")
                    continue

            # Commit after each course
            conn.commit()

        except Exception as e:
            print(f"Error processing course {course_id}: {e}")
            continue

except mysql.connector.Error as e:
    print(f"Error connecting to MySQL Platform: {e}")
    sys.exit(1)
except Exception as e:
    print(f"Error processing data: {e}")
    sys.exit(1)

finally:
    if 'conn' in locals():
        conn.close()
        print("Database connection closed.")
