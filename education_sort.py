import json

def categorize_courses():
    # Read the courses file
    with open('education_sport_course.json', 'r', encoding='utf-8') as f:
        data = json.load(f)

    # Create categories dictionary
    categorized_courses = {
        'Sports & Physical Education': [],
        'Teaching & Education': [],
        'Early Childhood & Family': [],
        'Educational Administration': [],
        'Languages Education': [],
        'Science Education': [],
        'Digital Learning': [],
        'Translation & Communication': [],
        'Other': []
    }

    # Keywords for categorization
    categories_keywords = {
        'Sports & Physical Education': [
            'sport', 'sportif', 'entrainement', 'éducation physique', 'parasport',
            'loisir', 'management du sport', 'encadrement sportif'
        ],
        'Teaching & Education': [
            'enseignement', 'didactique', 'pédagogique', 'éducation', 'formation',
            'التربية', 'التعليم'
        ],
        'Early Childhood & Family': [
            'enfance', 'familial', 'protection de l\'enfance', 'mineure'
        ],
        'Educational Administration': [
            'administration', 'management', 'gouvernance', 'gestion', 'ingénierie'
        ],
        'Languages Education': [
            'langue française', 'langue anglaise', 'langue arabe', 'langue amazigh',
            'langues étrangères', 'اللغة العربية', 'الأمازيغية'
        ],
        'Science Education': [
            'mathématiques', 'sciences physiques', 'sciences de la vie',
            'informatique', 'sciences industrielles'
        ],
        'Digital Learning': [
            'digital learning', 'e-learning', 'numérique', 'systèmes éducatifs intelligent'
        ],
        'Translation & Communication': [
            'traduction', 'communication', 'الترجمة', 'média'
        ]
    }

    # Categorize each course
    for course_id, course_name in data.items():
        course_name_lower = course_name.lower()
        categorized = False
        
        for category, keywords in categories_keywords.items():
            if any(keyword.lower() in course_name_lower for keyword in keywords):
                categorized_courses[category].append({
                    'id': course_id,
                    'name': course_name
                })
                categorized = True
                break
        
        if not categorized:
            categorized_courses['Other'].append({
                'id': course_id,
                'name': course_name
            })

    # Sort courses within each category by name
    for category in categorized_courses:
        categorized_courses[category].sort(key=lambda x: x['name'])

    # Create output structure
    sorted_data = {
        'courses_by_subject': {
            category: courses 
            for category, courses in categorized_courses.items()
            if courses  # Only include categories with courses
        }
    }

    # Write to new file
    with open('sorted_education_courses.json', 'w', encoding='utf-8') as f:
        json.dump(sorted_data, f, ensure_ascii=False, indent=2)

    # Print statistics
    print("\nCourse distribution:")
    for category, courses in categorized_courses.items():
        print(f"{category}: {len(courses)} courses")

if __name__ == "__main__":
    categorize_courses() 