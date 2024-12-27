import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';
import __ from '@/lang';

export const useTestProgressStore = defineStore('testProgress', {
  state: () => ({
    currentStage: 'holland_codes',
    stageConfig: {
      holland_codes: {
        name: __('test.Your_personality_archetype'),
        time: '~ 3 mins',
        totalQuestions: 48,
        nextStage: 'basic_interests',
        storeKey: 'hollandCodeStore',
        dataKey: 'progress',
        progressFields: {
          currentIndex: 'current_index',
          validResponses: 'validResponses',
          percentage: 'progress_percentage',
          completed: 'completed',
          specialData: 'archetypeDiscovery'
        }
      },
      basic_interests: {
        name: __('test.Your_career_matches'),
        time: '~ 3 mins',
        totalQuestions: 27,
        nextStage: 'degree',
        storeKey: 'basicInterestStore',
        dataKey: 'progress',
        progressFields: {
          currentIndex: 'current_index',
          validResponses: 'validResponses',
          percentage: 'progress_percentage',
          completed: 'completed',
          specialData: 'jobMatching'
        }
      },
      degree: {
        name: __('test.Your_degree_matches'),
        time: '~ 3 mins',
        totalQuestions: 7,
        nextStage: null,
        storeKey: 'degreeStore',
        dataKey: 'progress',
        progressFields: {
          currentIndex: 'current_index',
          validResponses: 'validResponses',
          percentage: 'progress_percentage',
          completed: 'completed',
          specialData: 'degreeMatching'
        }
      }
    },
    stages: {
      holland_codes: {
        completed: false,
        currentIndex: 0,
        validResponses: 0,
        percentage: 0,
        canTransition: false,
        archetypeDiscovery: null
      },
      basic_interests: {
        completed: false,
        currentIndex: 0,
        validResponses: 0,
        percentage: 0,
        canTransition: false,
        jobMatching: null
      },
      degree: {
        completed: false,
        currentIndex: 0,
        validResponses: 0,
        percentage: 0,
        canTransition: false,
        degreeMatching: null
      }
    }
  }),

  actions: {
    markStageComplete(stage) {
      if (!this.stages[stage]) return;

      this.stages[stage].completed = true;
      this.stages[stage].canTransition = true;
      this.stages[stage].percentage = 100;
    },

    updateStageProgress(stage, progressData) {
      if (!this.stages[stage]) return;

      const stageData = this.stages[stage];
      const stageConfig = this.stageConfig[stage];

      if (typeof progressData === 'object') {
        const fields = stageConfig.progressFields;
        
        stageData.currentIndex = progressData[fields.currentIndex] || progressData.currentIndex || 0;
        stageData.validResponses = progressData[fields.validResponses] || progressData.validResponses || 0;
        
        if (stageData.validResponses && stageConfig.totalQuestions) {
          stageData.percentage = Math.min(
            Math.round((stageData.validResponses / stageConfig.totalQuestions) * 100),
            100
          );
        } else {
          stageData.percentage = progressData[fields.percentage] || progressData.percentage || 0;
        }
        
        if (fields.specialData && progressData[fields.specialData]) {
          stageData[fields.specialData] = progressData[fields.specialData];
        }

        const isCompleted = progressData[fields.completed] || progressData.completed || false;
        if (isCompleted || stageData.percentage >= 100) {
          this.markStageComplete(stage);
        } else {
          stageData.completed = false;
          stageData.canTransition = false;
        }
      } else if (typeof progressData === 'number') {
        stageData.percentage = progressData;
        if (progressData >= 100) {
          this.markStageComplete(stage);
        }
      }
    },

    setCurrentStage(stage) {
      if (!this.stageConfig[stage]) return;
      this.currentStage = stage;
    },

    getStageSequence() {
      return Object.keys(this.stageConfig);
    },

    getNextStage(currentStage) {
      return this.stageConfig[currentStage]?.nextStage || null;
    },

    getPreviousStage(stage) {
      const sequence = this.getStageSequence();
      const currentIndex = sequence.indexOf(stage);
      return currentIndex > 0 ? sequence[currentIndex - 1] : null;
    },

    dumpState() {
      return {
        currentStage: this.currentStage,
        stages: Object.entries(this.stages).reduce((acc, [key, value]) => {
          acc[key] = { ...value };
          return acc;
        }, {})
      };
    },

    calculateProgress(stage) {
      const stageData = this.stages[stage];
      const config = this.stageConfig[stage];
      
      if (!stageData || !config) return 0;
      
      const validResponses = stageData.validResponses || 0;
      const totalQuestions = config.totalQuestions || 0;
      
      if (totalQuestions === 0) return 0;
      
      return Math.min(Math.round((validResponses / totalQuestions) * 100), 100);
    }
  },

  getters: {
    getStageInfo: (state) => (stage) => state.stageConfig[stage] || null,
    stageSequence: (state) => Object.keys(state.stageConfig),
    currentStageName: (state) => state.stageConfig[state.currentStage]?.name || '',
    nextStageName: (state) => {
      const nextStage = state.stageConfig[state.currentStage]?.nextStage;
      return nextStage ? state.stageConfig[nextStage]?.name : '';
    },
    isStageComplete: (state) => (stage) => {
      const stageData = state.stages[stage];
      if (!stageData) return false;

      return stageData.completed || stageData.percentage >= 100;
    },
    canTransitionFromStage: (state) => (stage) => state.stages[stage]?.canTransition || false,
    stageProgress: (state) => (stage) => {
      const stageData = state.stages[stage];
      if (!stageData) return 0;

      if (stageData.validResponses && state.stageConfig[stage]?.totalQuestions) {
        return Math.min(
          Math.round((stageData.validResponses / state.stageConfig[stage].totalQuestions) * 100),
          100
        );
      }

      return stageData.percentage || 0;
    },
    currentStageProgress: (state) => state.stages[state.currentStage]?.percentage || 0,
    
    stageStatus: (state) => (stage) => {
      const stageData = state.stages[stage];
      if (!stageData) return 'not-started';
      
      if (stageData.completed) return 'complete';
      if (stage === state.currentStage) return 'in-progress';
      
      const prevStage = state.getPreviousStage(stage);
      if (prevStage && state.stages[prevStage]?.completed) return 'upcoming';
      
      return 'not-started';
    },

    archetypeDiscovery: (state) => state.stages.holland_codes?.archetypeDiscovery || null,
    jobMatching: (state) => state.stages.basic_interests?.jobMatching || null,
    degreeMatching: (state) => state.stages.degree?.degreeMatching || null,
    
    shouldShowNextStage: (state) => {
      const currentStage = state.stages[state.currentStage];
      return currentStage?.completed && currentStage?.canTransition;
    },

    validResponsesCount: (state) => (stage) => {
      return state.stages[stage]?.validResponses || 0;
    }
  }
}); 