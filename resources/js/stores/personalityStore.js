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
            totalQuestions: 37,
            cant_stands_completed: false,
            cant_stands_responses: {},
            must_haves_completed: false,
            must_haves_responses: {},
            skills_preferences_completed: false,
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

            // Calculate total progress based on all three sets
            const cantStandsResponses = Object.values(progress.cant_stands_responses || {})
                .filter(v => v > 0).length;
            const mustHavesResponses = Object.values(progress.must_haves_responses || {})
                .filter(v => v > 0).length;
            const skillsResponses = Object.values(progress.skills_preferences_responses || {})
                .filter(v => v > 0).length;
            
            const totalResponses = cantStandsResponses + mustHavesResponses + skillsResponses;
            const percentage = Math.round((totalResponses / 37) * 100); // Use total of 37

            // Check if we need to transition to the next set
            const shouldTransition = !progress.completed && (
                (progress.cant_stands_completed && !this.progress.cant_stands_completed) ||
                (progress.must_haves_completed && !this.progress.must_haves_completed)
            );

            // Update our local progress state
            this.$patch((state) => {
                state.progress = {
                    ...state.progress,
                    ...progress,
                    progress_percentage: percentage,
                    validResponses: totalResponses,
                    responses: progress.cant_stands_completed 
                        ? (progress.must_haves_completed 
                            ? progress.skills_preferences_responses 
                            : progress.must_haves_responses)
                        : progress.cant_stands_responses,
                    current_index: progress.current_index || 0,
                    completed: progress.completed || false,
                    cant_stands_completed: progress.cant_stands_completed || false,
                    cant_stands_responses: progress.cant_stands_responses || {},
                    must_haves_completed: progress.must_haves_completed || false,
                    must_haves_responses: progress.must_haves_responses || {},
                    skills_preferences_completed: progress.skills_preferences_completed || false,
                    skills_preferences_responses: progress.skills_preferences_responses || {},
                    totalQuestions: 37
                };

                // Reset current index if transitioning
                if (shouldTransition) {
                    state.progress.current_index = 0;
                    state.progress.responses = {};
                }

                // Update current item index
                state.currentItemIndex = Math.min(
                    state.progress.current_index || 0,
                    state.progress.totalQuestions - 1
                );
            });

            // Sync with both stores
            const testStageStore = useTestStageStore();
            const testProgressStore = useTestProgressStore();

            // Update testStageStore
            testStageStore.$patch((state) => {
                if (!state.stageProgress.personality) {
                    state.stageProgress.personality = {};
                }
                state.stageProgress.personality.percentage = percentage;
                state.stageProgress.personality.completed = progress.completed || false;
            });

            // Update testProgressStore
            testProgressStore.updateStageProgress('personality', {
                currentIndex: progress.current_index || 0,
                validResponses: totalResponses,
                percentage: percentage,
                completed: progress.completed || false,
                personalityReport: progress.personalityReport || null,
                responses: progress.cant_stands_completed 
                    ? (progress.must_haves_completed 
                        ? progress.skills_preferences_responses 
                        : progress.must_haves_responses)
                    : progress.cant_stands_responses,
                totalQuestions: 37,
                cant_stands_completed: progress.cant_stands_completed || false,
                cant_stands_responses: progress.cant_stands_responses || {},
                must_haves_completed: progress.must_haves_completed || false,
                must_haves_responses: progress.must_haves_responses || {},
                skills_preferences_completed: progress.skills_preferences_completed || false,
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
        totalQuestions: (state) => state.progress.totalQuestions || 37,
        cantStandsCompleted: (state) => state.progress.cant_stands_completed || false,
        cantStandsResponses: (state) => state.progress.cant_stands_responses || {},
        skillsPreferencesResponses: (state) => state.progress.skills_preferences_responses || {},
        currentSetResponses: (state) => state.progress.cant_stands_completed 
            ? state.progress.skills_preferences_responses 
            : state.progress.cant_stands_responses,
        overallProgress: (state) => {
            const cantStandsResponses = Object.values(state.progress.cant_stands_responses || {}).filter(v => v > 0).length;
            const skillsResponses = Object.values(state.progress.skills_preferences_responses || {}).filter(v => v > 0).length;
            const totalQuestions = state.progress.totalQuestions || 37;
            return Math.round(((cantStandsResponses + skillsResponses) / (totalQuestions * 2)) * 100);
        }
    }
});
