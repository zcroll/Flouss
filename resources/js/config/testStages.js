import { useHollandCodeStore } from '@/stores/hollandCodeStore';
import { useBasicInterestStore } from '@/stores/basicInterestStore';
import TestQuestion from '@/Components/Test/TestQuestion.vue';
import Discovery from '@/Components/Test/Discovery.vue';

export const TEST_STAGES = {
  HOLLAND_CODES: 'holland_codes',
  BASIC_INTERESTS: 'basic_interests'
};

export const STAGE_ORDER = [
  TEST_STAGES.HOLLAND_CODES,
  TEST_STAGES.BASIC_INTERESTS
];

export const STAGE_CONFIG = {
  [TEST_STAGES.HOLLAND_CODES]: {
    name: 'Holland Codes',
    description: 'Discover your career interests and personality type',
    nextStage: TEST_STAGES.BASIC_INTERESTS,
    components: {
      test: TestQuestion,
      discovery: Discovery
    },
    store: useHollandCodeStore,
    totalQuestions: 48
  },
  [TEST_STAGES.BASIC_INTERESTS]: {
    name: 'Basic Interest',
    description: 'Explore your specific areas of interest',
    nextStage: null,
    components: {
      test: TestQuestion,
      discovery: Discovery
    },
    store: useBasicInterestStore,
    totalQuestions: 27
  }
};

export const getStageConfig = (stage) => STAGE_CONFIG[stage] || null;

export const getNextStage = (currentStage) => {
  const currentConfig = STAGE_CONFIG[currentStage];
  return currentConfig ? currentConfig.nextStage : null;
};

export const getStageStore = (stage) => {
  const config = STAGE_CONFIG[stage];
  return config ? config.store : null;
};

export const getStageComponent = (stage, type = 'test') => {
  const config = STAGE_CONFIG[stage];
  return config?.components?.[type] || null;
};

export const getStageName = (stage) => {
  const config = STAGE_CONFIG[stage];
  return config ? config.name : '';
};

export const getStageDescription = (stage) => {
  const config = STAGE_CONFIG[stage];
  return config ? config.description : '';
};

export const getTotalQuestions = (stage) => {
  const config = STAGE_CONFIG[stage];
  return config ? config.totalQuestions : 0;
};

export const isValidStage = (stage) => {
  return !!STAGE_CONFIG[stage];
};

export const getStageIndex = (stage) => {
  return STAGE_ORDER.indexOf(stage);
};

export const isPreviousStageComplete = (stage) => {
  const stageIndex = getStageIndex(stage);
  if (stageIndex <= 0) return true;
  
  const previousStage = STAGE_ORDER[stageIndex - 1];
  const previousStore = getStageStore(previousStage)();
  return previousStore?.isComplete || false;
}; 