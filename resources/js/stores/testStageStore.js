import { defineStore } from 'pinia';
import { useHollandCodeStore } from './hollandCodeStore';
import { useBasicInterestStore } from './basicInterestStore';
import { useDegreeStore } from './degreeStore';
import { usePersonalityStore } from './personalityStore';
import { useTestProgressStore } from './testProgressStore';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed } from 'vue';

export const useTestStageStore = defineStore('testStage', {
    state: () => ({
        currentStage: null,
        stages: ['holland_codes', 'basic_interests', 'degree', 'personality'],
        stageProgress: {
            holland_codes: {
                percentage: 0,
                completed: false
            },
            basic_interests: {
                percentage: 0,
                completed: false
            },
            degree: {
                percentage: 0,
                completed: false
            },
            personality: {
                percentage: 0,
                completed: false
            }
        },
        stageInfo: {
            'holland_codes': {
                name: 'Holland Codes',
                description: 'Discover your career interests and personality type',
                nextStage: 'basic_interests',
                nextStageName: 'Basic Interest Assessment',
                route: 'holland-codes.index'
            },
            'basic_interests': {
                name: 'Basic Interest',
                description: 'Explore your specific areas of interest',
                nextStage: 'degree',
                nextStageName: 'Degree Assessment',
                route: 'basic-interests.index'
            },
            'degree': {
                name: 'Degree Assessment',
                description: 'Find your ideal degree path',
                nextStage: 'personality',
                nextStageName: 'Personality Assessment',
                route: 'degree-assessment.index'
            },
            'personality': {
                name: 'Personality Assessment',
                description: 'Understand your personality traits and characteristics',
                nextStage: null,
                nextStageName: null,
                route: 'personality.index'
            }
        },
        loading: false,
        error: null,
        initialized: false
    }),

    actions: {
        updateStageProgress(stage, percentage) {
            if (this.stageProgress[stage]) {
                this.stageProgress[stage].percentage = percentage;
                this.stageProgress[stage].completed = percentage >= 100;
            }
        },

        markStageComplete(stage) {
            if (this.stageProgress[stage]) {
                this.stageProgress[stage].percentage = 100;
                this.stageProgress[stage].completed = true;
            }
        },

        async initialize() {
            if (this.initialized) return;

            try {
                await this.fetchCurrentStage();
                this.initialized = true;
            } catch (error) {
                console.error('Failed to initialize test stage store:', error);
            }
        },

        getNextStage() {
            const currentStageInfo = this.stageInfo[this.currentStage];
            return currentStageInfo?.nextStage || null;
        },

        getStageProgress(stage) {
            const progressStore = useTestProgressStore();
            const stageInfo = this.stageInfo[stage];
            if (!stageInfo?.storeKey) return null;
            return progressStore.stages[stageInfo.storeKey];
        },

        checkStageComplete(stage) {
            const progress = this.getStageProgress(stage);
            return progress?.completed || false;
        },

        canTransitionTo(stage) {
            const progressStore = useTestProgressStore();
            const sequence = progressStore.getStageSequence();
            const currentIndex = sequence.indexOf(this.currentStage);
            const targetIndex = sequence.indexOf(stage);

            // Can't transition to current stage
            if (stage === this.currentStage) return false;

            // Can't transition to unknown stage
            if (targetIndex === -1) return false;

            // Can only transition to the next stage in sequence
            if (targetIndex !== currentIndex + 1) return false;

            // Check if current stage is complete
            const currentStageProgress = progressStore.stages[this.currentStage];
            if (!currentStageProgress?.completed) return false;

            // Check if all previous stages are complete
            for (let i = 0; i < targetIndex; i++) {
                if (!progressStore.stages[sequence[i]]?.completed) {
                    return false;
                }
            }

            return true;
        },

        validateStageTransition(fromStage, toStage) {
            const progressStore = useTestProgressStore();
            const sequence = progressStore.getStageSequence();
            const fromIndex = sequence.indexOf(fromStage);
            const toIndex = sequence.indexOf(toStage);

            // Check if stages exist
            if (fromIndex === -1 || toIndex === -1) {
                throw new Error(`Invalid stage transition: Unknown stage (${fromStage} -> ${toStage})`);
            }

            // Check if it's a valid transition
            if (!this.canTransitionTo(toStage)) {
                const currentProgress = progressStore.stages[fromStage];

                if (!currentProgress?.completed) {
                    throw new Error('Cannot transition: Current stage not complete');
                }

                if (toIndex !== fromIndex + 1) {
                    throw new Error('Invalid stage transition: Can only move to next stage in sequence');
                }

                // Check if all previous stages are complete
                for (let i = 0; i < toIndex; i++) {
                    if (!progressStore.stages[sequence[i]]?.completed) {
                        throw new Error(`Cannot transition: Stage ${sequence[i]} not complete`);
                    }
                }

                throw new Error('Invalid stage transition: Not ready for next stage');
            }

            return true;
        },

        async fetchCurrentStage() {
            if (this.loading) return;
            
            try {
                this.loading = true;
                const response = await axios.get('/test-stage/current');
                
                if (this.currentStage !== response.data.currentStage) {
                    this.currentStage = response.data.currentStage;
                    
                    // Update progress efficiently
                    const progressStore = useTestProgressStore();
                    Object.entries(response.data.progress).forEach(([stage, stageProgress]) => {
                        progressStore.updateStageProgress(stage, {
                            currentIndex: stageProgress.current_index,
                            validResponses: stageProgress.responses?.length ?? 0,
                            percentage: stageProgress.progress_percentage,
                            completed: stageProgress.completed
                        });
                    });

                    // Only fetch data if needed
                    if (['basic_interests', 'degree'].includes(this.currentStage)) {
                        await this.fetchStageData(this.currentStage);
                    }
                }
            } catch (error) {
                this.error = 'Failed to fetch current stage';
                console.error('Error fetching current stage:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async changeStage(newStage) {
            try {
                this.loading = true;
                this.error = null;

                await router.post('/test-stage/change', {
                    fromStage: this.currentStage,
                    toStage: newStage
                }, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.currentStage = newStage;
                        
                        // Data will be available in page.props
                        const store = this.getStageStore();
                        if (store) {
                            store.initialize(page.props);
                        }
                    },
                    onError: (errors) => {
                        this.error = errors.message || 'Failed to change stage';
                    }
                });

                return true;
            } catch (error) {
                this.error = error.message || 'Failed to change stage';
                console.error('Error changing stage:', error);
                return false;
            } finally {
                this.loading = false;
            }
        },

        async fetchStageData(stage) {
            const store = this.getStageStore;
            if (store?.fetchData) {
                await store.fetchData();
            }
        },

        getStageStore() {
            const stores = {
                basic_interests: useBasicInterestStore,
                holland_codes: useHollandCodeStore,
                degree: useDegreeStore,
                personality: usePersonalityStore
            };
            
            return stores[this.currentStage]?.() ?? null;
        },

        clearError() {
            this.error = null;
        }
    },

    getters: {
        hasError: (state) => !!state.error,
        currentStageName: (state) => state.stageInfo[state.currentStage]?.name || '',
        nextStageName: (state) => state.stageInfo[state.currentStage]?.nextStageName || '',
        currentStageDescription: (state) => state.stageInfo[state.currentStage]?.description || '',
        hasNextStage: (state) => !!state.stageInfo[state.currentStage]?.nextStage,
        nextStage: (state) => state.stageInfo[state.currentStage]?.nextStage || null,
        stageProgress: (state) => (stage) => state.stageProgress[stage]?.percentage ?? 0,
        isStageComplete: (state) => (stage) => state.stageProgress[stage]?.completed ?? false,
        canProceedToNextStage: (state) => state.stageProgress[state.currentStage]?.completed ?? false,
        currentStageInfo: (state) => state.stageInfo[state.currentStage] ?? {}
    }
});
