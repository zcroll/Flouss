import { createBaseTestStore } from './baseTestStore';
import { useTestStageStore } from './testStageStore';
import { useTestProgressStore } from './testProgressStore';
import { storeToRefs } from 'pinia';

export const usePersonalityStore = createBaseTestStore('personality', {
    dataKey: 'personality',
    submitRoute: '/personality',
    goBackRoute: '/personality/go-back', 
    fetchRoute: route('personality.index'),
    category: 'personality',
    testStage: 'personality',
    
    state: {
        progress: {
            personalityReport: null,
            progress_percentage: 0,
            completed: false,
            current_index: 0,
            validResponses: 0,
            responses: {},
            totalQuestions: 20,
            cant_stands_completed: false,
            cant_stands_responses: {},
            skills_preferences_responses: {}
        }
    },
    
    actions: {
        fetchPersonality() {
            return this.fetchData();
        },

        // Override setProgress from baseTestStore
        setProgress(progress) {
            if (!progress) return;

            // Calculate the actual progress percentage based on valid responses for current set
            const currentSetResponses = progress.cant_stands_completed 
                ? progress.skills_preferences_responses 
                : progress.cant_stands_responses;
            
            const validResponsesCount = Object.values(currentSetResponses || {}).filter(v => v > 0).length;
            const totalQuestions = this.progress.totalQuestions || 20;
            const percentage = Math.round((validResponsesCount / totalQuestions) * 100);

            // Check if we need to transition to the next set
            const shouldTransition = !progress.completed && 
                progress.cant_stands_completed && 
                !this.progress.cant_stands_completed;

            // Update our local progress state
            this.$patch((state) => {
                state.progress = {
                    ...state.progress,
                    ...progress,
                    progress_percentage: percentage,
                    validResponses: validResponsesCount,
                    responses: currentSetResponses || {},
                    current_index: progress.current_index || 0,
                    completed: progress.completed || false,
                    cant_stands_completed: progress.cant_stands_completed || false,
                    cant_stands_responses: progress.cant_stands_responses || {},
                    skills_preferences_responses: progress.skills_preferences_responses || {},
                    totalQuestions
                };

                // Reset current index if transitioning
                if (shouldTransition) {
                    state.progress.current_index = 0;
                    state.progress.responses = {};
                }

                // Update current item index
                state.currentItemIndex = Math.min(
                    state.progress.current_index || 0,
                    totalQuestions - 1
                );
            });

            // Sync with both stores
            const testStageStore = useTestStageStore();
            const testProgressStore = useTestProgressStore();

            // Calculate overall progress
            const cantStandsResponses = Object.values(progress.cant_stands_responses || {}).filter(v => v > 0).length;
            const skillsResponses = Object.values(progress.skills_preferences_responses || {}).filter(v => v > 0).length;
            const totalProgress = Math.round(((cantStandsResponses + skillsResponses) / (totalQuestions * 2)) * 100);

            // Update testStageStore
            testStageStore.$patch((state) => {
                if (!state.stageProgress.personality) {
                    state.stageProgress.personality = {};
                }
                state.stageProgress.personality.percentage = totalProgress;
                state.stageProgress.personality.completed = progress.completed || false;
            });

            // Update testProgressStore
            testProgressStore.updateStageProgress('personality', {
                currentIndex: progress.current_index || 0,
                validResponses: validResponsesCount,
                percentage: totalProgress,
                completed: progress.completed || false,
                personalityReport: progress.personalityReport || null,
                responses: currentSetResponses || {},
                totalQuestions,
                cant_stands_completed: progress.cant_stands_completed || false,
                cant_stands_responses: progress.cant_stands_responses || {},
                skills_preferences_responses: progress.skills_preferences_responses || {}
            });

            // Mark complete if needed
            if (progress.completed) {
                testStageStore.markStageComplete('personality');
                testProgressStore.markStageComplete('personality');
            }

            // If we need to transition to the next set, fetch new data
            if (shouldTransition) {
                this.fetchData();
            } else {
                // Set current item after all updates
                this.setCurrentItem();
            }
        },

        // Override submitAnswer from baseTestStore
        async submitAnswer(formData) {
            if (this.form.processing) return;

            this.error = null;
            this.form.clearErrors();

            // Update form data
            this.form.type = formData.type || 'answered';
            this.form.answer = formData.answer;
            this.form.itemId = formData.itemId;
            this.form.category = this.category;
            this.form.testStage = this.testStage;

            try {
                await this.form.post('/personality', {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.progress) {
                            // Store the response in the appropriate set
                            if (!this.progress.cant_stands_completed) {
                                this.progress.cant_stands_responses[formData.itemId] = formData.answer;
                            } else {
                                this.progress.skills_preferences_responses[formData.itemId] = formData.answer;
                            }
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: () => {
                        this.error = 'Failed to submit answer';
                    }
                });
            } catch (error) {
                console.error('Submit error:', error);
                this.error = 'Failed to submit answer';
            }
        },

        // Keep the existing updateProgress method
        updateProgress(progress) {
            this.setProgress(progress);
        }
    },
    
    getters: {
        personalityReport: (state) => state.progress?.personalityReport || null,
        isTestComplete: (state) => state.progress.completed,
        currentProgress: (state) => state.progress.progress_percentage || 0,
        validResponses: (state) => state.progress.validResponses || 0,
        totalQuestions: (state) => state.progress.totalQuestions || 20,
        cantStandsCompleted: (state) => state.progress.cant_stands_completed || false,
        cantStandsResponses: (state) => state.progress.cant_stands_responses || {},
        skillsPreferencesResponses: (state) => state.progress.skills_preferences_responses || {},
        currentSetResponses: (state) => state.progress.cant_stands_completed 
            ? state.progress.skills_preferences_responses 
            : state.progress.cant_stands_responses,
        overallProgress: (state) => {
            const cantStandsResponses = Object.values(state.progress.cant_stands_responses || {}).filter(v => v > 0).length;
            const skillsResponses = Object.values(state.progress.skills_preferences_responses || {}).filter(v => v > 0).length;
            const totalQuestions = state.progress.totalQuestions || 20;
            return Math.round(((cantStandsResponses + skillsResponses) / (totalQuestions * 2)) * 100);
        }
    }
});
