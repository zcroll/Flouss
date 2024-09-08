import sys
import json
import mysql.connector
import numpy as np
from sklearn.preprocessing import MinMaxScaler

# Get the JSON encoded target traits from command-line arguments
target_holland = json.loads(sys.argv[1])

# Connect to your MySQL database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="Zcroll@2001",
    database="bigdata"
)
cursor = conn.cursor()

# Retrieve all jobs and their Holland traits
cursor.execute("SELECT job_info_id, trait_name, trait_score FROM personality_traits WHERE trait_type='holland_codes'")
holland_rows = cursor.fetchall()

# Organize Holland data by job_id
holland_jobs = {}
for job_id, trait_name, trait_score in holland_rows:
    if job_id not in holland_jobs:
        holland_jobs[job_id] = {}
    holland_jobs[job_id][trait_name] = float(trait_score)

# Function to calculate weighted Euclidean distance across traits
def weighted_euclidean_distance(target, candidate, weights):
    distance = 0
    for trait, weight in weights.items():
        distance += weight * (target.get(trait, 0) - candidate.get(trait, 0)) ** 2
    return np.sqrt(distance)

# Get the top two Holland traits
sorted_holland = sorted(target_holland.items(), key=lambda x: x[1], reverse=True)
top_two_traits = dict(sorted_holland[:2])

# Normalize the weights of the top two traits to 100%
total_score = sum(top_two_traits.values())
weights = {trait: score / total_score for trait, score in top_two_traits.items()}

# Calculate Holland distances
holland_distances = []

for job_id, job_traits in holland_jobs.items():
    holland_distance = weighted_euclidean_distance(target_holland, job_traits, weights)
    holland_distances.append((job_id, holland_distance))

# Sort and get the top 10 closest jobs based on Holland traits
holland_distances.sort(key=lambda x: x[1])
closest_jobs = holland_distances[:10]

# Prepare the result array
result = [{"job_info_id": int(job_id), "distance": float(distance)} for job_id, distance in closest_jobs]

# Output the result as JSON
print(json.dumps(result))

# Close the database connection
conn.close()
