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
                personality: {
                    currentIndex: personalityStore?.progress?.current_index ?? 0,
                    validResponses: personalityStore?.validResponsesCount ?? 0,
                    percentage: personalityStore?.progressPercentage ?? 0,
                    completed: personalityStore?.isComplete ?? false,
                    personalityReport: personalityStore?.progress?.personalityReport
                },
                completed: isComplete && canProceed
            }" :test-stage="testStageStore?.currentStage"/>

            <div class="assessment Roadmap__inner" >
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

                        <div class="Assessment__scroll-container" ref="formRef">
                            <div 
                                v-if="!isComplete && currentItem?.id" 
                                :key="`question-${currentItem.id}`"
                                class="Assessment__question-container"
                            >
                                <TestQuestion 
                                    :current-item="currentItem" 
                                    :current-item-index="currentItemIndex"
                                    :test-stage="testStageStore.currentStage" 
                                    :test-data="currentTestData"
                                    :form="form"
                                    :store="currentStore" 
                                    @submit="handleSubmit"
                                    @go-back="handleGoBack"
                                    @skip="handleSkip"
                                    ref="questionRef"
                                />
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
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import NextStep from "@/Components/Test/NextStep.vue";
import SidebarMainTest from "./SidebarMainTest.vue";
import TestQuestion from "@/Components/Test/TestQuestion.vue";
import { useHollandCodeStore } from "@/stores/hollandCodeStore";
import { useTestStageStore } from "@/stores/testStageStore";
import { useBasicInterestStore } from "@/stores/basicInterestStore";
import { usePersonalityStore } from "@/stores/personalityStore";
import Discovery from "@/Components/Test/Discovery.vue";
import BackButton from "@/Components/Test/BackButton.vue";
import MatchResult from "@/Components/Test/MatchResult.vue";
import { useTestProgressStore } from '@/stores/testProgressStore';
import { useDegreeStore } from '@/stores/degreeStore';
import MainTestTutorial from "@/Components/Test/MainTestTutorial.vue";
import anime from 'animejs/lib/anime.es.js';
const hollandCodeStore = useHollandCodeStore();
const testStageStore = useTestStageStore();
const basicInterestStore = useBasicInterestStore();
const progressStore = useTestProgressStore();
const degreeStore = useDegreeStore();
const personalityStore = usePersonalityStore();

const form = useForm({
    itemId: null,
    answer: null,
    category: null,
    testStage: null,
    type: 'answered'
});

const formRef = ref(null);
const currentAnimation = ref(null);
const isAnimating = ref(false);

const canAnimate = computed(() => 
  formRef.value && 
  !isAnimating.value && 
  !currentStore.value?.loading
);

const currentItem = computed(() => 
  currentStore.value?.currentItem
);

const currentStore = computed(() => {
    return testStageStore.getStageStore();
});

const currentTestData = computed(() => {
    switch (testStageStore.currentStage) {
        case 'basic_interests':
            return basicInterestStore.data;
        case 'holland_codes':
            return hollandCodeStore.data;
        case 'degree':
            return degreeStore.data;
        case 'personality':
            return personalityStore.data;
        default:
            return null;
    }
});

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
        } else if (testStageStore?.currentStage === 'personality') {
            await personalityStore?.fetchData();
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
        } else if (testStageStore.currentStage === 'personality') {
            await personalityStore.fetchData();
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

const animateTransition = (direction, callback) => {
  if (!canAnimate.value) return;
  
  const animations = {
    next: {
      start: { translateY: [0, '-100%'], scale: [1, 0.98], opacity: [1, 0] },
      end: { translateY: ['100%', 0], scale: [0.98, 1], opacity: [0, 1] }
    },
    back: {
      start: { translateY: [0, '100%'], scale: [1, 0.98], opacity: [1, 0] },
      end: { translateY: ['-100%', 0], scale: [0.98, 1], opacity: [0, 1] }
    }
  };

  const timing = {
    duration: 400,
    easing: 'cubicBezier(.4,0,.2,1)'
  };

  // Start the process
  const processTransition = async () => {
    try {
      isAnimating.value = true;

      // First, update the data
      await callback();
      
      // Then start exit animation
      await anime({
        targets: formRef.value,
        ...animations[direction].start,
        ...timing
      }).finished;

      // Wait for store to update and DOM to reflect changes
      await nextTick();

      // Finally, animate in the new content
      await anime({
        targets: formRef.value,
        ...animations[direction].end,
        ...timing
      }).finished;

    } catch (error) {
      console.error('Transition error:', error);
    } finally {
      isAnimating.value = false;
    }
  };

  processTransition();
};

const handleSubmit = () => {
  if (!canAnimate.value) return;

  animateTransition('next', async () => {
    const store = currentStore.value;
    if (!store || !form.answer || !form.itemId) return;

    // Submit the answer first
    await store.submitAnswer({
      itemId: currentItem.value.id,
      answer: form.answer,
      type: 'answered'
    });

    // Clear form after successful submission
    form.answer = null;
  });
};

const handleGoBack = () => {
  if (!canAnimate.value) return;

  animateTransition('back', async () => {
    const store = currentStore.value;
    if (!store) return;

    // Go back first
    await store.goBack();
    
    // Update form with previous question's answer
    form.answer = store.responses[store.currentItem?.id] || null;
  });
};

const handleSkip = () => {
    if (!currentStore.value || form.processing || isAnimating.value) return;
    
    animateTransition('next', () => {
        currentStore.value.skipQuestion();
    });
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
    console.log('currentTestData changed:', {
        data: newData,
        stage: testStageStore.currentStage,
        storeData: currentStore.value?.data
    });
}, {immediate: true});

watch(() => currentStore.value, (newStore) => {
    console.log('currentStore changed:', {
        store: newStore,
        data: newStore?.data,
        currentItem: newStore?.currentItem
    });
}, {immediate: true});

const handleTutorialComplete = () => {
    if (!hollandCodeStore) return;
    hollandCodeStore.responses['tutorial'] = true;
};

// Watchers
watch(() => currentItem.value, (newItem) => {
  if (newItem) {
    form.itemId = newItem.id;
  }
}, { immediate: true });

// Cleanup
onBeforeUnmount(() => {
  if (currentAnimation.value) {
    currentAnimation.value.pause();
  }
});

// Watch for store updates
watch(() => currentStore.value?.currentItem, (newItem) => {
  if (newItem) {
    form.itemId = newItem.id;
    form.answer = currentStore.value?.responses[newItem.id] || null;
  }
}, { immediate: true });
</script>

<style>
@import '/public/css/assessment.css';


</style>
