import { defineStore } from 'pinia';
import { useHollandCodeStore } from './hollandCodeStore';
import { useBasicInterestStore } from './basicInterestStore';
import { router } from '@inertiajs/vue3';
import { useTestProgressStore } from './testProgressStore';

export const useTestStageStore = defineStore('testStage', {
    state: () => ({
        currentStage: 'holland_codes',
        stages: ['holland_codes', 'basic_interests', 'workplace', 'personality'],
        stageInfo: {
            'holland_codes': {
                name: 'Holland Codes',
                description: 'Discover your career interests and personality type',
                nextStage: 'basic_interests',
                nextStageName: 'Basic Interest Assessment',
                route: 'holland-codes.index',
                storeKey: 'hollandCodes'
            },
            'basic_interests': {
                name: 'Basic Interest',
                description: 'Explore your specific areas of interest',
                nextStage: 'workplace',
                nextStageName: 'Workplace Assessment',
                route: 'holland-codes.index',
                storeKey: 'basicInterest'
            },
            'workplace': {
                name: 'Workplace',
                description: 'Understand your ideal work environment',
                nextStage: 'personality',
                nextStageName: 'Personality Assessment',
                route: 'holland-codes.index',
                storeKey: 'workplace'
            },
            'personality': {
                name: 'Personality',
                description: 'Discover your personality traits',
                nextStage: null,
                nextStageName: null,
                route: 'holland-codes.index',
                storeKey: 'personality'
            }
        },
        loading: false,
        error: null,
        transitionData: null
    }),

    actions: {
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

        async initializeFromSession() {
            try {
                const response = await fetch(route('test.current-stage'));
                const data = await response.json();
                
                if (data.currentStage) {
                    this.currentStage = data.currentStage;
                    
                    // Initialize the appropriate store based on the current stage
                    await this.initializeStage(this.currentStage);
                }
            } catch (error) {
                console.error('Failed to initialize from session:', error);
            }
        },

        async changeStage(newStage) {
            console.log('Starting stage change:', { from: this.currentStage, to: newStage });
            
            try {
                // Validate the stage transition
                this.validateStageTransition(this.currentStage, newStage);
                
                this.loading = true;
                this.error = null;

                // Get the route for the new stage
                const newStageInfo = this.stageInfo[newStage];
                if (!newStageInfo?.route) {
                    throw new Error('Stage route not found');
                }

                // Make the API request to change stage
                await router.post(route('test.change-stage'), {
                    fromStage: this.currentStage,
                    toStage: newStage
                }, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('Stage change response:', page.props);
                        
                        if (page.props.error) {
                            console.error('Stage change error from server:', page.props.error);
                            this.error = page.props.error;
                            return;
                        }

                        // Store transition data temporarily
                        this.transitionData = page.props;

                        // Update the current stage
                        this.currentStage = newStage;

                        // Update progress store
                        const progressStore = useTestProgressStore();
                        progressStore.setCurrentStage(newStage);

                        // Navigate to the new stage's route
                        router.visit(route(newStageInfo.route), {
                            preserveState: true,
                            preserveScroll: true,
                            onSuccess: () => {
                                // Initialize the new stage with the stored transition data
                                this.initializeStage(newStage, this.transitionData);
                                this.transitionData = null;
                            },
                            onError: (errors) => {
                                console.error('Navigation error:', errors);
                                this.error = 'Failed to navigate to new stage';
                                this.currentStage = this.currentStage; // Keep current stage
                                progressStore.setCurrentStage(this.currentStage);
                            }
                        });
                    },
                    onError: (errors) => {
                        console.error('Stage change request error:', errors);
                        this.error = errors?.message || 'Failed to change stage';
                    }
                });
            } catch (error) {
                console.error('Stage change error:', error);
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        initializeStage(stage, data) {
            console.log('Initializing stage:', { stage, data });
            
            const progressStore = useTestProgressStore();
            progressStore.updateStageProgress(stage, data?.progress || {});

            switch(stage) {
                case 'basic_interests':
                    const basicInterestStore = useBasicInterestStore();
                    basicInterestStore.initialize(data);
                    console.log('Basic Interest store initialized');
                    break;
                case 'holland_codes':
                    const hollandCodeStore = useHollandCodeStore();
                    hollandCodeStore.initialize(data);
                    console.log('Holland Codes store initialized');
                    break;
                default:
                    console.log('No specific initialization for stage:', stage);
            }
        },

        clearError() {
            this.error = null;
        }
    },

    getters: {
        hasError: (state) => !!state.error,
        currentStageName: (state) => state.stageInfo[state.currentStage]?.name || state.currentStage,
        nextStageName: (state) => state.stageInfo[state.currentStage]?.nextStageName || null,
        currentStageDescription: (state) => state.stageInfo[state.currentStage]?.description || null,
        isValidTransition: (state) => (fromStage, toStage) => {
            try {
                return state.stageInfo[fromStage]?.nextStage === toStage;
            } catch {
                return false;
            }
        }
    }
}); 