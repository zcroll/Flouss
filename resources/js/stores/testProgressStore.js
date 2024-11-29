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
        const previousState = { ...this.stages[stage] };
        
        this.stages[stage].completed = true;
        this.stages[stage].canTransition = true;
        
        this.logDebug(stage, 'markStageComplete', {
          previous: previousState,
          current: { ...this.stages[stage] }
        });
      }
    },

    updateStageProgress(stage, progressData) {
      if (this.stages[stage]) {
        const previousState = { ...this.stages[stage] };
        const stageData = this.stages[stage];

        // Update with controller data if provided
        if (typeof progressData === 'object') {
          this.logDebug(stage, 'updateProgress:before', {
            progressData,
            currentState: { ...stageData }
          });

          // Update progress data
          stageData.currentIndex = progressData.currentIndex || 0;
          stageData.validResponses = progressData.validResponses || 0;
          stageData.percentage = progressData.percentage || 0;
          
          // Handle completion status
          if (progressData.completed) {
            console.log('Marking stage complete:', stage, progressData);
            this.markStageComplete(stage);
          } else {
            stageData.completed = false;
            stageData.canTransition = false;
          }

          this.logDebug(stage, 'updateProgress:after', {
            previous: previousState,
            current: { ...stageData },
            changes: {
              currentIndex: stageData.currentIndex !== previousState.currentIndex,
              validResponses: stageData.validResponses !== previousState.validResponses,
              percentage: stageData.percentage !== previousState.percentage,
              completed: stageData.completed !== previousState.completed,
              canTransition: stageData.canTransition !== previousState.canTransition
            }
          });
        } 
        // Handle legacy percentage-only updates
        else if (typeof progressData === 'number') {
          this.logDebug(stage, 'updateProgress:legacy', {
            progressData,
            currentState: { ...stageData }
          });

          stageData.percentage = progressData;
          stageData.completed = progressData >= 100;
          stageData.canTransition = progressData >= 100;
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
      if (this.stages[stage]) {
        const previousStage = this.currentStage;
        const previousStageData = this.stages[previousStage];
        
        // Log the transition
        this.logDebug('transition', 'setCurrentStage:start', {
          from: previousStage,
          to: stage,
          previousStageState: { ...previousStageData },
          nextStageState: { ...this.stages[stage] }
        });

        // Update the current stage
        this.currentStage = stage;

        // Ensure proper state of stages
        if (previousStageData) {
          if (!previousStageData.completed) {
            previousStageData.canTransition = false;
          }
        }

        // Log the completion
        this.logDebug('transition', 'setCurrentStage:complete', {
          currentStage: this.currentStage,
          previousStage: previousStage,
          stageStates: {
            previous: { ...previousStageData },
            current: { ...this.stages[stage] }
          }
        });
      }
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