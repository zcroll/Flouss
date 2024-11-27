import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useBasicInterestStore = defineStore('basicInterest', {
    state: () => ({
        basicInterestData: {
            id: null,
            title: null,
            lead_in_text: null,
            items: [],
            option_sets: []
        },
        currentItem: null,
        currentItemIndex: 0,
        responses: {},
        progress: {
            current_index: 0,
            responses: {},
            completed: false,
            progress_percentage: 0,
            validResponses: 0,
            totalQuestions: 27
        },
        loading: false,
        error: null,
        debug: true
    }),

    actions: {
        logDebug(action, data) {
            if (!this.debug) return;
            console.log(`[BasicInterestStore][${action}]`, {
                ...data,
                timestamp: new Date().toISOString()
            });
        },

        initialize(data) {
            this.logDebug('initialize', { data });
            
            if (data.basicInterest) {
                this.basicInterestData = {
                    id: data.basicInterest.id,
                    title: data.basicInterest.title,
                    lead_in_text: data.basicInterest.lead_in_text,
                    items: data.basicInterest.items || [],
                    option_sets: data.basicInterest.option_sets || []
                };
                
                if (data.progress) {
                    this.setProgress(data.progress);
                }
                
                this.setCurrentItem();
            }
        },

        setProgress(progress) {
            if (!progress) return;
            
            const oldProgress = { ...this.progress };
            
            this.logDebug('setProgress:before', {
                oldProgress,
                newProgress: progress
            });

            // Ensure we have the correct total questions
            const totalQuestions = this.basicInterestData?.items?.length || 27;
            
            // Update progress with controller data
            this.progress = {
                current_index: progress.current_index || 0,
                responses: progress.responses || {},
                completed: false, // Will be set based on validation
                progress_percentage: progress.percentage || 0,
                validResponses: progress.validResponses || 0,
                totalQuestions
            };

            // Update responses
            if (progress.responses) {
                this.responses = Object.fromEntries(
                    Object.entries(progress.responses).map(([key, value]) => [
                        key,
                        value !== null ? parseInt(value) : null
                    ])
                );
            }

            // Calculate valid responses count
            const validResponsesCount = Object.values(this.responses).filter(v => v > 0).length;
            this.progress.validResponses = validResponsesCount;

            // Update completion status based on valid responses
            this.progress.completed = validResponsesCount >= totalQuestions;

            // Update current index to match controller
            this.currentItemIndex = Math.min(progress.current_index || 0, totalQuestions - 1);
            
            this.logDebug('setProgress:after', {
                oldProgress,
                newProgress: { ...this.progress },
                validResponsesCount,
                totalQuestions,
                completed: this.progress.completed
            });
            
            this.setCurrentItem();
        },

        setCurrentItem() {
            if (!this.basicInterestData?.items) return;
            
            const totalItems = this.basicInterestData.items.length;
            const safeIndex = Math.min(this.currentItemIndex, totalItems - 1);
            const item = this.basicInterestData.items[safeIndex];
            
            this.currentItem = item || null;
            this.currentItemIndex = safeIndex;
            
            this.logDebug('setCurrentItem', {
                currentIndex: this.currentItemIndex,
                totalItems,
                hasCurrentItem: !!this.currentItem
            });
        },

        async submitAnswer(form) {
            try {
                this.loading = true;
                this.logDebug('submitAnswer:start', { form });

                await router.post('/basic-interest/store-response', form, {
                    preserveState: true,
                    preserveScroll: true,
                    preserveUrl: true,
                    onSuccess: (page) => {
                        if (page.props.progress) {
                            this.logDebug('submitAnswer:success', {
                                progress: page.props.progress,
                                form
                            });
                            
                            // Store response before updating progress
                            this.responses[form.itemId] = form.answer;
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: (errors) => {
                        console.error('Basic interest submission error:', errors);
                        this.error = errors?.message || 'Failed to submit answer';
                    },
                    onFinish: () => {
                        this.loading = false;
                    }
                });
            } catch (error) {
                console.error('Basic interest submission failed:', error);
                this.error = error.message;
                this.loading = false;
            }
        },

        async goBack() {
            if (this.currentItemIndex <= 0 || this.loading) return;

            try {
                this.loading = true;
                this.logDebug('goBack:start', {
                    currentIndex: this.currentItemIndex,
                    responses: this.responses
                });

                await router.post('/basic-interest/go-back', {}, {
                    preserveState: true,
                    preserveScroll: true,
                    preserveUrl: true,
                    onSuccess: (page) => {
                        if (page.props.progress) {
                            this.logDebug('goBack:success', {
                                progress: page.props.progress
                            });
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: (error) => {
                        console.error('Basic interest go back error:', error);
                        this.error = error.message || 'Failed to go back';
                    },
                    onFinish: () => {
                        this.loading = false;
                    }
                });
            } catch (error) {
                console.error('Basic interest go back failed:', error);
                this.error = error.message;
                this.loading = false;
            }
        }
    },

    getters: {
        isComplete: (state) => {
            const validResponses = Object.values(state.responses).filter(v => v > 0).length;
            const totalQuestions = state.basicInterestData?.items?.length || state.progress.totalQuestions;
            return validResponses >= totalQuestions;
        },
        progressPercentage: (state) => state.progress.progress_percentage,
        validResponsesCount: (state) => Object.values(state.responses).filter(v => v > 0).length,
        totalQuestionsCount: (state) => state.basicInterestData?.items?.length || state.progress.totalQuestions,
        canProceed: (state) => {
            const validResponses = Object.values(state.responses).filter(v => v > 0).length;
            const totalQuestions = state.basicInterestData?.items?.length || state.progress.totalQuestions;
            const hasAllResponses = validResponses >= totalQuestions;
            
            if (state.debug) {
                console.log('[BasicInterestStore][canProceed]', {
                    validResponses,
                    totalQuestions,
                    hasAllResponses,
                    responses: state.responses
                });
            }
            
            return hasAllResponses;
        }
    }
}); 