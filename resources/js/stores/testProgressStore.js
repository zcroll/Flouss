import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useTestProgressStore = defineStore('testProgress', {
  state: () => ({
    debug: true,
    currentStage: 'holland_codes',
    stages: {
      holland_codes: {
        completed: false,
        nextStage: 'basic_interests',
        totalQuestions: 48,
        currentIndex: 0,
        validResponses: 0,
        percentage: 0,
        canTransition: false,
        debug: []
      },
      basic_interests: {
        completed: false,
        nextStage: 'workplace',
        totalQuestions: 27,
        currentIndex: 0,
        validResponses: 0,
        percentage: 0,
        canTransition: false,
        debug: []
      },
      workplace: {
        completed: false,
        nextStage: 'personality',
        url: '/test/workplace',
        canTransition: false,
        debug: []
      },
      personality: {
        completed: false,
        nextStage: null,
        canTransition: false,
        debug: []
      }
    }
  }),

  actions: {
    logDebug(stage, action, data) {
      if (!this.debug) return;
      
      const timestamp = new Date().toISOString();
      const debugEntry = {
        timestamp,
        action,
        data
      };
      
      if (this.stages[stage]) {
        this.stages[stage].debug.push(debugEntry);
        console.log(`[${stage}][${action}]`, data);
      }
    },

    markStageComplete(stage) {
      if (this.stages[stage]) {
        this.logDebug(stage, 'markComplete:before', {
          stage,
          currentState: { ...this.stages[stage] }
        });

        this.stages[stage].completed = true;
        this.stages[stage].canTransition = true;
        this.stages[stage].percentage = 100;

        // If this is holland_codes and we're in basic_interests, ensure it stays completed
        if (stage === 'holland_codes' && this.currentStage === 'basic_interests') {
          this.stages[stage].completed = true;
          this.stages[stage].canTransition = true;
        }

        this.logDebug(stage, 'markComplete:after', {
          stage,
          newState: { ...this.stages[stage] }
        });
      }
    },

    updateStageProgress(stage, progressData) {
      if (!this.stages[stage]) return;

      const previousState = { ...this.stages[stage] };
      const stageData = this.stages[stage];

      this.logDebug(stage, 'updateProgress:start', {
        stage,
        progressData,
        currentState: { ...stageData }
      });

      // If we're in basic_interests, ensure holland_codes stays completed
      if (this.currentStage === 'basic_interests' && stage === 'holland_codes') {
        stageData.completed = true;
        stageData.canTransition = true;
        stageData.percentage = 100;
        return;
      }

      // Update with controller data if provided
      if (typeof progressData === 'object') {
        // Update progress data
        stageData.currentIndex = progressData.current_index || progressData.currentIndex || 0;
        stageData.validResponses = progressData.valid_responses || progressData.validResponses || 0;
        stageData.percentage = progressData.progress_percentage || progressData.percentage || 0;
        
        // Handle completion status
        if (progressData.completed) {
          this.markStageComplete(stage);
        } else {
          stageData.completed = false;
          stageData.canTransition = false;
        }

        this.logDebug(stage, 'updateProgress:after', {
          previous: previousState,
          current: { ...stageData }
        });
      } 
      // Handle legacy percentage-only updates
      else if (typeof progressData === 'number') {
        stageData.percentage = progressData;
        if (progressData >= 100) {
          this.markStageComplete(stage);
        }
      }
    },

    async moveToNextStage() {
      const currentStage = this.currentStage;
      const currentStageData = this.stages[currentStage];
      const nextStage = currentStageData?.nextStage;

      this.logDebug(currentStage, 'moveToNextStage:attempt', {
        currentStage,
        nextStage,
        currentStageData: { ...currentStageData }
      });

      if (nextStage && this.stages[nextStage]) {
        if (currentStageData.completed && currentStageData.canTransition) {
          this.currentStage = nextStage;
          
          this.logDebug(nextStage, 'moveToNextStage:success', {
            from: currentStage,
            to: nextStage
          });

          if (this.stages[nextStage].url) {
            await router.visit(this.stages[nextStage].url);
          }
        } else {
          this.logDebug(currentStage, 'moveToNextStage:blocked', {
            reason: {
              notCompleted: !currentStageData.completed,
              cantTransition: !currentStageData.canTransition
            }
          });
        }
      }
    },

    setCurrentStage(stage) {
      if (!this.stages[stage]) return;

      this.logDebug(stage, 'setCurrentStage:before', {
        fromStage: this.currentStage,
        toStage: stage
      });

      // If moving to basic_interests, ensure holland_codes is marked as complete
      if (stage === 'basic_interests') {
        this.stages['holland_codes'].completed = true;
        this.stages['holland_codes'].canTransition = true;
        this.stages['holland_codes'].percentage = 100;
      }

      this.currentStage = stage;

      this.logDebug(stage, 'setCurrentStage:after', {
        currentStage: this.currentStage,
        stageState: { ...this.stages[stage] }
      });
    },

    resetStageTransition(stage) {
      if (this.stages[stage]) {
        const previousState = { ...this.stages[stage] };
        this.stages[stage].canTransition = false;
        
        this.logDebug(stage, 'resetTransition', {
          previous: previousState,
          current: { ...this.stages[stage] }
        });
      }
    },

    // Debug helper to dump current state
    dumpState() {
      return {
        currentStage: this.currentStage,
        stages: Object.entries(this.stages).reduce((acc, [key, value]) => {
          acc[key] = {
            ...value,
            debug: value.debug?.length || 0 // Just show count of debug entries
          };
          return acc;
        }, {})
      };
    }
  },

  getters: {
    currentStageName: (state) => state.currentStage,
    isStageComplete: (state) => (stage) => state.stages[stage]?.completed || false,
    canTransitionFromStage: (state) => (stage) => state.stages[stage]?.canTransition || false,
    nextStage: (state) => state.stages[state.currentStage]?.nextStage,
    nextStageUrl: (state) => {
      const nextStage = state.stages[state.currentStage]?.nextStage;
      return nextStage ? state.stages[nextStage]?.url : null;
    },
    stageProgress: (state) => (stage) => state.stages[stage]?.percentage || 0,
    currentStageProgress: (state) => state.stages[state.currentStage]?.percentage || 0,
    shouldShowNextStage: (state) => {
      const currentStage = state.stages[state.currentStage];
      const shouldShow = currentStage?.completed && currentStage?.canTransition;
      
      if (state.debug) {
        console.log('shouldShowNextStage check:', {
          stage: state.currentStage,
          completed: currentStage?.completed,
          canTransition: currentStage?.canTransition,
          shouldShow
        });
      }
      
      return shouldShow;
    },
    // Debug getter to access stage debug history
    stageDebugHistory: (state) => (stage) => state.stages[stage]?.debug || []
  }
}); 