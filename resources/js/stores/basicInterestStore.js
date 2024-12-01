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
            totalQuestions: 27,
            jobMatching: null
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
            }
            
            if (data.progress) {
                this.setProgress(data.progress);
            }

            this.setCurrentItem();
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
                completed: progress.completed || false,
                progress_percentage: progress.progress_percentage || 0,
                validResponses: Object.values(progress.responses || {}).filter(v => v > 0).length,
                totalQuestions,
                jobMatching: progress.jobMatching || null
            };

            // Check if test should be completed
            if (this.progress.progress_percentage >= 100 || this.progress.validResponses >= totalQuestions) {
                this.progress.completed = true;
                this.currentItem = null;
            }

            this.logDebug('setProgress:after', {
                oldProgress,
                newProgress: { ...this.progress },
                validResponses: this.progress.validResponses,
                totalQuestions,
                completed: this.progress.completed
            });
            
            if (!this.progress.completed) {
                this.setCurrentItem();
            }
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

        submitAnswer(form) {
            this.logDebug('submitAnswer', { form });
            
            if (this.loading) return;
            this.loading = true;
            this.error = null;

            const response = form.answer;
            const itemId = form.itemId;

            try {
                // Store response locally first
                this.responses[itemId] = response;
                
                // Make API request
                router.post('/basic-interests', {
                    itemId,
                    answer: response,
                    type: 'answered',
                    category: 'basic_interests',
                    testStage: 'basic_interests'
                }, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.logDebug('submitAnswer:success', { page });
                        
                        if (page.props.progress) {
                            this.setProgress(page.props.progress);
                            
                            // Check completion after progress update
                            if (page.props.progress.progress_percentage >= 100) {
                                this.progress.completed = true;
                                this.currentItem = null;
                            }
                        }

                        if (page.props.error) {
                            this.error = page.props.error;
                        }
                    },
                    onError: (errors) => {
                        this.logDebug('submitAnswer:error', { errors });
                        this.error = 'Failed to submit answer';
                        // Rollback local changes on error
                        delete this.responses[itemId];
                    }
                });
            } catch (error) {
                this.logDebug('submitAnswer:error', { error });
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        async goBack() {
            if (this.currentItemIndex <= 0) return;
            
            try {
                this.loading = true;
                this.logDebug('goBack:start', {
                    currentIndex: this.currentItemIndex,
                    responses: this.responses
                });

                await router.post('/basic-interests/go-back', {}, {
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
        },

        async fetchData() {
            try {
                this.loading = true;
                this.error = null;
                this.logDebug('fetchData', { message: 'Starting fetch' });

                const response = await router.get(route('basic-interests.index'), {}, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.logDebug('fetchData', { 
                            message: 'Fetch successful',
                            data: page.props 
                        });
                        
                        this.initialize(page.props);
                    },
                    onError: (errors) => {
                        this.error = 'Failed to fetch basic interest data';
                        this.logDebug('fetchData', { 
                            message: 'Fetch failed',
                            errors 
                        });
                    }
                });

                return response;
            } catch (error) {
                this.error = 'Failed to fetch basic interest data';
                this.logDebug('fetchData', { 
                    message: 'Fetch error',
                    error 
                });
                throw error;
            } finally {
                this.loading = false;
            }
        },
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
        },
        hasJobMatching: (state) => {
            return state.progress.jobMatching !== null;
        },
        
        getJobMatching: (state) => {
            return state.progress.jobMatching;
        }
    }
}); 