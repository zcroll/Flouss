import { createBaseTestStore } from './baseTestStore';

export const useHollandCodeStore = createBaseTestStore('hollandCode', {
    dataKey: 'hollandCodes',
    submitRoute: '/holland-codes',
    goBackRoute: '/holland-codes/go-back',
    fetchRoute: route('holland-codes.index'),
    category: 'holland_codes',
    testStage: 'holland_codes',
    
    state: {
        progress: {
            archetypeDiscovery: null
        }
    },
    
    actions: {
        fetchHollandCodes() {
            return this.fetchData();
        }
    },
    
    getters: {
        archetypeDiscovery: (state) => state.progress?.archetypeDiscovery || null,
        isTestComplete: (state) => state.progress.completed
    }
}); 