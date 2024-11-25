import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useTestProgressStore = defineStore('testProgress', {
  state: () => ({
    currentStage: 'holland_codes',
    stages: {
      holland_codes: {
        completed: false,
        nextStage: 'basic_interests',
      },
      basic_interests: {
        completed: false,
        nextStage: 'workplace',
      },
      workplace: {
        completed: false,
        nextStage: 'personality',
        url: '/test/workplace'
      },
      personality: {
        completed: false,
        nextStage: null,
      }
    }
  }),

  actions: {
    markStageComplete(stage) {
      if (this.stages[stage]) {
        this.stages[stage].completed = true;
      }
    },

    async moveToNextStage() {
      const currentStage = this.currentStage;
      const nextStage = this.stages[currentStage]?.nextStage;

      if (nextStage && this.stages[nextStage]) {
        this.currentStage = nextStage;
        await router.visit(this.stages[nextStage].url);
      }
    },

    setCurrentStage(stage) {
      if (this.stages[stage]) {
        this.currentStage = stage;
      }
    }
  },

  getters: {
    currentStageName: (state) => state.currentStage,
    isStageComplete: (state) => (stage) => state.stages[stage]?.completed || false,
    nextStage: (state) => state.stages[state.currentStage]?.nextStage,
    nextStageUrl: (state) => {
      const nextStage = state.stages[state.currentStage]?.nextStage;
      return nextStage ? state.stages[nextStage]?.url : null;
    }
  }
}); 