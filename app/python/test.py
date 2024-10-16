import mariadb
import pandas as pd
import sys
import json

def get_database_connection():
    """
    Establishes and returns a connection to the MariaDB database.
    """
    try:
        return mariadb.connect(
            host='localhost',
            user='root',
            password='new_password',
            database='bigdata',
        )
    except mariadb.Error as e:
        print(json.dumps({"error": f"Error connecting to MariaDB: {e}"}))
        sys.exit(1)

def fetch_job_requirements(connection, basic_interest_ids):
    """
    Fetches job requirements that match the given basic_interest_ids.
    
    Args:
        connection (mariadb.connection): Database connection.
        basic_interest_ids (list): List of basic_interest_id integers.
        
    Returns:
        pd.DataFrame: DataFrame containing job_info_id, category, and basic_interest_id.
    """
    cursor = connection.cursor(dictionary=True)
    format_strings = ','.join(['?'] * len(basic_interest_ids))
    sql = f"""
        SELECT job_info_id, category, basic_interest_id
        FROM job_requirement
        WHERE basic_interest_id IN ({format_strings})
    """
    cursor.execute(sql, basic_interest_ids)
    result = cursor.fetchall()
    cursor.close()
    return pd.DataFrame(result)

def fetch_job_info(connection, job_info_ids):
    """
    Fetches job information (e.g., name) for the given job_info_ids.
    
    Args:
        connection (mariadb.connection): Database connection.
        job_info_ids (list): List of job_info_id integers.
        
    Returns:
        pd.DataFrame: DataFrame containing id and name.
    """
    cursor = connection.cursor(dictionary=True)
    format_strings = ','.join(['?'] * len(job_info_ids))
    sql = f"""
        SELECT id, name
        FROM job_infos
        WHERE id IN ({format_strings})
    """
    cursor.execute(sql, job_info_ids)
    result = cursor.fetchall()
    cursor.close()
    return pd.DataFrame(result)

def fetch_trait_info(connection, job_info_ids):
    """
    Fetches trait information for the given job_info_ids.
    
    Args:
        connection (mariadb.connection): Database connection.
        job_info_ids (list): List of job_info_id integers.
        
    Returns:
        pd.DataFrame: DataFrame containing job_id, archetype, trait_name, trait_score, and trait_type.
    """
    cursor = connection.cursor(dictionary=True)
    format_strings = ','.join(['?'] * len(job_info_ids))
    sql = f"""
        SELECT job_id, archetype, trait_name, trait_score, trait_type
        FROM archetype_personality_traits
        WHERE job_id IN ({format_strings})
    """
    cursor.execute(sql, job_info_ids)
    result = cursor.fetchall()
    cursor.close()
    return pd.DataFrame(result)

def main():
    try:
        # Get input data from command line arguments
        basic_interest_ids = json.loads(sys.argv[1])
        holland_scores = json.loads(sys.argv[2])

        # Connect to the database
        connection = get_database_connection()

        # Fetch job requirements that match the basic_interest_ids
        job_requirements_df = fetch_job_requirements(connection, basic_interest_ids)

        if job_requirements_df.empty:
            print(json.dumps({"error": "No jobs match the criteria with the given basic interests."}))
            return

        # Get unique job_info_ids
        job_info_ids = job_requirements_df['job_info_id'].unique().tolist()

        # Fetch job information for the matched jobs
        job_info_df = fetch_job_info(connection, job_info_ids)

        # Fetch trait information for the matched jobs
        trait_info_df = fetch_trait_info(connection, job_info_ids)
        
        # Assign the Holland scores to the DataFrame
        trait_info_df['holland_score'] = trait_info_df['trait_name'].map(holland_scores)
        
        # Calculate the composite score for each job
        highest_trait = max(holland_scores, key=holland_scores.get)
        trait_info_df['composite_score'] = trait_info_df.apply(
            lambda row: 0.7 * row['holland_score'] if row['trait_name'] == highest_trait else 0.3 * row['holland_score'],
            axis=1
        )
        
        # Group by job_id and calculate the total composite score
        job_scores = trait_info_df.groupby('job_id')['composite_score'].sum().reset_index()
        
        # Sort jobs by composite score in descending order and get the top 22 jobs
        top_jobs = job_scores.nlargest(22, 'composite_score')
        
        # Merge job information with top jobs
        top_job_info = pd.merge(top_jobs, job_info_df, left_on='job_id', right_on='id')
        
        # Prepare the output
        output = []
        for _, row in top_job_info.iterrows():
            output.append({
                "id": int(row['id']),
                "name": row['name'],
                "match_percentage": round(row['composite_score'] * 100, 2)
            })
        
        print(json.dumps(output))

    except Exception as e:
        print(json.dumps({"error": str(e)}))
    finally:
        if 'connection' in locals():
            connection.close()

if __name__ == "__main__":
    main()