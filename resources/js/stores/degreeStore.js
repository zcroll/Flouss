import { createBaseTestStore } from './baseTestStore';

export const useDegreeStore = createBaseTestStore('degree', {
    dataKey: 'degree',
    submitRoute: '/degree-assessment',
    goBackRoute: '/degree-assessment/go-back', 
    fetchRoute: route('degree-assessment.index'),
    category: 'degree',
    testStage: 'degree',
    
    state: {
        progress: {
            degreeMatching: null
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