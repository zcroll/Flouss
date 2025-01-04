-- Create must_have_relationships table
CREATE TABLE IF NOT EXISTS must_have_relationships (
    must_have_id INT PRIMARY KEY,
    related_must_haves JSON,
    weight DECIMAL(3,2),
    related_interests JSON,
    related_skills JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert must-have relationships data
INSERT INTO must_have_relationships (must_have_id, related_must_haves, weight, related_interests, related_skills) VALUES
-- Professional Growth & Achievement Cluster
(1290, -- Making use of abilities
 '[1451, 890, 1327]', -- Achievement, Try own ideas, Planning own work
 0.90,
 '["Engineering", "Mathematics", "Information technology"]',
 '[1557, 1308, 486]'  -- Critical thinking, Analytical thinking, Mathematical skills
),

(1451, -- Achievement
 '[1290, 1742, 613]', -- Making use of abilities, Recognition, Advancement
 0.85,
 '["Business Management", "Engineering", "Finance"]',
 '[1557, 1308, 1687]' -- Critical thinking, Analytical thinking, Persuading
),

-- Autonomy & Innovation Cluster
(890, -- Try own ideas
 '[1290, 1327, 1194]', -- Making use of abilities, Planning own work, Variety
 0.85,
 '["Creative arts", "Engineering", "Information technology"]',
 '[1557, 1329, 1687]' -- Critical thinking, Learning new things, Persuading
),

(1327, -- Planning own work
 '[890, 1290, 221]', -- Try own ideas, Making use of abilities, Making decisions
 0.80,
 '["Engineering", "Business Management", "Information technology"]',
 '[1557, 1308, 1687]' -- Critical thinking, Analytical thinking, Persuading
),

-- Ethics & Values Cluster
(872, -- Ethical alignment
 '[973, 493, 1611]', -- Fair treatment, Helping people, Easy co-workers
 0.90,
 '["Healthcare service", "Social sciences", "Professional advising"]',
 '[492, 1378, 1650]' -- Helping people, Active listening, Scientific communication
),

(973, -- Fair treatment
 '[872, 1667, 493]', -- Ethics, Manager support, Helping people
 0.85,
 '["Healthcare service", "Social sciences", "Professional advising"]',
 '[492, 1378, 1650]' -- Helping people, Active listening, Scientific communication
),

-- Work Environment Cluster
(351, -- Good working conditions
 '[610, 973, 1611]', -- Steady employment, Fair treatment, Easy co-workers
 0.85,
 '["Healthcare service", "Nature and agriculture", "Professional advising"]',
 '[492, 1378, 1650]' -- Helping people, Active listening, Scientific communication
),

(610, -- Steady employment
 '[351, 973, 1667]', -- Working conditions, Fair treatment, Manager support
 0.85,
 '["Healthcare service", "Engineering", "Business Management"]',
 '[492, 1557, 1308]' -- Helping people, Critical thinking, Analytical thinking
),

-- Social & Support Cluster
(493, -- Helping people
 '[872, 1611, 1667]', -- Ethics, Easy co-workers, Manager support
 0.90,
 '["Healthcare service", "Social sciences", "Professional advising"]',
 '[492, 1378, 1650]' -- Helping people, Active listening, Scientific communication
),

(1611, -- Easy co-workers
 '[1667, 493, 973]', -- Manager support, Helping people, Fair treatment
 0.85,
 '["Healthcare service", "Social sciences", "Professional advising"]',
 '[1378, 492, 1687]' -- Active listening, Helping people, Persuading
),

-- Career Advancement Cluster
(613, -- Opportunities for advancement
 '[1730, 1742, 1451]', -- Earn money, Recognition, Achievement
 0.85,
 '["Business Management", "Finance", "Law"]',
 '[1687, 1557, 1308]' -- Persuading, Critical thinking, Analytical thinking
),

(1730, -- Earn money
 '[613, 1742, 320]', -- Advancement, Recognition, Job prestige
 0.85,
 '["Finance", "Business Management", "Law"]',
 '[1687, 1557, 1308]' -- Persuading, Critical thinking, Analytical thinking
),

-- Work Style Cluster
(314, -- Keeping busy
 '[1194, 1290, 221]', -- Variety, Making use of abilities, Making decisions
 0.75,
 '["Engineering", "Healthcare service", "Business Management"]',
 '[1557, 1308, 492]' -- Critical thinking, Analytical thinking, Helping people
),

(1194, -- Variety
 '[314, 890, 1290]', -- Keeping busy, Try own ideas, Making use of abilities
 0.80,
 '["Creative arts", "Healthcare service", "Professional advising"]',
 '[1329, 1687, 1378]' -- Learning new things, Persuading, Active listening
),

-- Authority & Recognition Cluster
(1367, -- Authority to direct
 '[1742, 1730, 613]', -- Recognition, Earn money, Advancement
 0.80,
 '["Business Management", "Law", "Politics"]',
 '[1687, 1557, 1378]' -- Persuading, Critical thinking, Active listening
),

(1742, -- Recognition
 '[1367, 1730, 320]', -- Authority, Earn money, Job prestige
 0.85,
 '["Business Management", "Law", "Politics"]',
 '[1687, 1557, 1378]' -- Persuading, Critical thinking, Active listening
),

-- Work Independence Cluster
(221, -- Making decisions independently
 '[1327, 890, 373]', -- Planning own work, Try own ideas, Work alone
 0.80,
 '["Engineering", "Information technology", "Finance"]',
 '[1557, 1308, 1687]' -- Critical thinking, Analytical thinking, Persuading
),

(373, -- Work alone
 '[221, 1327, 890]', -- Making decisions, Planning own work, Try own ideas
 0.75,
 '["Information technology", "Engineering", "Creative writing & journalism"]',
 '[1308, 1557, 1650]' -- Analytical thinking, Critical thinking, Scientific communication
),

-- Management Support Cluster
(1667, -- Manager support
 '[1611, 973, 493]', -- Easy co-workers, Fair treatment, Helping people
 0.85,
 '["Healthcare service", "Professional advising", "Social sciences"]',
 '[1378, 492, 1687]' -- Active listening, Helping people, Persuading
),

(320, -- Job prestige
 '[1742, 1730, 613]', -- Recognition, Earn money, Advancement
 0.80,
 '["Law", "Business Management", "Politics"]',
 '[1687, 1557, 1378]' -- Persuading, Critical thinking, Active listening
);

-- Create indexes for better performance
CREATE INDEX idx_must_have_relationships_weight ON must_have_relationships(weight);
CREATE INDEX idx_must_have_relationships_updated_at ON must_have_relationships(updated_at); 