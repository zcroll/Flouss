import mysql.connector
import json
import os
from dataclasses import dataclass
from typing import Dict, List, Tuple
import sys

@dataclass
class JobMatch:
    job_id: int
    job_title: str
    primary_fields: Tuple[str, str]
    description: str
    education_level: str
    match_score: float
    interest_match: Dict[str, float]
    skill_match: Dict[str, float]
    must_have_match: Dict[str, float]

    def to_dict(self):
        return {
            'job_id': self.job_id,
            'job_title': self.job_title,
            'primary_fields': self.primary_fields,
            'description': self.description,
            'education_level': self.education_level,
            'match_score': self.match_score,
            'interest_match': self.interest_match,
            'skill_match': self.skill_match,
            'must_have_match': self.must_have_match
        }

class JobMatcher:
    def __init__(self, user_preferences: Dict):
        """Initialize with user preferences and create relationship mappings"""
        self.conn = mysql.connector.connect(
            host=os.environ.get('DB_HOST', 'localhost'),
            user=os.environ.get('DB_USER', 'root'),
            password=os.environ.get('DB_PASSWORD', 'new_password'),
            database=os.environ.get('DB_NAME', 'bigdataa'),
            charset='utf8mb4',
            collation='utf8mb4_unicode_ci'
        )
        self.cursor = self.conn.cursor(dictionary=True)
        self.user_preferences = user_preferences
        self.allowed_education = ["Master's", "Doctorate", "Bachelor's"]
        
        # Initialize relationship mappings
        self.initialize_relationships()
        
        # Verify database structure
        if not self.verify_database_structure():
            raise Exception("Database structure verification failed")

    def initialize_relationships(self):
        """Initialize all relationship mappings"""
        self.interest_relationships = self.get_interest_relationships()
        self.skill_relationships = self.get_skill_relationships()
        self.must_have_relationships = self.get_must_have_relationships()
        self.build_relationship_mappings()

    def get_interest_relationships(self):
        """Get interest relationships from database"""
        query = """
        SELECT 
            interest_name,
            related_interests,
            weight,
            related_skills,
            related_must_haves
        FROM interest_relationships
        """
        self.cursor.execute(query)
        relationships = {}
        for row in self.cursor.fetchall():
            relationships[row['interest_name']] = {
                'related': json.loads(row['related_interests']),
                'weight': float(row['weight']),
                'related_skills': json.loads(row['related_skills']),
                'related_must_haves': json.loads(row['related_must_haves'])
            }
        return relationships

    def get_skill_relationships(self):
        """Get skill relationships from database"""
        query = """
        SELECT 
            skill_id,
            related_skills,
            weight,
            related_interests,
            related_must_haves
        FROM skill_relationships
        """
        self.cursor.execute(query)
        relationships = {}
        for row in self.cursor.fetchall():
            relationships[row['skill_id']] = {
                'related_skills': json.loads(row['related_skills']),
                'weight': float(row['weight']),
                'related_interests': json.loads(row['related_interests']),
                'related_must_haves': json.loads(row['related_must_haves'])
            }
        return relationships

    def get_must_have_relationships(self):
        """Get must-have relationships from database"""
        query = """
        SELECT 
            must_have_id,
            related_must_haves,
            weight,
            related_interests,
            related_skills
        FROM must_have_relationships
        """
        self.cursor.execute(query)
        relationships = {}
        for row in self.cursor.fetchall():
            relationships[row['must_have_id']] = {
                'related_must_haves': json.loads(row['related_must_haves']),
                'weight': float(row['weight']),
                'related_interests': json.loads(row['related_interests']),
                'related_skills': json.loads(row['related_skills'])
            }
        return relationships

    def build_relationship_mappings(self):
        """Build reverse mappings for quick interest relationship lookups"""
        self.related_interests = {}
        for interest, data in self.interest_relationships.items():
            self.related_interests[interest] = set(data['related'])
            # Add reverse relationships
            for related in data['related']:
                if related not in self.related_interests:
                    self.related_interests[related] = set()
                self.related_interests[related].add(interest)

    def verify_database_structure(self):
        """Verify that all required tables exist and have data"""
        try:
            tables_to_check = [
                'job_requirement',
                'job_skills',
                'job_must_haves',
                'interest_relationships',
                'skill_relationships',
                'must_have_relationships'
            ]
            
            for table in tables_to_check:
                query = f"SELECT COUNT(*) as count FROM {table}"
                self.cursor.execute(query)
                result = self.cursor.fetchone()
                if result['count'] == 0:
                    print(f"Warning: No data found in {table}")
                    return False
            return True

        except mysql.connector.Error as err:
            print(f"Database error: {err}")
            return False

    def get_matching_jobs(self) -> Dict:
        """Find and rank matching jobs"""
        try:
            # Get all jobs with their requirements
            query = """
            SELECT 
                j.id as job_id,
                j.name as job_title,
                j.education_level,
                GROUP_CONCAT(DISTINCT jr.scale_name) as fields,
                GROUP_CONCAT(DISTINCT jcs.cant_stand_id) as cant_stands
            FROM job_infos j
            LEFT JOIN job_requirement jr ON j.id = jr.job_id
            LEFT JOIN job_cant_stands jcs ON j.id = jcs.job_id
            WHERE j.education_level IN ('Bachelor''s', 'Master''s', 'Doctorate')
            GROUP BY j.id, j.name, j.education_level
            """
            
            self.cursor.execute(query)
            jobs = self.cursor.fetchall()
            
            # Process jobs and calculate scores
            job_matches = []
            for job in jobs:
                scores = self.calculate_job_scores(job['job_id'])
                
                # Calculate final score
                final_score = scores['total_score']
                
                # Get primary fields
                fields = job['fields'].split(',') if job['fields'] else []
                primary_fields = tuple(fields[:2] if fields else ['General', 'General'])
                
                # Create job match object
                match = JobMatch(
                    job_id=job['job_id'],
                    job_title=job['job_title'],
                    primary_fields=primary_fields,
                    description=self.generate_job_description(scores),
                    education_level=job['education_level'],
                    match_score=final_score,
                    interest_match=scores['interest_match'],
                    skill_match=scores['skill_match'],
                    must_have_match=scores['must_have_match']
                )
                
                job_matches.append(match)
            
            # Sort matches by score
            job_matches.sort(key=lambda x: x.match_score, reverse=True)
            
            # Return top 20 matches
            return {
                "job_matches": [match.to_dict() for match in job_matches[:20]],
                "total_matches": len(job_matches)
            }

        except Exception as e:
            print(f"Error in get_matching_jobs: {str(e)}")
            return {"job_matches": [], "total_matches": 0}

    def calculate_job_scores(self, job_id: int) -> Dict:
        """Calculate job scores with interests as primary filter"""
        # Calculate component scores
        interest_match = self.calculate_interest_score(job_id)
        skill_match = self.calculate_skill_score(job_id)
        must_have_match = self.calculate_must_have_score(job_id)
        
        # Calculate total score
        total_score = (
            interest_match['score'] * 0.5 +
            skill_match['score'] * 0.25 +
            must_have_match['score'] * 0.25
        )
        
        return {
            'total_score': total_score,
            'interest_match': interest_match['details'],
            'skill_match': skill_match['details'],
            'must_have_match': must_have_match['details']
        }

    def calculate_interest_score(self, job_id: int) -> Dict:
        """Calculate interest match score"""
        query = """
        SELECT jr.scale_name
        FROM job_requirement jr
        WHERE jr.job_id = %s
        AND jr.scale_name IS NOT NULL
        """
        self.cursor.execute(query, (job_id,))
        job_interests = {row['scale_name']: 1.0 for row in self.cursor.fetchall()}
        
        if not job_interests:
            return {'score': 0, 'details': {}}
        
        interest_scores = {}
        total_score = 0
        total_weight = 0
        
        for interest, score in self.user_preferences['interest_scores'].items():
            if interest in job_interests:
                normalized_score = score / 5.0
                weight = self.interest_relationships.get(interest, {}).get('weight', 0.6)
                
                interest_scores[interest] = score
                total_score += normalized_score * weight
                total_weight += weight
        
        if total_weight == 0:
            return {'score': 0, 'details': interest_scores}
            
        final_score = total_score / total_weight
        return {'score': final_score, 'details': interest_scores}

    def calculate_skill_score(self, job_id: int) -> Dict:
        """Calculate skill match score"""
        query = """
        SELECT js.skill_id, js.required_proficiency_level, js.is_primary, js.weight,
               i.text as skill_name
        FROM job_skills js
        JOIN items i ON js.skill_id = i.id
        WHERE js.job_id = %s
        """
        self.cursor.execute(query, (job_id,))
        job_skills = self.cursor.fetchall()
        
        if not job_skills:
            return {'score': 0, 'details': {}}
        
        skill_scores = {}
        total_score = 0
        total_weight = 0
        
        for skill in job_skills:
            user_score = self.user_preferences['skill_scores'].get(skill['skill_id'], 0)
            weight = float(skill['weight']) * (1.5 if skill['is_primary'] else 1.0)
            
            match_score = (5 - abs(user_score - skill['required_proficiency_level'])) / 5.0
            skill_scores[skill['skill_name']] = match_score * 5
            
            total_score += match_score * weight
            total_weight += weight
        
        if total_weight == 0:
            return {'score': 0, 'details': skill_scores}
            
        final_score = total_score / total_weight
        return {'score': final_score, 'details': skill_scores}

    def calculate_must_have_score(self, job_id: int) -> Dict:
        """Calculate must-have match score"""
        query = """
        SELECT jm.must_have_id, jm.required_proficiency_level, jm.is_primary, jm.weight,
               i.text as must_have_name
        FROM job_must_haves jm
        JOIN items i ON jm.must_have_id = i.id
        WHERE jm.job_id = %s
        """
        self.cursor.execute(query, (job_id,))
        job_must_haves = self.cursor.fetchall()
        
        if not job_must_haves:
            return {'score': 0, 'details': {}}
        
        must_have_scores = {}
        total_score = 0
        total_weight = 0
        
        for must_have in job_must_haves:
            user_score = self.user_preferences['must_have_scores'].get(must_have['must_have_id'], 0)
            weight = float(must_have['weight']) * (1.5 if must_have['is_primary'] else 1.0)
            
            match_score = (5 - abs(user_score - must_have['required_proficiency_level'])) / 5.0
            must_have_scores[must_have['must_have_name']] = match_score * 5
            
            total_score += match_score * weight
            total_weight += weight
        
        if total_weight == 0:
            return {'score': 0, 'details': must_have_scores}
            
        final_score = total_score / total_weight
        return {'score': final_score, 'details': must_have_scores}

    def generate_job_description(self, scores: Dict) -> str:
        """Generate a descriptive summary of the job match"""
        description = []
        
        # Add interest match description
        if scores['interest_match']:
            top_interests = sorted(scores['interest_match'].items(), key=lambda x: x[1], reverse=True)[:2]
            description.append(f"Strong interest alignment in {top_interests[0][0]}")
            if len(top_interests) > 1:
                description.append(f"and {top_interests[1][0]}")
        
        # Add skill match description
        if scores['skill_match']:
            top_skills = sorted(scores['skill_match'].items(), key=lambda x: x[1], reverse=True)[:2]
            description.append(f"Key skills match: {top_skills[0][0]}")
            if len(top_skills) > 1:
                description.append(f"and {top_skills[1][0]}")
        
        return ". ".join(description) + "."

    def close(self):
        """Close database connection"""
        if hasattr(self, 'cursor') and self.cursor:
            self.cursor.close()
        if hasattr(self, 'conn') and self.conn:
            self.conn.close()

def main():
    """Main function to handle command line execution"""
    if len(sys.argv) != 2:
        print("Usage: python JobMatch.py <preferences_json>")
        sys.exit(1)
    
    try:
        # Parse preferences from command line argument
        preferences = json.loads(sys.argv[1])
        
        # Initialize matcher and get results
        matcher = JobMatcher(preferences)
        results = matcher.get_matching_jobs()
        
        # Output results as JSON
        print(json.dumps(results))
        
    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "job_matches": [],
            "total_matches": 0
        }))
    finally:
        if 'matcher' in locals():
            matcher.close()

if __name__ == "__main__":
    main()