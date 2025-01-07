import { createBaseTestStore } from './baseTestStore';

export const useDegreeStore = createBaseTestStore('degree', {
    dataKey: 'degree',
    submitRoute: '/degree-assessment',
    goBackRoute: '/degree-assessment/go-back', 
    fetchRoute: route('degree-assessment.index'),
    category: 'degree',
    testStage: 'degree',
    sessionKey: 'degree_progress',
    
    state: {
        progress: {
            degreeMatching: null,
            progress_percentage: 0,
            completed: false,
            current_index: 0,
            validResponses: 0,
            responses: {},
            totalQuestions: 0
        }
    },
    
    getters: {
        degreeMatching: (state) => state.progress?.degreeMatching || null,
        hasDegreeMatching: (state) => state.progress.degreeMatching !== null,
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
    }
}); 