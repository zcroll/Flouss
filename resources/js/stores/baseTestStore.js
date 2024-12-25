import { defineStore } from 'pinia';
import { router, useForm } from '@inertiajs/vue3';

export const createBaseTestStore = (storeName, options = {}) => {
    return defineStore(storeName, {
        state: () => ({
            data: {
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
                totalQuestions: 0
            },
            loading: false,
            error: null,
            debug: false,
            initialized: false,
            form: useForm({
                type: 'answered',
                answer: null,
                itemId: null,
                category: options.category,
                testStage: options.testStage
            }),
            ...options.state
        }),

        actions: {
            initialize(data) {
                const storeData = data[options.dataKey];
                
                if (storeData) {
                    this.data = {
                        id: storeData.id,
                        title: storeData.title,
                        lead_in_text: storeData.lead_in_text,
                        items: storeData.items || [],
                        option_sets: storeData.option_sets || []
                    };
                }
                
                if (data.progress) {
                    this.setProgress(data.progress);
                }

                this.setCurrentItem();
                this.initialized = true;
            },

            setProgress(progress) {
                if (!progress) return;

                this.progress = {
                    ...this.progress,
                    ...progress,
                    totalQuestions: this.data?.items?.length || this.progress.totalQuestions
                };

                if (progress.responses) {
                    this.responses = Object.fromEntries(
                        Object.entries(progress.responses).map(([key, value]) => [
                            key,
                            value !== null ? parseInt(value) : null
                        ])
                    );
                }

                this.currentItemIndex = Math.min(
                    progress.current_index || 0,
                    this.progress.totalQuestions - 1
                );
                
                this.setCurrentItem();
            },

            setCurrentItem() {
                if (!this.data?.items) return;
                
                const totalItems = this.data.items.length;
                const safeIndex = Math.min(this.currentItemIndex, totalItems - 1);
                
                this.currentItem = this.data.items[safeIndex] || null;
                this.currentItemIndex = safeIndex;
            },

            async submitAnswer(formData) {
                if (this.form.processing) return;

                this.error = null;

                this.form.clearErrors();
                this.form.type = formData.type || 'answered';
                this.form.answer = formData.answer;
                this.form.itemId = formData.itemId;

                this.form.post(options.submitRoute, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.progress) {
                            this.responses[formData.itemId] = formData.answer;
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: () => {
                        this.error = 'Failed to submit answer';
                    }
                });
            },

            async goBack() {
                if (this.currentItemIndex <= 0 || this.form.processing) return;

                this.form.post(options.goBackRoute, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.progress) {
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: () => {
                        this.error = 'Failed to go back';
                    }
                });
            },

            async fetchData() {
                if (this.form.processing) return;
                
                this.error = null;

                try {
                    await router.get(options.fetchRoute, {}, {
                        preserveState: true,
                        preserveScroll: true,
                        onSuccess: (page) => {
                            this.initialize(page.props);
                        },
                        onError: () => {
                            this.error = 'Failed to fetch data';
                        }
                    });
                } catch (error) {
                    this.error = error.message;
                    throw error;
                }
            },

            ...options.actions
        },

        getters: {
            isComplete: (state) => state.progress.completed,
            progressPercentage: (state) => state.progress.progress_percentage,
            validResponsesCount: (state) => Object.values(state.responses).filter(v => v > 0).length,
            totalQuestionsCount: (state) => state.data?.items?.length || state.progress.totalQuestions,
            canGoBack: (state) => state.currentItemIndex > 0 && !state.form.processing,
            currentResponse: (state) => state.responses[state.currentItem?.id],
            ...options.getters
        }
    });
};
