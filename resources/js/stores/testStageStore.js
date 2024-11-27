import { defineStore } from 'pinia';
import { useHollandCodeStore } from './hollandCodeStore';
import { useBasicInterestStore } from './basicInterestStore';
import { router } from '@inertiajs/vue3';

export const useTestStageStore = defineStore('testStage', {
    state: () => ({
        currentStage: 'holland_codes',
        stages: ['holland_codes', 'basic_interest', 'degree_match', 'results'],
        loading: false,
        error: null
    }),

    actions: {
        async changeStage(newStage) {
            console.log('Starting stage change:', { from: this.currentStage, to: newStage });
            this.loading = true;
            this.error = null;

            try {
                await router.post(route('test.change-stage'), {
                    fromStage: this.currentStage,
                    toStage: newStage
                }, {
                    preserveState: true,
                    preserveScroll: true,
                    preserveUrl: true,
                    onSuccess: (page) => {
                        console.log('Stage change response:', page.props);
                        
                        if (page.props.error) {
                            console.error('Stage change error from server:', page.props.error);
                            this.error = page.props.error;
                            return;
                        }

                        if (page.props.testStage !== newStage) {
                            console.error('Stage mismatch:', {
                                expected: newStage,
                                received: page.props.testStage
                            });
                            return;
                        }

                        console.log('Initializing new stage:', newStage);
                        this.currentStage = newStage;
                        this.initializeStage(newStage, page.props);
                    },
                    onError: (errors) => {
                        console.error('Stage change request error:', errors);
                        this.error = errors?.message || 'Failed to change stage';
                    },
                    onFinish: () => {
                        console.log('Stage change request finished');
                        this.loading = false;
                    }
                });
            } catch (error) {
                console.error('Stage change error:', error);
                this.error = error.message;
                this.loading = false;
            }
        },

        initializeStage(stage, data) {
            console.log('Initializing stage:', { stage, data });
            
            switch(stage) {
                case 'basic_interest':
                    const basicInterestStore = useBasicInterestStore();
                    basicInterestStore.initialize(data);
                    console.log('Basic Interest store initialized');
                    break;
                default:
                    console.warn('No initialization handler for stage:', stage);
            }
        }
    },

    getters: {
        hasError: (state) => !!state.error,
        currentStageName: (state) => {
            return {
                'holland_codes': 'Holland Codes',
                'basic_interest': 'Basic Interest',
                'degree_match': 'Degree Match',
                'results': 'Results'
            }[state.currentStage] || state.currentStage;
        }
    }
}); 