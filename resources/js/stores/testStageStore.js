import { defineStore } from 'pinia';
import { useHollandCodeStore } from './hollandCodeStore';
import { useBasicInterestStore } from './basicInterestStore';
import { useDegreeStore } from './degreeStore';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export const useTestStageStore = defineStore('testStage', {
    state: () => ({
        currentStage: null,
        stages: ['holland_codes', 'basic_interests', 'degree'],
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
                nextStage: null,
                nextStageName: null,
                route: 'degree-assessment.index'
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

        isStageComplete(stage) {
            const progress = this.getStageProgress(stage);
            return progress?.completed || false;
        },

        canTransitionTo(stage) {
            const progressStore = useTestProgressStore();
            const currentStageInfo = this.stageInfo[this.currentStage];
            
            // Check if this is the next stage in sequence
            if (currentStageInfo.nextStage !== stage) {
                return false;
            }
            
            // Check if current stage is complete and can transition
            const currentProgress = progressStore.stages[this.currentStage];
            if (!currentProgress?.completed || !currentProgress?.canTransition) {
                return false;
            }
            
            return true;
        },

        validateStageTransition(fromStage, toStage) {
            // Check if stages exist
            if (!this.stageInfo[fromStage] || !this.stageInfo[toStage]) {
                throw new Error(`Invalid stage transition: Unknown stage (${fromStage} -> ${toStage})`);
            }

            // Check if it's the next stage in sequence
            if (!this.canTransitionTo(toStage)) {
                const progressStore = useTestProgressStore();
                const currentProgress = progressStore.stages[fromStage];
                
                let errorMessage = 'Invalid stage transition: Not ready for next stage';
                if (!currentProgress?.completed) {
                    errorMessage = 'Cannot transition: Current stage not complete';
                } else if (!currentProgress?.canTransition) {
                    errorMessage = 'Cannot transition: Stage transition not allowed';
                }
                
                throw new Error(errorMessage);
            }

            return true;
        },

        async fetchCurrentStage() {
            try {
                this.loading = true;
                const response = await axios.get('/test-stage/current');
                
                this.currentStage = response.data.currentStage;
                this.stageData = response.data.stageData;
                
                // Fetch data based on current stage
                if (this.currentStage === 'basic_interests') {
                    const basicInterestStore = useBasicInterestStore();
                    await basicInterestStore.fetchData();
                } else if (this.currentStage === 'degree') {
                    const degreeStore = useDegreeStore();
                    await degreeStore.fetchData();
                }
            } catch (error) {
                this.error = 'Failed to fetch current stage';
                console.error('Error fetching current stage:', error);
            } finally {
                this.loading = false;
            }
        },

        async changeStage(newStage) {
            try {
                this.loading = true;
                this.error = null;

                const response = await axios.post('/test-stage/change', {
                    fromStage: this.currentStage,
                    toStage: newStage
                });

                if (response.data.error) {
                    this.error = response.data.error;
                    return false;
                }

                this.currentStage = response.data.currentStage;
                
                // Fetch data for the new stage
                await this.fetchStageData(newStage);

                return true;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to change stage';
                console.error('Error changing stage:', error);
                return false;
            } finally {
                this.loading = false;
            }
        },

        async fetchStageData(stage) {
            const store = this.getStageStore();
            if (store?.fetchData) {
                await store.fetchData();
            }
        },

        getStageStore() {
            switch (this.currentStage) {
                case 'basic_interests':
                    return useBasicInterestStore();
                case 'holland_codes':
                    return useHollandCodeStore();
                case 'degree':
                    return useDegreeStore();
                default:
                    return null;
            }
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
        stageProgress: (state) => (stage) => {
            return state.stageProgress[stage]?.percentage || 0;
        },
        isStageComplete: (state) => (stage) => {
            return state.stageProgress[stage]?.completed || false;
        },
        canProceedToNextStage: (state) => {
            const currentStage = state.currentStage;
            return state.stageProgress[currentStage]?.completed || false;
        }
    }
}); 