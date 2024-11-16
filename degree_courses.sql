-- Create the table if it doesn't exist
CREATE TABLE IF NOT EXISTS course_degree_similarity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(10),
    degree_id BIGINT UNSIGNED,
    degree_name VARCHAR(255),
    similarity_score FLOAT DEFAULT 1.0,
    INDEX (course_id),
    INDEX (degree_id),
    UNIQUE KEY unique_course_degree (course_id, degree_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert degree-course mappings with duplicate prevention
INSERT IGNORE INTO course_degree_similarity
(course_id, degree_id, degree_name, similarity_score)
SELECT DISTINCT
    course_id,
    degree_id,
    degree_name,
    1.0 as similarity_score
FROM (
    -- Your values here
    SELECT '1122' as course_id, 231 as degree_id, 'Agriculture' as degree_name UNION
    SELECT '1123', 513, 'Agronomie' UNION
    SELECT '1124', 484, 'Production végétale'
    -- ... continue with other values
) AS unique_mappings;

