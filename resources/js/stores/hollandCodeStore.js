import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useHollandCodeStore = defineStore('hollandCode', {
  state: () => ({
    hollandCodesData: {
      id: null,
      type: null,
      title: null,
      lead_in_text: null,
      items: [],
      option_sets: []
    },
    currentItem: null,
    currentSetIndex: 0,
    currentItemIndex: 0,
    responses: {},
    loading: false,
    error: null,
    progress: {
      current_index: 0,
      responses: {},
      completed: false,
      progress_percentage: 0,
      archetypeDiscovery: null
    },
    initialized: false,
    isTestComplete: false,
    errors: {
      initialization: null,
      fetch: null,
      submission: null,
      navigation: null
    },
    debugLogs: []
  }),

  actions: {
    initialize(data) {
      if (data.hollandCodes && !this.initialized) {
        this.hollandCodesData = {
          id: data.hollandCodes.id,
          type: data.hollandCodes.type,
          title: data.hollandCodes.title,
          lead_in_text: data.hollandCodes.lead_in_text,
          items: data.hollandCodes.items || [],
          option_sets: data.hollandCodes.option_sets || []
        };
        
        if (data.progress) {
          this.setProgress(data.progress);
          
          // Check if test is completed based on progress
          const totalItems = this.hollandCodesData.items.length;
          const answeredItems = Object.keys(data.progress.responses || {}).length;
          const progressPercentage = totalItems > 0 ? (answeredItems / totalItems) * 100 : 0;
          
          if (progressPercentage === 100 || data.progress.completed) {
            this.progress.completed = true;
            this.isTestComplete = true;
          }
        }
        
        this.setCurrentItem();
        this.initialized = true;
      }
    },

    setProgress(progress) {
      this.progress = {
        current_index: progress.current_index || 0,
        responses: progress.responses || {},
        completed: progress.completed || false,
        progress_percentage: progress.progress_percentage || 0,
        archetypeDiscovery: progress.archetypeDiscovery || null
      };
      this.currentItemIndex = progress.current_index;
      this.responses = progress.responses || {};
      this.updateProgressPercentage();

      // Update completed state if progress is 100%
      if (this.progress.progress_percentage === 100) {
        this.progress.completed = true;
        this.isTestComplete = true;
      }
    },

    updateProgressPercentage() {
      const totalItems = this.hollandCodesData.items.length;
      const answeredItems = Object.keys(this.responses).length;
      this.progress.progress_percentage = totalItems > 0 ? (answeredItems / totalItems) * 100 : 0;
    },

    setCurrentItem() {
      if (this.hollandCodesData?.items?.length > 0) {
        const newItem = this.hollandCodesData.items[this.currentItemIndex] || null;
        this.currentItem = newItem ? { ...newItem } : null;
        this.logDebug('Current item set to:', this.currentItem);
      }
    },

    async fetchHollandCodes() {
      if (!this.initialized) {
        try {
          this.loading = true;
          router.visit(route('holland-codes.index'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
              this.initialize(page.props);
              this.loading = false;
            },
            onError: (errors) => {
              this.error = errors;
              this.loading = false;
            }
          });
        } catch (error) {
          this.error = error.message;
          this.loading = false;
        }
      }
    },

    logError(type, error) {
      this.errors[type] = error;
      this.debugLogs.push({
        type: 'error',
        timestamp: new Date(),
        message: error.message,
        stack: error.stack
      });
      console.error(`[HollandCode ${type} Error]:`, error);
    },

    logDebug(message, data = null) {
      this.debugLogs.push({
        type: 'debug',
        timestamp: new Date(),
        message,
        data
      });
      console.log(`[HollandCode Debug]:`, message, data);
    },

    async submitAnswer(formData) {
      try {
        this.loading = true;
        this.error = null;

        // Update local state immediately for responsive UI
        this.responses[formData.itemId] = {
          itemId: formData.itemId,
          answer: formData.answer,
          type: formData.type,
          category: formData.category
        };
        
        this.updateProgressPercentage();

        // Make API call
        await router.post(route('holland-codes.store-response'), formData, {
          preserveState: true,
          preserveScroll: true,
          onSuccess: (page) => {
            if (page.props.progress) {
              this.setProgress(page.props.progress);
            }
            this.nextQuestion();
            this.loading = false;
          },
          onError: (errors) => {
            this.logError('submission', errors);
            this.loading = false;
          }
        });
      } catch (error) {
        this.logError('submission', error);
        this.loading = false;
      }
    },

    skipQuestion() {
      const formData = {
        itemId: this.currentItem.id,
        answer: 0,
        type: 'skipped',
        category: this.currentItem.category || 'holland_codes',
        testStage: 'holland_codes'
      };

      this.logDebug('Skipping question with data:', formData);
      return this.submitAnswer(formData);
    },

    nextQuestion() {
      if (this.currentItemIndex < this.hollandCodesData.items.length - 1) {
        this.currentItemIndex++;
        this.setCurrentItem();
      }
    },

    async goBack() {
      try {
        router.post(route('holland-codes.go-back'), {}, {
          preserveState: true,
          preserveScroll: true,
          onSuccess: (response) => {
            this.progress = response.data.progress;
            this.currentItemIndex = response.data.progress.current_index;
            this.setCurrentItem();
          }
        });
      } catch (error) {
        this.error = error.message;
      }
    }
  },

  getters: {
    currentOptions: (state) => {
      if (!state.currentItem?.optionSet) return [];
      return state.currentItem.optionSet.options || [];
    },
    progressPercentage: (state) => {
      return state.progress.progress_percentage || 0;
    },
    isComplete: (state) => {
      return state.currentItemIndex >= (state.hollandCodesData?.items?.length || 0);
    },
    isReady: (state) => {
      return state.initialized && 
             state.hollandCodesData.id !== null && 
             state.currentItem !== null;
    },
    hasResponse: (state) => (itemId) => {
      return state.responses[itemId] !== undefined;
    },
    getResponse: (state) => (itemId) => {
      return state.responses[itemId] || null;
    },
    totalQuestions: (state) => {
      return state.hollandCodesData?.items?.length || 0;
    },
    answeredQuestions: (state) => {
      return Object.keys(state.responses || {}).length;
    },
    hollandCodes: (state) => ({
      ...state.hollandCodesData,
      items: state.hollandCodesData.items,
      option_sets: state.hollandCodesData.option_sets
    }),
    hasErrors: (state) => Object.values(state.errors).some(error => error !== null),
    getErrorMessages: (state) => Object.entries(state.errors)
      .filter(([_, error]) => error !== null)
      .map(([type, error]) => ({ type, message: error.message }))
  }
}); 