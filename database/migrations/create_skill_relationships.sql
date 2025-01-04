-- Create skill_relationships table
CREATE TABLE IF NOT EXISTS skill_relationships (
    skill_id INT PRIMARY KEY,
    related_skills JSON,
    weight DECIMAL(3,2),
    related_interests JSON,
    related_must_haves JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert skill relationships data
INSERT INTO skill_relationships (skill_id, related_skills, weight, related_interests, related_must_haves) VALUES
-- Mathematical/Technical Skills (486)
(486, 
 '[1513, 1329, 1308]', -- Related: Science, Learning new things, Analytical thinking
 0.85,
 '["Mathematics", "Engineering", "Information technology"]',
 '[1290, 1451, 1327]' -- Related: Making use of abilities, Achievement, Planning own work
),

-- Helping People (492)
(492,
 '[1378, 1308, 1687]', -- Related: Active listening, Analytical thinking, Persuading
 0.80,
 '["Healthcare service", "Professional advising", "Social sciences"]',
 '[493, 872, 973]' -- Related: Helping people, Ethics, Fair treatment
),

-- Installing Equipment (837)
(837,
 '[486, 1329, 1557]', -- Related: Mathematical skills, Learning new things, Critical thinking
 0.75,
 '["Engineering", "Information technology", "Skilled trades"]',
 '[1290, 1451, 314]' -- Related: Making use of abilities, Achievement, Keeping busy
),

-- Analytical Thinking (1308)
(1308,
 '[1557, 1513, 1687]', -- Related: Critical thinking, Science, Persuading
 0.90,
 '["Engineering", "Mathematics", "Finance"]',
 '[1290, 1451, 890]' -- Related: Making use of abilities, Achievement, Try own ideas
),

-- Learning New Things (1329)
(1329,
 '[1557, 1513, 1650]', -- Related: Critical thinking, Science, Scientific communication
 0.85,
 '["Information technology", "Engineering", "Life science"]',
 '[1290, 1451, 1194]' -- Related: Making use of abilities, Achievement, Variety
),

-- Active Listening (1378)
(1378,
 '[492, 1687, 1650]', -- Related: Helping people, Persuading, Scientific communication
 0.80,
 '["Healthcare service", "Professional advising", "Social sciences"]',
 '[493, 1611, 1667]' -- Related: Helping people, Easy co-workers, Manager support
),

-- Science (1513)
(1513,
 '[486, 1329, 1557]', -- Related: Mathematical skills, Learning new things, Critical thinking
 0.85,
 '["Life science", "Physical science", "Engineering"]',
 '[1290, 872, 1451]' -- Related: Making use of abilities, Ethics, Achievement
),

-- Critical Thinking (1557)
(1557,
 '[1308, 1513, 1687]', -- Related: Analytical thinking, Science, Persuading
 0.90,
 '["Engineering", "Mathematics", "Finance"]',
 '[1290, 1327, 890]' -- Related: Making use of abilities, Planning own work, Try own ideas
),

-- Scientific Communication (1650)
(1650,
 '[1557, 1513, 1378]', -- Related: Critical thinking, Science, Active listening
 0.85,
 '["Life science", "Healthcare service", "Professional advising"]',
 '[1290, 872, 1451]' -- Related: Making use of abilities, Ethics, Achievement
),

-- Persuading (1687)
(1687,
 '[1378, 1308, 1557]', -- Related: Active listening, Analytical thinking, Critical thinking
 0.80,
 '["Professional advising", "Finance", "Business Management"]',
 '[1367, 1611, 1742]' -- Related: Authority to direct, Easy co-workers, Recognition
);

-- Create indexes for better performance
CREATE INDEX idx_skill_relationships_weight ON skill_relationships(weight);
CREATE INDEX idx_skill_relationships_updated_at ON skill_relationships(updated_at); 