import mysql.connector
import numpy as np
from sklearn.preprocessing import MinMaxScaler

# Connect to your MySQL database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="Zcroll@2001",
    database="bigdata"
)
cursor = conn.cursor()

# Define the target job's Holland codes and Big Five traits
target_holland = {
    'Realistic': 0.20,
    'Investigative': 0.43,
    'Artistic': 0.57,
    'Social': 0.23,
    'Enterprising': 0.47,
    'Conventional': 0.33,
}

target_big_five = {
    'Openness': 0.22,
    'Conscientiousness': 0.83,
    'Extraversion': 0.35,
    'Agreeableness': 0.41,
    'Social Responsibility': 0.64,
}

# Retrieve all jobs and their Holland traits
cursor.execute("SELECT job_info_id, trait_name, trait_score FROM personality_traits WHERE trait_type='holland_codes'")
holland_rows = cursor.fetchall()

# Retrieve all jobs and their Big Five traits
cursor.execute("SELECT job_info_id, trait_name, trait_score FROM personality_traits WHERE trait_type='big_five'")
big_five_rows = cursor.fetchall()

# Organize Holland data by job_id
holland_jobs = {}
for job_id, trait_name, trait_score in holland_rows:
    if job_id not in holland_jobs:
        holland_jobs[job_id] = {}
    holland_jobs[job_id][trait_name] = float(trait_score)

# Organize Big Five data by job_id
big_five_jobs = {}
for job_id, trait_name, trait_score in big_five_rows:
    if job_id not in big_five_jobs:
        big_five_jobs[job_id] = {}
    big_five_jobs[job_id][trait_name] = float(trait_score)

# Function to calculate Euclidean distance across traits
def euclidean_distance(target, candidate):
    distance = 0
    for trait in target:
        distance += (target[trait] - candidate.get(trait, 0)) ** 2
    return np.sqrt(distance)

# Calculate Holland and Big Five distances separately
holland_distances = []
big_five_distances = []

for job_id in holland_jobs.keys():
    holland_distance = euclidean_distance(target_holland, holland_jobs.get(job_id, {}))
    holland_distances.append((job_id, holland_distance))

for job_id in big_five_jobs.keys():
    big_five_distance = euclidean_distance(target_big_five, big_five_jobs.get(job_id, {}))
    big_five_distances.append((job_id, big_five_distance))

# Normalize the distances using MinMaxScaler to ensure they are on the same scale
holland_distances = np.array([d[1] for d in holland_distances]).reshape(-1, 1)
big_five_distances = np.array([d[1] for d in big_five_distances]).reshape(-1, 1)

scaler = MinMaxScaler()
normalized_holland_distances = scaler.fit_transform(holland_distances)
normalized_big_five_distances = scaler.fit_transform(big_five_distances)

# Combine the normalized distances with the respective weights
combined_distances = []

for i, job_id in enumerate(holland_jobs.keys()):
    holland_distance = normalized_holland_distances[i][0]
    big_five_distance = normalized_big_five_distances[i][0] if i < len(normalized_big_five_distances) else 0

    combined_distance = 0.6 * holland_distance + 0.4 * big_five_distance
    combined_distances.append((job_id, combined_distance))

# Sort and get the top 10 closest jobs based on the combined score
combined_distances.sort(key=lambda x: x[1])
closest_jobs = combined_distances[:10]

# Output the closest jobs
print("Top 10 closest jobs based on 60% Holland and 40% Big Five (normalized):")
for job_id, distance in closest_jobs:
    print(f"Job ID: {job_id}, Combined Distance: {distance}")

# Close the database connection
conn.close()
