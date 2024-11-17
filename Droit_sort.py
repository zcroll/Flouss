import json

def categorize_courses():
    # Read the courses file
    with open('Lettres_art.json', 'r', encoding='utf-8') as f:
        data = json.load(f)

    # Create categories dictionary
    categorized_courses = {
        'Law': [],
        'Art et Design': [],
        'Political Science': [],
        'Communication & Media': [],
        'Islamic Studies': [],
        'Languages & Literature': [],
        'Tourism & Management': [],
        'Anthropology & Heritage': [],
        'History': [],
        'Psychology & Human Sciences': [],
        'Other': []
    }

    # Keywords for categorization
    categories_keywords = {
        'Law': [
            'droit', 'juridique', 'justice', 'قانون', 'légal', 'judiciaire',
            'المنازعات', 'juridiction', 'العدالة', 'التشريع'
        ],
        'Art et Design': [
            'art', 'arts', 'artistique', 'الفن', 'الفنون', 'cinéma', 'théâtre',
            'musique', 'peinture', 'sculpture', 'audiovisuel', 'créatif'
        ],
        'Political Science': [
            'politique', 'السياس', 'الدولية', 'international', 'diplomatie',
            'الدبلوماسي', 'gouvernance', 'الحكامة'
        ],
        'Communication & Media': [
            'communication', 'média', 'الإعلام', 'audiovisuel', 'journalisme',
            'publicité', 'التواصل'
        ],
        'Islamic Studies': [
            'الشريعة', 'الإسلام', 'الفقه', 'القرآن', 'islamique', 'الدين'
        ],
        'Languages & Literature': [
            'langue', 'littérature', 'linguistique', 'اللغة', 'الأدب',
            'français', 'english', 'español', 'translation', 'الترجمة'
        ],
        'Tourism & Management': [
            'tourisme', 'hôtel', 'السياحة', 'الفندقة'
        ],
        'Anthropology & Heritage': [
            'anthropologie', 'muséologie', 'archéologie', 'patrimoine',
            'التراث', 'الآثار'
        ],
        'History': [
            'histoire', 'historique', 'التاريخ', 'civilization', 'الحضارة'
        ],
        'Psychology & Human Sciences': [
            'psychologie', 'علم النفس', 'sociologie', 'علم الاجتماع',
            'sciences humaines', 'العلوم الإنسانية'
        ]
    }

    # Categorize each course
    for course in data['courses']:
        course_name = course['name'].lower()
        categorized = False
        
        for category, keywords in categories_keywords.items():
            if any(keyword.lower() in course_name for keyword in keywords):
                categorized_courses[category].append(course)
                categorized = True
                break
        
        if not categorized:
            categorized_courses['Other'].append(course)

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
    with open('sorted_Lettre_courses.json', 'w', encoding='utf-8') as f:
        json.dump(sorted_data, f, ensure_ascii=False, indent=2)

    # Print statistics
    print("\nCourse distribution:")
    for category, courses in categorized_courses.items():
        print(f"{category}: {len(courses)} courses")

if __name__ == "__main__":
    categorize_courses()