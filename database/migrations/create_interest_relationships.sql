-- Create interest_relationships table
CREATE TABLE IF NOT EXISTS interest_relationships (
    interest_name VARCHAR(100) PRIMARY KEY,
    related_interests JSON,
    weight DECIMAL(3,2),
    related_skills JSON,
    related_must_haves JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert interest relationships data
INSERT INTO interest_relationships (interest_name, related_interests, weight, related_skills, related_must_haves) VALUES
-- Technology & Engineering Cluster
('Information technology',
 '["Engineering", "Mathematics", "Physical science"]',
 0.85,
 '[486, 1513, 1308]', -- Mathematical skills, Science, Analytical thinking
 '[1290, 890, 1451]'  -- Making use of abilities, Try own ideas, Achievement
),

('Engineering',
 '["Information technology", "Mathematics", "Physical science"]',
 0.85,
 '[486, 1557, 1308]', -- Mathematical skills, Critical thinking, Analytical thinking
 '[1290, 890, 1451]'  -- Making use of abilities, Try own ideas, Achievement
),

-- Healthcare & Sciences Cluster
('Healthcare service',
 '["Life science", "Professional advising", "Social sciences"]',
 0.90,
 '[492, 1378, 1650]', -- Helping people, Active listening, Scientific communication
 '[493, 872, 973]'    -- Helping people, Ethics, Fair treatment
),

('Life science',
 '["Healthcare service", "Physical science", "Nature and agriculture"]',
 0.85,
 '[1513, 1650, 1308]', -- Science, Scientific communication, Analytical thinking
 '[1290, 872, 1451]'   -- Making use of abilities, Ethics, Achievement
),

-- Business & Finance Cluster
('Finance',
 '["Mathematics", "Business Management", "Professional advising"]',
 0.85,
 '[1308, 1557, 1687]', -- Analytical thinking, Critical thinking, Persuading
 '[1290, 1730, 613]'   -- Making use of abilities, Earn money, Advancement
),

('Business Management',
 '["Finance", "Professional advising", "Sales"]',
 0.85,
 '[1687, 1557, 1378]', -- Persuading, Critical thinking, Active listening
 '[1367, 1730, 613]'   -- Authority, Earn money, Advancement
),

-- Nature & Environment Cluster
('Nature and agriculture',
 '["Green industry", "Life science", "Physical science"]',
 0.80,
 '[1513, 1650, 492]',  -- Science, Scientific communication, Helping people
 '[872, 351, 610]'     -- Ethics, Working conditions, Steady employment
),

('Green industry',
 '["Nature and agriculture", "Engineering", "Physical science"]',
 0.80,
 '[1513, 1557, 1308]', -- Science, Critical thinking, Analytical thinking
 '[872, 890, 1451]'    -- Ethics, Try own ideas, Achievement
),

-- Social & Services Cluster
('Social sciences',
 '["Professional advising", "Healthcare service", "Creative writing & journalism"]',
 0.80,
 '[492, 1378, 1687]',  -- Helping people, Active listening, Persuading
 '[493, 1611, 1667]'   -- Helping people, Easy co-workers, Manager support
),

('Professional advising',
 '["Social sciences", "Business Management", "Healthcare service"]',
 0.85,
 '[1378, 1687, 492]',  -- Active listening, Persuading, Helping people
 '[493, 1611, 1667]'   -- Helping people, Easy co-workers, Manager support
),

-- Creative & Arts Cluster
('Creative arts',
 '["Music", "Creative writing & journalism", "Beauty & style"]',
 0.75,
 '[1687, 1650, 1329]', -- Persuading, Scientific communication, Learning new things
 '[890, 1194, 1290]'   -- Try own ideas, Variety, Making use of abilities
),

('Music',
 '["Creative arts", "Creative writing & journalism"]',
 0.75,
 '[1687, 1650, 1329]', -- Persuading, Scientific communication, Learning new things
 '[890, 1194, 1290]'   -- Try own ideas, Variety, Making use of abilities
),

-- Physical Sciences & Mathematics
('Mathematics',
 '["Engineering", "Information technology", "Finance"]',
 0.90,
 '[486, 1308, 1557]',  -- Mathematical skills, Analytical thinking, Critical thinking
 '[1290, 1451, 890]'   -- Making use of abilities, Achievement, Try own ideas
),

('Physical science',
 '["Life science", "Engineering", "Mathematics"]',
 0.85,
 '[1513, 1308, 1557]', -- Science, Analytical thinking, Critical thinking
 '[1290, 872, 1451]'   -- Making use of abilities, Ethics, Achievement
),

-- Writing & Communication
('Creative writing & journalism',
 '["Creative arts", "Social sciences", "Professional advising"]',
 0.75,
 '[1650, 1378, 1687]', -- Scientific communication, Active listening, Persuading
 '[890, 1194, 1290]'   -- Try own ideas, Variety, Making use of abilities
),

-- Service Industries
('Beauty & style',
 '["Creative arts", "Sales", "Professional advising"]',
 0.75,
 '[1687, 1378, 492]',  -- Persuading, Active listening, Helping people
 '[493, 1611, 1194]'   -- Helping people, Easy co-workers, Variety
),

('Sales',
 '["Business Management", "Professional advising", "Beauty & style"]',
 0.80,
 '[1687, 1378, 492]',  -- Persuading, Active listening, Helping people
 '[1730, 613, 1367]'   -- Earn money, Advancement, Authority
),

-- Public Service & Military
('Military',
 '["Protective services", "Athletics", "Flying"]',
 0.80,
 '[1557, 1308, 492]',  -- Critical thinking, Analytical thinking, Helping people
 '[1290, 872, 351]'    -- Making use of abilities, Ethics, Working conditions
),

('Protective services',
 '["Military", "Law", "Professional advising"]',
 0.80,
 '[492, 1557, 1378]',  -- Helping people, Critical thinking, Active listening
 '[493, 872, 351]'     -- Helping people, Ethics, Working conditions
),

-- Law & Politics
('Law',
 '["Politics", "Professional advising", "Business Management"]',
 0.85,
 '[1557, 1687, 1378]', -- Critical thinking, Persuading, Active listening
 '[1290, 872, 1367]'   -- Making use of abilities, Ethics, Authority
),

('Politics',
 '["Law", "Professional advising", "Social sciences"]',
 0.85,
 '[1687, 1557, 1378]', -- Persuading, Critical thinking, Active listening
 '[1367, 1742, 320]'   -- Authority, Recognition, Job prestige
);

-- Create indexes for better performance
CREATE INDEX idx_interest_relationships_weight ON interest_relationships(weight);
CREATE INDEX idx_interest_relationships_updated_at ON interest_relationships(updated_at); 