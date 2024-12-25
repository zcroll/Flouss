<template>
    <section class="Roadmap" data-testid="roadmap">
        <!-- <div v-if="debug" style="padding: 10px; background: #f5f5f5;">
          <pre>{{ {
            currentStage: testStageStore?.currentStage,
            hasData: !!currentTestData.value,
            hasStore: !!currentStore.value,
            isReady: isReady,
            loading: loading.value,
            error: error.value,
            leadInText: currentTestData.value?.lead_in_text
          } }}</pre>
        </div> -->

        <div v-if="error" class="error-container">
            <div class="error-alert">
                {{ error }}
            </div>
        </div>

        <div class="assessment-with-progress">
            <SidebarMainTest :progress="{
        hollandCodes: Number(hollandCodeStore?.progress?.progress_percentage ?? 0),
        basicInterest: {
          currentIndex: basicInterestStore?.progress?.current_index ?? 0,
          validResponses: basicInterestStore?.validResponsesCount ?? 0,
          percentage: basicInterestStore?.progressPercentage ?? 0,
          completed: basicInterestStore?.isComplete ?? false
        },
        degree: {
          currentIndex: degreeStore?.progress?.current_index ?? 0,
          validResponses: degreeStore?.validResponsesCount ?? 0,
          percentage: degreeStore?.progressPercentage ?? 0,
          completed: degreeStore?.isComplete ?? false,
          degreeMatching: degreeStore?.progress?.degreeMatching
        },
        completed: isComplete && canProceed
      }" :test-stage="testStageStore?.currentStage"/>

            <div class="assessment Roadmap__inner">
                <template v-if="showTutorial">
                    <MainTestTutorial
                        @continue="handleTutorialComplete"
                    />
                </template>
                <template v-else>
                    <section v-if="!isReady || loading" class="Assessment">
                        <div class="loading">Loading...</div>
                    </section>
                    <section v-else-if="error" class="Assessment">
                        <div class="error">
                            <h3>Error Occurred</h3>
                            <p>{{ error }}</p>
                            <button @click="retryFetch" :disabled="form.processing">Retry</button>
                        </div>
                    </section>
                    <section v-else class="Assessment" tabindex="-1" data-testid="assessment-test">
                        <!-- <div v-if="debug" style="padding: 10px; background: #f5f5f5;">
                          <p>Lead in text: {{ currentTestData?.lead_in_text }}</p>
                          <p>Current item: {{ currentItem?.id }}</p>
                        </div> -->

                        <section class="Assessment__ItemSetLeadIn" v-if="!isComplete">
                            {{ currentTestData?.lead_in_text }}
                        </section>

                        <div class="Assessment__scroll-container">
                            <div v-if="!isComplete && currentItem?.id">
                                <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                                    <TestQuestion :key="`question-${currentItem.id}-${testStageStore.currentStage}`"
                                                  :current-item="currentItem" :current-item-index="currentItemIndex"
                                                  :test-stage="testStageStore.currentStage" :test-data="currentTestData"
                                                  :form="form"
                                                  :store="currentStore" @submit="submitAnswer" @go-back="goBack"
                                                  @skip="skipQuestion"
                                                  ref="questionRef"/>
                                </div>
                            </div>

                            <Discovery
                                v-if="isComplete && (
                    (testStageStore.currentStage === 'holland_codes' && hollandCodeStore?.progress?.archetypeDiscovery) ||
                    (testStageStore.currentStage === 'basic_interests' && basicInterestStore?.progress?.jobMatching) ||
                    (testStageStore.currentStage === 'degree' && degreeStore?.progress?.degreeMatching)
                  )"
                                :archetype-discovery="hollandCodeStore?.progress?.archetypeDiscovery"
                                :job-matching="basicInterestStore?.progress?.jobMatching"
                                :degree-matching="degreeStore?.progress?.degreeMatching"
                                :current-stage="testStageStore.currentStage"
                                @close="handleDiscoveryClose"
                            />

                            <section class="discovery"
                                     v-if="isComplete">
                                <NextStep
                                    :title="testStageStore.nextStageName || 'Complete'"
                                    :description="testStageStore.currentStageDescription || 'You have completed this section.'"
                                    :button-text="testStageStore.nextStageName ? `Continue to ${testStageStore.nextStageName}` : 'View Results'"
                                    :current-stage="testStageStore.currentStage"
                                    @continue="continueToNextSection"
                                    :disabled="form.processing"
                                />
                            </section>
                        </div>
                    </section>
                </template>
            </div>
        </div>
    </section>
</template>

<script setup>
import {ref, computed, watch, onMounted} from "vue";
import {useForm, router} from "@inertiajs/vue3";
import NextStep from "@/Components/Test/NextStep.vue";
import SidebarMainTest from "./SidebarMainTest.vue";
import TestQuestion from "@/Components/Test/TestQuestion.vue";
import {useHollandCodeStore} from "@/stores/hollandCodeStore";
import {useTestStageStore} from "@/stores/testStageStore";
import {useBasicInterestStore} from "@/stores/basicInterestStore";
import Discovery from "@/Components/Test/Discovery.vue";
import BackButton from "@/Components/Test/BackButton.vue";
import MatchResult from "@/Components/Test/MatchResult.vue";
import {useTestProgressStore} from '@/stores/testProgressStore';
import {useDegreeStore} from '@/stores/degreeStore';
import MainTestTutorial from "@/Components/Test/MainTestTutorial.vue";

const hollandCodeStore = useHollandCodeStore();
const testStageStore = useTestStageStore();
const basicInterestStore = useBasicInterestStore();
const progressStore = useTestProgressStore();
const degreeStore = useDegreeStore();

const form = useForm({
    itemId: null,
    answer: null,
    category: null,
    testStage: null,
    type: 'answered'
});

const currentStore = computed(() => {
    switch (testStageStore.currentStage) {
        case 'basic_interests':
            return basicInterestStore;
        case 'holland_codes':
            return hollandCodeStore;
        case 'degree':
            return degreeStore;
        default:
            return null;
    }
});

const currentTestData = computed(() => {
    switch (testStageStore.currentStage) {
        case 'basic_interests':
            return basicInterestStore.data;
        case 'holland_codes':
            return hollandCodeStore.data;
        case 'degree':
            return degreeStore.data;
        default:
            return null;
    }
});

const currentItem = computed(() => currentStore.value?.currentItem ?? null);
const currentItemIndex = computed(() => currentStore.value?.currentItemIndex ?? 0);

const isComplete = computed(() => {
    if (!testStageStore?.currentStage) return false;

    if (testStageStore.currentStage === 'holland_codes') {

        return hollandCodeStore?.isTestComplete ?? false;
    }
    return currentStore.value?.isComplete ?? false;
});

const canProceed = computed(() => {
    if (!testStageStore?.currentStage) return false;

    if (testStageStore.currentStage === 'holland_codes') {
        return hollandCodeStore?.isTestComplete ?? false;
    }
    return currentStore.value?.canProceed ?? false;
});

const loading = computed(() => currentStore.value?.loading ?? false);
const error = computed(() => currentStore.value?.error ?? null);
const isReady = computed(() => !!currentStore.value && !!currentTestData.value);

const debug = ref(true);

onMounted(async () => {
    try {
        await testStageStore?.initialize();

        if (testStageStore?.currentStage === 'basic_interests') {
            await basicInterestStore?.fetchData();
        } else if (testStageStore?.currentStage === 'holland_codes') {
            await hollandCodeStore?.fetchHollandCodes();
        } else if (testStageStore?.currentStage === 'degree') {
            await degreeStore?.fetchData();
        }
    } catch (error) {
        console.error('Error initializing test:', error);
    }
});

const retryFetch = async () => {
    if (form.processing) return;

    try {
        if (testStageStore.currentStage === 'basic_interests') {
            await basicInterestStore.fetchData();
        } else if (testStageStore.currentStage === 'holland_codes') {
            await hollandCodeStore.fetchHollandCodes();
        } else if (testStageStore.currentStage === 'degree') {
            await degreeStore.fetchData();
        }
    } catch (error) {
        console.error('Error retrying fetch:', error);
    }
};
const showTutorial = computed(() => {
    return testStageStore.currentStage === 'holland_codes' &&
           hollandCodeStore?.currentItemIndex === 0 &&
           !hollandCodeStore?.responses['tutorial'];
});

const submitAnswer = async () => {
    const store = currentStore.value;
    if (!store || form.processing || !form.answer || !form.itemId) return;

    try {
        form.testStage = testStageStore?.currentStage;
        await store.submitAnswer(form);
    } catch (err) {
        console.error('Error submitting answer:', err);
    } finally {
        form.answer = null;
    }
};

const goBack = () => {
    if (!currentStore.value || form.processing) return;
    currentStore.value.goBack();
};

const skipQuestion = () => {
    if (!currentStore.value || form.processing) return;
    currentStore.value.skipQuestion();
};

const handleDiscoveryClose = () => {
    if (form.processing) return;

    if (testStageStore.currentStage === 'basic_interests') {
        basicInterestStore.jobMatching = null;
    } else if (testStageStore.currentStage === 'holland_codes') {
        hollandCodeStore.progress.archetypeDiscovery = null;
    } else if (testStageStore.currentStage === 'degree') {
        degreeStore.progress.degreeMatching = null;
    }
};

const continueToNextSection = async () => {
    if (form.processing) return;

    try {
        const nextStage = testStageStore.getNextStage();
        if (nextStage) {
            const currentProgress = progressStore.stages[testStageStore.currentStage];
            if (!currentProgress?.completed) {
                throw new Error('Please complete the current stage before continuing.');
            }

            await testStageStore.changeStage(nextStage);
        } else {
            router.visit(route('dashboard'));
        }
    } catch (error) {
        console.error('Failed to continue to next section:', error);
    }
};

watch(() => currentTestData.value, (newData) => {

}, {immediate: true});

watch(() => currentStore.value, (newStore) => {
  
}, {immediate: true});

const handleTutorialComplete = () => {
    if (!hollandCodeStore) return;
    hollandCodeStore.responses['tutorial'] = true;
};
</script>

<style>
@import '/public/css/assessment.css';
</style>
