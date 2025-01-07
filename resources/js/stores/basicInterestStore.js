import { createBaseTestStore } from './baseTestStore';

export const useBasicInterestStore = createBaseTestStore('basicInterest', {
    dataKey: 'basicInterest',
    submitRoute: '/basic-interests',
    goBackRoute: '/basic-interests/go-back',
    fetchRoute: route('basic-interests.index'),
    category: 'basic_interests',
    testStage: 'basic_interests',
    sessionKey: 'basic_interest_progress',
    
    state: {
        progress: {
            jobMatching: null,
            progress_percentage: 0,
            completed: false,
            current_index: 0,
            validResponses: 0,
            responses: {},
            totalQuestions: 0
        }
    },
    
    getters: {
        jobMatching: (state) => state.progress?.jobMatching || null,
        hasJobMatching: (state) => state.progress.jobMatching !== null,
        isComplete: (state) => {
            const validResponses = Object.values(state.responses).filter(v => v !== null && v > 0).length;
            const totalQuestions = state.data?.items?.length || state.progress.totalQuestions;
            return validResponses >= totalQuestions;
        },
        canProceed: (state) => {
            const validResponses = Object.values(state.responses).filter(v => v !== null && v > 0).length;
            const totalQuestions = state.data?.items?.length || state.progress.totalQuestions;
            return validResponses >= totalQuestions;
        },
        progressPercentage: (state) => {
            const validResponses = Object.values(state.responses).filter(v => v !== null && v > 0).length;
            const totalQuestions = state.data?.items?.length || state.progress.totalQuestions;
            return Math.min(Math.round((validResponses / totalQuestions) * 100), 100);
        }
    },

    actions: {
        setProgress(progress) {
            if (!progress) return;
            
            const totalQuestions = this.data?.items?.length || this.progress.totalQuestions;
            const validResponses = Object.values(progress.responses || {}).filter(v => v !== null && v > 0).length;
            
            const progressPercentage = Math.min(
                Math.round((validResponses / totalQuestions) * 100),
                100
            );

            this.progress = {
                ...this.progress,
                ...progress,
                totalQuestions,
                validResponses,
                progress_percentage: progressPercentage,
                completed: validResponses >= totalQuestions
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
                totalQuestions - 1
            );

            this.setCurrentItem();
        }
    }
}); 