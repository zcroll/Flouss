<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class PersonalityArchetypeQuestion extends Model
//{
//    use HasFactory;
//
//    protected $fillable = [
//        'question',
//        'type',
//    ];
//}
//import re
//import requests
//from bs4 import BeautifulSoup
//
//def read_job_data(filename):
//    with open(filename, 'r') as file:
//        content = file.read()
//    pattern = r"\((\d+),\s*'([^']+)',\s*'([^']+)',"
//    return re.findall(pattern, content)
//
//def scrape_data(slug, job_name):
//    url = f"https://www.careerexplorer.com/careers/{slug}/"
//    try:
//        response = requests.get(url)
//        response.raise_for_status()
//        soup = BeautifulSoup(response.content, 'html.parser')
//
//        duties = []
//        types = []
//
//        # Scraping Duties and Responsibilities
//        h2_tag_duties = soup.find('h2', id=f"what-does-a-{slug}-do")
//        if h2_tag_duties:
//            duty_sections = h2_tag_duties.find_all_next(['p', 'ul'], limit=20)
//            current_category = None
//            for section in duty_sections:
//                if section.name == 'p':
//                    strong_tag = section.find('strong')
//                    em_tag = section.find('em')
//                    if strong_tag:
//                        if 'Duties and Responsibilities' in section.get_text(strip=True):
//                            current_category = 'General Duties'
//                        else:
//                            current_category = strong_tag.get_text(strip=True)
//                    elif em_tag:
//                        current_category = em_tag.get_text(strip=True)
//                elif section.name == 'ul' and current_category:
//                    for li in section.find_all('li'):
//                        duties.append((current_category, li.get_text(strip=True)))
//
//        # Scraping Types of Job
//        h2_tag_types = None
//        for h2 in soup.find_all('h2'):
//            if re.search(r'Types of', h2.get_text(), re.IGNORECASE) or re.search(f'Types of {job_name.lower()}', h2.get_text(), re.IGNORECASE):
//                h2_tag_types = h2
//                break
//
//        if h2_tag_types:
//            for li in h2_tag_types.find_next('ul').find_all('li'):
//                parts = li.get_text(strip=True).split(":")
//                type_name = parts[0].strip()
//                type_description = parts[1].strip() if len(parts) > 1 else ""
//                types.append((type_name, type_description))
//
//        return duties, types
//
//    except requests.RequestException as e:
//        print(f"Error fetching data: {e}")
//        return [], []
//
//def generate_sql_insert_duties(job_id, duties):
//    sql_statements = []
//    for category, description in duties:
//        sql_statements.append(f"INSERT INTO duties (job_id, duty_category, duty_description) VALUES ({job_id}, '{category.replace("'", "''")}', '{description.replace("'", "''")}');")
//    return "\n".join(sql_statements)
//
//def generate_sql_insert_types(job_id, types):
//    sql_statements = []
//    for type_name, type_description in types:
//        sql_statements.append(f"INSERT INTO job_types (job_id, type_name, type_description) VALUES ({job_id}, '{type_name.replace("'", "''")}', '{type_description.replace("'", "''")}');")
//    return "\n".join(sql_statements)
//
//def main():
//    job_data = read_job_data('data/careers.sql')
//    with open('job_details_insert.sql', 'w') as outfile:
//        outfile.write("-- Job Details SQL Insert Statements\n\n")
//        for job_id, job_name, slug in job_data:
//            print(f"Processing {slug}...")
//            duties, types = scrape_data(slug, job_name)
//            if duties:
//                sql_inserts_duties = generate_sql_insert_duties(job_id, duties)
//                outfile.write(sql_inserts_duties + "\n")
//            if types:
//                sql_inserts_types = generate_sql_insert_types(job_id, types)
//                outfile.write(sql_inserts_types + "\n")
//    print("SQL insert statements have been written to job_details_insert.sql")
//
//if __name__ == "__main__":
//    main()
