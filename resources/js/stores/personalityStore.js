import { createBaseTestStore } from './baseTestStore';
import { useTestProgressStore } from './testProgressStore';
import { router } from '@inertiajs/vue3';

export const usePersonalityStore = createBaseTestStore('personality', {
    dataKey: 'personality',
    submitRoute: route('personality.store'),
    goBackRoute: route('personality.go-back'),
    fetchRoute: route('personality.index'),
    category: 'personality',
    testStage: 'personality',
    
    state: {
        progress: {
            personalityReport: null,
            currentSection: 'skills_preferences',
            sections: {
                skills_preferences: {
                    completed: false,
                    responses: {}
                },
                must_haves: {
                    completed: false,
                    responses: {}
                },
                cant_stands: {
                    completed: false,
                    responses: {}
                }
            }
        }
    },
    
    getters: {
        personalityReport: (state) => state.progress?.personalityReport || null,
        hasPersonalityReport: (state) => state.progress.personalityReport !== null,
        currentSection: (state) => state.progress.currentSection,
        sectionResponses: (state) => (section) => state.progress.sections[section]?.responses || {},
        currentSectionItems: (state) => state.data?.items || [],
        currentItem: (state) => {
            const items = state.data?.items || [];
            return items[state.currentItemIndex] || null;
        },
        isSectionComplete: (state) => (section) => {
            const sectionData = state.progress.sections[section];
            if (!sectionData) return false;
            
            const responses = Object.values(sectionData.responses).filter(v => v !== null && v > 0);
            const items = state.data?.items || [];
            return items.length > 0 && responses.length >= items.length;
        },
        isComplete: (state) => {
            return Object.values(state.progress.sections).every(section => section.completed);
        },
        canProceed: (state) => {
            const currentSection = state.progress.currentSection;
            const sectionData = state.progress.sections[currentSection];
            if (!sectionData) return false;
            
            const responses = Object.values(sectionData.responses).filter(v => v !== null && v > 0);
            const items = state.data?.items || [];
            return items.length > 0 && responses.length >= items.length;
        },
        progressPercentage: (state) => {
            const totalSections = Object.keys(state.progress.sections).length;
            const completedSections = Object.values(state.progress.sections)
                .filter(section => section.completed).length;
            
            return Math.min(Math.round((completedSections / totalSections) * 100), 100);
        },
        validResponsesCount: (state) => {
            let totalResponses = 0;
            Object.values(state.progress.sections).forEach(section => {
                totalResponses += Object.values(section.responses)
                    .filter(v => v !== null && v > 0).length;
            });
            return totalResponses;
        }
    },

    actions: {
        initialize(data) {
            console.log('Initializing personality store with data:', data);
            this.data = data;
            this.currentItemIndex = 0;
            this.error = null;
            
            // Initialize progress if needed
            if (!this.progress) {
                this.progress = {
                    personalityReport: null,
                    currentSection: 'skills_preferences',
                    sections: {
                        skills_preferences: {
                            completed: false,
                            responses: {}
                        },
                        must_haves: {
                            completed: false,
                            responses: {}
                        },
                        cant_stands: {
                            completed: false,
                            responses: {}
                        }
                    }
                };
            }
            
            // Ensure current section is valid
            if (!this.progress.currentSection || !this.progress.sections[this.progress.currentSection]) {
                this.progress.currentSection = 'skills_preferences';
            }
            
            // Update current section from data if provided
            if (data.currentSection && this.progress.sections[data.currentSection]) {
                this.progress.currentSection = data.currentSection;
            }
            
            this.setCurrentItem();
            this.initialized = true;
            console.log('Personality store initialized:', {
                currentSection: this.progress.currentSection,
                currentItemIndex: this.currentItemIndex
            });
        },

        setCurrentItem() {
            const items = this.currentSectionItems;
            if (!items) return;
            
            const safeIndex = Math.min(this.currentItemIndex, items.length - 1);
            this.currentItemIndex = Math.max(0, safeIndex);
        },

        setProgress(progress) {
            if (!progress) return;
            
            // Ensure sections exist
            if (!this.progress.sections) {
                this.progress.sections = {
                    skills_preferences: { completed: false, responses: {} },
                    must_haves: { completed: false, responses: {} },
                    cant_stands: { completed: false, responses: {} }
                };
            }
            
            // Update section completion status
            if (progress.sections) {
                Object.keys(progress.sections).forEach(section => {
                    if (this.progress.sections[section]) {
                        this.progress.sections[section].completed = progress.sections[section].completed || false;
                        this.progress.sections[section].responses = {
                            ...this.progress.sections[section].responses,
                            ...progress.sections[section].responses
                        };
                    }
                });
            }
            
            // Update current section if changed and valid
            if (progress.currentSection && this.progress.sections[progress.currentSection]) {
                this.progress.currentSection = progress.currentSection;
                this.currentItemIndex = 0;
            }
            
            // Update completion status
            this.progress.completed = progress.completed || false;
            
            // Update test progress store
            const testProgressStore = useTestProgressStore();
            testProgressStore.updateStageProgress('personality', {
                currentIndex: this.currentItemIndex,
                validResponses: this.validResponsesCount,
                percentage: this.progressPercentage,
                completed: this.progress.completed,
                personalityReport: this.progress.personalityReport
            });

            this.setCurrentItem();
            
            console.log('Progress updated:', {
                currentSection: this.progress.currentSection,
                completed: this.progress.completed,
                percentage: this.progressPercentage
            });
        },

        async fetchData() {
            try {
                this.loading = true;
                this.error = null;
                console.log('Fetching personality data...');

                router.get(route('personality.index'), {}, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('Personality data fetched:', page.props);
                        if (page.props.error) {
                            throw new Error(page.props.error);
                        }

                        if (!page.props.personality) {
                            throw new Error('No personality data received');
                        }

                        this.initialize(page.props.personality);
                        if (page.props.progress) {
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: (errors) => {
                        console.error('Error fetching personality data:', errors);
                        this.error = errors.message || 'Failed to fetch personality data';
                    }
                });

            } catch (error) {
                console.error('Error in fetchData:', error);
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        async submitResponse(response) {
            try {
                console.log('Submitting response:', response);
                await router.post(route('personality.store'), response, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('Response submitted successfully:', page.props);
                        if (page.props.progress) {
                            this.setProgress(page.props.progress);
                        }
                    },
                    onError: (errors) => {
                        console.error('Error submitting response:', errors);
                        this.error = errors.message || 'Failed to submit response';
                    }
                });
            } catch (error) {
                console.error('Error in submitResponse:', error);
                this.error = error.message;
            }
        }
    }
}); 