import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';
import { useTestProgressStore } from './testProgressStore'; // Assuming this is where useTestProgressStore is defined

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
          const progressPercentage = totalItems > 0 ? (data.progress.current_index / totalItems) * 100 : 0;
          
          if (data.progress.current_index >= totalItems || data.progress.completed) {
            this.progress.completed = true;
            this.isTestComplete = true;
          }
        }
        
        this.setCurrentItem();
        this.initialized = true;
      }
    },

    setProgress(progress) {
      if (!progress) return;
      
      console.log('Setting progress:', progress);
      
      // Update all progress properties
      this.progress = progress;
      this.currentItemIndex = progress.current_index;
      
      // Convert response values to numbers
      if (progress.responses) {
        this.responses = Object.fromEntries(
          Object.entries(progress.responses).map(([key, value]) => [
            key,
            value !== null ? parseInt(value) : null
          ])
        );
      }

      // Update completion status and notify progress store
      const wasComplete = this.isTestComplete;
      this.isTestComplete = progress.completed || false;
      
      // If completion status changed to true, update progress store
      if (!wasComplete && this.isTestComplete) {
        const progressStore = useTestProgressStore();
        progressStore.updateStageProgress('holland_codes', {
          completed: true,
          currentIndex: this.currentItemIndex,
          validResponses: Object.values(this.responses).filter(v => v !== null).length,
          percentage: this.getProgressPercentage()
        });
      }
      
      this.setCurrentItem();
    },

    getProgressPercentage() {
      if (!this.responses) return 0;
      const totalResponses = Object.values(this.responses).filter(v => v !== null).length;
      const totalQuestions = this.hollandCodesData.items?.length || 0;
      return totalQuestions > 0 ? Math.round((totalResponses / totalQuestions) * 100) : 0;
    },

    updateProgressPercentage() {
      const totalItems = this.hollandCodesData?.items?.length || 0;
      if (totalItems === 0) {
        this.progress.progress_percentage = 0;
        return;
      }
      
      // Calculate progress based on current index
      this.progress.progress_percentage = Math.round((this.currentItemIndex / totalItems) * 100);
      
      // Update completion status
      this.progress.completed = this.currentItemIndex >= totalItems;
      this.isTestComplete = this.progress.completed;
      
      console.log('Progress updated:', {
        currentIndex: this.currentItemIndex,
        totalItems,
        percentage: this.progress.progress_percentage,
        completed: this.progress.completed,
        isTestComplete: this.isTestComplete
      });
    },

    setCurrentItem() {
      if (!this.hollandCodesData?.items) return;
      
      const item = this.hollandCodesData.items[this.currentItemIndex];
      const currentResponse = item ? this.responses[item.id] : null;
      
      console.log('Setting current item:', { 
        index: this.currentItemIndex, 
        item,
        currentResponse,
        allResponses: this.responses
      });
      
      this.currentItem = item || null;
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

    async submitAnswer(form) {
      try {
        this.loading = true;
        console.log('Submitting answer:', form);

        const formData = {
          ...form,
          answer: form.type === 'skipped' ? 0 : form.answer // Ensure skipped questions have answer = 0
        };

        await router.post(route('holland-codes.store-response'), formData, {
          preserveState: true,
          preserveScroll: true,
          preserveUrl: true,
          onSuccess: (page) => {
            if (page.props.progress) {
              // Update local response
              this.responses[formData.itemId] = formData.answer;
              this.setProgress(page.props.progress);
            }
          },
          onError: (errors) => {
            this.logError('submission', errors);
          },
          onFinish: () => {
            this.loading = false;
          }
        });
      } catch (error) {
        this.logError('submission', error);
        this.loading = false;
      }
    },

    async skipQuestion() {
      if (this.loading || !this.currentItem) return;

      try {
        this.loading = true;
        console.log('Skipping question:', this.currentItem.id);

        const skipData = {
          itemId: this.currentItem.id,
          answer: 0, // Use 0 to indicate skipped
          type: 'skipped',
          category: 'holland_codes',
          testStage: 'holland_codes'
        };

        await router.post(route('holland-codes.store-response'), skipData, {
          preserveState: true,
          preserveScroll: true,
          preserveUrl: true,
          onSuccess: (page) => {
            if (page.props.progress) {
              // Update local response
              this.responses[skipData.itemId] = 0;
              this.setProgress(page.props.progress);
            }
          },
          onError: (error) => {
            console.error('Skip error:', error);
            this.error = error.message || 'Failed to skip question';
          },
          onFinish: () => {
            this.loading = false;
          }
        });
      } catch (error) {
        console.error('Skip failed:', error);
        this.error = error.message || 'Failed to skip question';
        this.loading = false;
      }
    },

    nextQuestion() {
      if (this.currentItemIndex < this.hollandCodesData.items.length - 1) {
        this.currentItemIndex++;
        this.setCurrentItem();
      }
    },

    async goBack() {
      if (this.currentItemIndex <= 0 || this.loading) {
        console.log('Cannot go back:', { currentIndex: this.currentItemIndex, loading: this.loading });
        return;
      }

      try {
        console.log('Going back from index:', this.currentItemIndex);
        
        this.loading = true;
        await router.post(route('holland-codes.go-back'), {}, {
          preserveState: true,
          preserveScroll: true,
          preserveUrl: true,
          onSuccess: (page) => {
            console.log('Back navigation success:', page);
            const progress = page.props?.progress || page.progress;
            if (progress) {
              this.setProgress(progress);
            }
          },
          onError: (error) => {
            console.error('Back navigation error:', error);
            this.error = error.message || 'Failed to go back';
          },
          onFinish: () => {
            console.log('Back navigation finished');
            this.loading = false;
          }
        });
      } catch (error) {
        console.error('Back navigation failed:', error);
        this.error = error.message || 'Failed to go back';
        this.loading = false;
      }
    },

    async continueToNextSection() {
      if (this.loading) return;

      try {
        this.loading = true;
        await router.post(route('test.change-stage'), {
          fromStage: 'holland_codes',
          toStage: 'basic_interest'
        }, {
          preserveState: true,
          onSuccess: (page) => {
            console.log('Stage transition success:', page);
          },
          onError: (error) => {
            console.error('Stage transition error:', error);
            this.error = error.message || 'Failed to transition to next stage';
          },
          onFinish: () => {
            this.loading = false;
          }
        });
      } catch (error) {
        console.error('Stage transition failed:', error);
        this.error = error.message || 'Failed to transition to next stage';
        this.loading = false;
      }
    }
  },

  getters: {
    currentOptions(state) {
      return state.currentItem?.optionSet?.options || [];
    },
    progressPercentage(state) {
      return state.progress.progress_percentage;
    },
    isComplete(state) {
      return state.progress.completed;
    },
    isReady(state) {
      return state.initialized && !state.loading && !state.error;
    },
    canGoBack(state) {
      return state.currentItemIndex > 0 && !state.loading;
    },
    hasResponse(state) {
      return !!state.responses[state.currentItem?.id];
    },
    getResponse(state) {
      return state.responses[state.currentItem?.id];
    },
    totalQuestions(state) {
      return state.hollandCodesData.items.length;
    },
    answeredQuestions(state) {
      return Object.keys(state.responses).length;
    },
    hollandCodes(state) {
      return state.hollandCodesData;
    },
    hasErrors(state) {
      return Object.values(state.errors).some(error => error !== null);
    },
    getErrorMessages(state) {
      return Object.entries(state.errors)
        .filter(([_, error]) => error !== null)
        .map(([type, message]) => ({ type, message }));
    },
    archetypeDiscovery(state) {
      return state.progress?.archetypeDiscovery || null;
    }
  }
}); 