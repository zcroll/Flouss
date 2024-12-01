import { defineStore } from 'pinia';
import { useHollandCodeStore } from './hollandCodeStore';
import { useBasicInterestStore } from './basicInterestStore';
import { router } from '@inertiajs/vue3';
import { useTestProgressStore } from './testProgressStore';
import axios from 'axios'; // Import axios

export const useTestStageStore = defineStore('testStage', {
    state: () => ({
        currentStage: null, // Initialize as null instead of default value
        stages: ['holland_codes', 'basic_interests'],
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
                nextStage: null,
                nextStageName: null,
                route: 'basic-interests.index'
            }
        },
        stageData: null,
        loading: false,
        error: null,
        initialized: false
    }),

    actions: {
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
                
                // If we're on basic interests, fetch its data
                if (this.currentStage === 'basic_interests') {
                    const basicInterestStore = useBasicInterestStore();
                    await basicInterestStore.fetchData();
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
                this.stageData = response.data.stageData;

                // If transitioning to basic interests, fetch the data
                if (newStage === 'basic_interests') {
                    const basicInterestStore = useBasicInterestStore();
                    await basicInterestStore.fetchData();
                }

                return true;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to change stage';
                console.error('Error changing stage:', error);
                return false;
            } finally {
                this.loading = false;
            }
        },

        getStageStore() {
            switch (this.currentStage) {
                case 'basic_interests':
                    return useBasicInterestStore();
                case 'holland_codes':
                    return useHollandCodeStore();
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
        nextStage: (state) => state.stageInfo[state.currentStage]?.nextStage || null
    }
}); 