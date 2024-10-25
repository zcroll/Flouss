import mariadb
from typing import Dict, List, Tuple
from dataclasses import dataclass
import itertools
import sys
import json
import os

@dataclass
class JobMatch:
    job_id: int
    job_title: str
    primary_fields: Tuple[str, str]
    description: str
    education_level: str

    def to_dict(self):
        return {
            'job_id': self.job_id,
            'job_title': self.job_title,
            'primary_fields': self.primary_fields,
            'description': self.description,
            'education_level': self.education_level
        }

class JobMatcher:
    def __init__(self, db_config: Dict, interest_scores: Dict[str, int]):
        """Initialize database connection and prepare interest scores"""
        self.conn = mariadb.connect(**db_config)
        self.cursor = self.conn.cursor(dictionary=True)
        self.interest_scores = interest_scores

    def get_jobs_by_fields(self, fields: Tuple[str, str], limit: int = 30) -> List[Dict]:
        """Fetch jobs that combine both fields in the tuple."""
        field1, field2 = fields
        query = """
        SELECT DISTINCT ji.id as job_id, ji.name as job_title, ji.education_level
        FROM job_requirement jr1
        JOIN job_requirement jr2 ON jr1.job_id = jr2.job_id
        JOIN job_infos ji ON ji.id = jr1.job_id
        WHERE jr1.scale_name = ? AND jr2.scale_name = ?
        LIMIT ?
        """
        self.cursor.execute(query, (field1, field2, limit))
        return self.cursor.fetchall()

    def find_matching_jobs(self) -> Dict[str, any]:
        """Find and rank matching jobs based on optimized combination rules"""
        final_matches = []
        combination_productivity = {}
        remaining_slots = 30
        used_job_ids = set()
        
        # Step 1: Analyze highest scoring fields (score 5)
        top_fields = [field for field, score in self.interest_scores.items() if score == 5]
        top_pairs = list(itertools.combinations(top_fields, 2))
        
        # Find the most productive pair from top fields
        most_productive_pair = None
        max_jobs = 0
        top_pair_jobs = []
        
        for pair in top_pairs:
            jobs = self.get_jobs_by_fields(pair)
            num_jobs = len(jobs)
            if num_jobs > max_jobs:
                max_jobs = num_jobs
                most_productive_pair = pair
                top_pair_jobs = jobs
        
        # Add jobs from most productive top pair
        for job in top_pair_jobs:
            if remaining_slots > 0 and job['job_id'] not in used_job_ids:
                description = f"This role combines expertise in {most_productive_pair[0]} and {most_productive_pair[1]}"
                final_matches.append(JobMatch(
                    job_id=job['job_id'],
                    job_title=job['job_title'],
                    primary_fields=most_productive_pair,
                    description=description,
                    education_level=job['education_level']
                ))
                used_job_ids.add(job['job_id'])
                remaining_slots -= 1
        
        # Step 2: Combine with next level (score 4)
        if most_productive_pair:
            score_4_fields = [field for field, score in self.interest_scores.items() if score == 4]
            best_secondary_pair = None
            max_secondary_jobs = 0
            best_secondary_jobs = []
            
            # Try combinations with each field from the most productive pair
            for top_field in most_productive_pair:
                for score_4_field in score_4_fields:
                    pair = (top_field, score_4_field)
                    jobs = self.get_jobs_by_fields(pair)
                    num_jobs = len(jobs)
                    if num_jobs > max_secondary_jobs:
                        max_secondary_jobs = num_jobs
                        best_secondary_pair = pair
                        best_secondary_jobs = jobs
            
            # Add jobs from best secondary combination
            for job in best_secondary_jobs:
                if remaining_slots > 0 and job['job_id'] not in used_job_ids:
                    description = f"This role combines expertise in {best_secondary_pair[0]} and {best_secondary_pair[1]}"
                    final_matches.append(JobMatch(
                        job_id=job['job_id'],
                        job_title=job['job_title'],
                        primary_fields=best_secondary_pair,
                        description=description,
                        education_level=job['education_level']
                    ))
                    used_job_ids.add(job['job_id'])
                    remaining_slots -= 1
        
        return {
            "most_productive_combinations": [
                (most_productive_pair, max_jobs),
                (best_secondary_pair, max_secondary_jobs)
            ] if most_productive_pair and best_secondary_pair else [],
            "job_matches": final_matches,
            "combination_analysis": {
                most_productive_pair: max_jobs,
                best_secondary_pair: max_secondary_jobs
            } if most_productive_pair and best_secondary_pair else {}
        }

    def close(self):
        """Close database connection"""
        self.cursor.close()
        self.conn.close()

def main():
    try:
        # Get interest scores from command line argument
        interest_scores = json.loads(sys.argv[1])
        
        # Validate interest scores
        if not all(isinstance(score, int) and 1 <= score <= 5 
                  for score in interest_scores.values()):
            raise ValueError("Invalid interest scores. Scores must be integers between 1 and 5")
        # Get database config from Laravel .env
        db_config = {
            'host': os.getenv('DB_HOST', 'localhost'),
            'user': os.getenv('DB_USERNAME', 'root'), 
            'password': os.getenv('DB_PASSWORD', ''),
            'database': os.getenv('DB_DATABASE', 'laravel')
        }

        matcher = JobMatcher(db_config, interest_scores)
        results = matcher.find_matching_jobs()
        
        # Convert results to JSON-serializable format
        response = {
            'job_matches': [match.to_dict() for match in results['job_matches']],
            'most_productive_combinations': results['most_productive_combinations'],
            'combination_analysis': {
                str(k): v for k, v in results['combination_analysis'].items()
            } if results['combination_analysis'] else {},
            'total_matches': len(results['job_matches'])
        }
        
        print(json.dumps(response))
        
    except Exception as e:
        print(json.dumps({
            'error': str(e)
        }))
        sys.exit(1)
    finally:
        if 'matcher' in locals():
            matcher.close()

if __name__ == "__main__":
    main()
