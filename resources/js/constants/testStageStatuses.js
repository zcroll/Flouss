export const TEST_STAGE_STATUSES = {
  NOT_STARTED: 'not-started',
  IN_PROGRESS: 'in-progress',
  COMPLETE: 'complete',
  INCOMPLETE: 'incomplete',
  UPCOMING: 'upcoming',
  LOCKED: 'locked'
};

export const TEST_STAGES = {
  HOLLAND_CODES: 'holland_codes',
  BASIC_INTERESTS: 'basic_interests',
  DEGREE: 'degree',
  PERSONALITY: 'personality'
};

export const TEST_STAGE_SEQUENCE = [
  TEST_STAGES.HOLLAND_CODES,
  TEST_STAGES.BASIC_INTERESTS, 
  TEST_STAGES.DEGREE,
  TEST_STAGES.PERSONALITY
]; 