import mysql.connector
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
        # Add charset and collation settings to db_config
        db_config['charset'] = 'utf8mb4'
        db_config['collation'] = 'utf8mb4_unicode_ci'
        
        self.conn = mysql.connector.connect(**db_config)
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
        WHERE jr1.scale_name = %s AND jr2.scale_name = %s
        LIMIT %s
        """
        self.cursor.execute(query, (field1, field2, limit))
        return self.cursor.fetchall()

    def find_matching_jobs(self) -> Dict[str, any]:
        """Find and rank matching jobs based on optimized combination rules"""
        final_matches = []
        data_cache = {}
        duplicate_tracker = set()
        
        # Get interests by score
        interests_by_score = {}
        for interest, score in self.interest_scores.items():
            if score not in interests_by_score:
                interests_by_score[score] = []
            interests_by_score[score].append(interest)
        
        scores = sorted(interests_by_score.keys(), reverse=True)
        high_score_interests = interests_by_score[scores[0]]
        second_highest_interests = interests_by_score[scores[1]] if len(scores) > 1 else []
        third_highest_interests = interests_by_score[scores[2]] if len(scores) > 2 else []
        fourth_highest_interests = interests_by_score[scores[3]] if len(scores) > 3 else []
        
        # Phase 1: Primary Data Selection with Duplicates and Education Level Exclusion
        if len(high_score_interests) > 3:
            pairs = list(itertools.combinations(high_score_interests, 2))
            for pair in pairs:
                if pair not in data_cache:
                    jobs = self.get_jobs_by_fields(pair)
                    filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                    data_cache[pair] = filtered_jobs
                
                final_matches.extend(data_cache[pair])
                duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
        
        elif len(high_score_interests) == 2:
            pair = tuple(high_score_interests)
            if pair not in data_cache:
                jobs = self.get_jobs_by_fields(pair)
                filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                data_cache[pair] = filtered_jobs
            
            final_matches.extend(data_cache[pair])
            duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
        
        elif len(high_score_interests) == 1:
            pairs = [(high_score_interests[0], interest) for interest in second_highest_interests[:3]]
            for pair in pairs:
                if pair not in data_cache:
                    jobs = self.get_jobs_by_fields(pair)
                    filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                    data_cache[pair] = filtered_jobs
            
            sorted_pairs = sorted(pairs, key=lambda p: len(data_cache[p]), reverse=True)
            final_matches.extend(data_cache[sorted_pairs[0]])
            final_matches.extend(data_cache[sorted_pairs[1]])
            final_matches.extend(data_cache[sorted_pairs[2]])
            duplicate_tracker.update(job['job_id'] for pair in sorted_pairs for job in data_cache[pair])
        
        if len(final_matches) >= 30:
            final_matches = final_matches[:30]
        else:
            for pair in data_cache:
                final_matches.extend(job for job in data_cache[pair] if job['job_id'] not in duplicate_tracker)
                duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                if len(final_matches) >= 30:
                    final_matches = final_matches[:30]
                    break
        
        # Phase 2: Supplementary Data Selection with Fallback
        if len(final_matches) < 30:
            pairs = [(interest1, interest2) 
                     for interest1 in high_score_interests 
                     for interest2 in second_highest_interests]
            
            for pair in pairs:
                if pair not in data_cache:
                    jobs = self.get_jobs_by_fields(pair)
                    filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                    data_cache[pair] = filtered_jobs
            
            sorted_pairs = sorted(pairs, key=lambda p: len(data_cache[p]), reverse=True)
            for pair in sorted_pairs:
                final_matches.extend(data_cache[pair])
                duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                if len(final_matches) >= 30:
                    final_matches = final_matches[:30]
                    break
            
            if len(final_matches) < 30:
                for pair in sorted_pairs:
                    final_matches.extend(job for job in data_cache[pair] if job['job_id'] not in duplicate_tracker)
                    duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                    if len(final_matches) >= 30:
                        final_matches = final_matches[:30]
                        break
        
        # Phase 3: Tertiary Data Selection with Fallback
        if len(final_matches) < 30:
            pairs = [(interest1, interest2)
                     for interest1 in high_score_interests
                     for interest2 in third_highest_interests]
            
            for pair in pairs:
                if pair not in data_cache:
                    jobs = self.get_jobs_by_fields(pair)
                    filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                    data_cache[pair] = filtered_jobs
            
            sorted_pairs = sorted(pairs, key=lambda p: len(data_cache[p]), reverse=True)
            for pair in sorted_pairs:
                final_matches.extend(data_cache[pair])
                duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                if len(final_matches) >= 30:
                    final_matches = final_matches[:30]
                    break
            
            if len(final_matches) < 30:
                for pair in sorted_pairs:
                    final_matches.extend(job for job in data_cache[pair] if job['job_id'] not in duplicate_tracker)
                    duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                    if len(final_matches) >= 30:
                        final_matches = final_matches[:30]
                        break
        
        # Phase 4: Quaternary Data Selection with Fallback
        if len(final_matches) < 30:
            pairs = [(interest1, interest2)
                     for interest1 in high_score_interests
                     for interest2 in fourth_highest_interests]
            
            for pair in pairs:
                if pair not in data_cache:
                    jobs = self.get_jobs_by_fields(pair)
                    filtered_jobs = [job for job in jobs if job['education_level'] not in ['high school', 'associate']]
                    data_cache[pair] = filtered_jobs
            
            sorted_pairs = sorted(pairs, key=lambda p: len(data_cache[p]), reverse=True)
            for pair in sorted_pairs:
                final_matches.extend(data_cache[pair])
                duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                if len(final_matches) >= 30:
                    final_matches = final_matches[:30]
                    break
            
            if len(final_matches) < 30:
                for pair in sorted_pairs:
                    final_matches.extend(job for job in data_cache[pair] if job['job_id'] not in duplicate_tracker)
                    duplicate_tracker.update(job['job_id'] for job in data_cache[pair])
                    if len(final_matches) >= 30:
                        final_matches = final_matches[:30]
                        break
        
        return {
            "job_matches": [JobMatch(
                job_id=job['job_id'],
                job_title=job['job_title'],
                primary_fields=pair,
                description=f"This role combines expertise in {pair[0]} and {pair[1]}",
                education_level=job['education_level']
            ) for job in final_matches],
            "total_matches": len(final_matches)
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
            'total_matches': results['total_matches']
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
