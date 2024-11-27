<template>
  <section class="Roadmap" data-testid="roadmap">
    <div v-if="error" class="error-container">
      <div class="error-alert">
        {{ error }}
      </div>
    </div>

    <div class="assessment-with-progress">
      <SidebarMainTest :progress="{
        hollandCodes: Number(hollandCodeStore?.progress?.progress_percentage || 0),
        basicInterest: {
          currentIndex: basicInterestStore?.progress?.current_index || 0,
          validResponses: basicInterestStore.validResponsesCount,
          percentage: basicInterestStore.progressPercentage,
          completed: basicInterestStore.isComplete
        },
        completed: isComplete && canProceed
      }" :test-stage="testStageStore.currentStage" />

      <div class="assessment Roadmap__inner">
        <section v-if="!isReady || loading" class="Assessment">
          <div class="loading">Loading...</div>
        </section>
        <section v-else-if="error" class="Assessment">
          <div class="error">
            <h3>Error Occurred</h3>
            <p>{{ error }}</p>
            <button @click="retryFetch">Retry</button>
          </div>
        </section>
        <section v-else class="Assessment" tabindex="-1" data-testid="assessment-test">
          <section class="Assessment__ItemSetLeadIn" v-if="!isComplete">
            {{ testStageStore.currentStage === 'basic_interest' 
              ? basicInterestStore?.basicInterestData?.lead_in_text 
              : hollandCodeStore?.hollandCodesData?.lead_in_text }}
          </section>

          <div class="Assessment__scroll-container">
            <div v-if="!isComplete && currentItem?.id">
              <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <TestQuestion 
                  :key="`question-${currentItem.id}-${testStageStore.currentStage}`"
                  :current-item="currentItem" 
                  :current-item-index="currentItemIndex"
                  :test-stage="testStageStore.currentStage"
                  :holland-codes="currentTestData"
                  :form="form"
                  :holland-code-store="currentStore"
                  @submit="submitAnswer"
                  @go-back="goBack"
                  @skip="skipQuestion"
                  ref="questionRef"
                />
              </div>
            </div>

            <Discovery 
              v-if="isComplete && testStageStore.currentStage === 'holland_codes' && hollandCodeStore?.progress?.archetypeDiscovery"
              :archetype-discovery="hollandCodeStore.progress.archetypeDiscovery"
              @close="handleDiscoveryClose"
            />

            <section class="discovery" v-if="isComplete && canProceed">
              <NextStep @continue="continueToNextSection" />
            </section>
          </div>
        </section>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import NextStep from "@/Components/Test/NextStep.vue";
import SidebarMainTest from "./SidebarMainTest.vue";
import TestQuestion from "@/Components/Test/TestQuestion.vue";
import { useHollandCodeStore } from "@/stores/hollandCodeStore";
import { useTestStageStore } from "@/stores/testStageStore";
import { useBasicInterestStore } from "@/stores/basicInterestStore";
import Discovery from "@/Components/Test/Discovery.vue";
import { storeToRefs } from "pinia";
import BackButton from "@/Components/Test/BackButton.vue";

const hollandCodeStore = useHollandCodeStore();
const testStageStore = useTestStageStore();
const basicInterestStore = useBasicInterestStore();

const form = useForm({
  itemId: null,
  answer: null,
  category: 'holland_codes',
  testStage: null,
  type: 'answered'
});

const props = defineProps({
  testStage: String,
});

const questionRef = ref(null);

// Computed properties for current state
const currentStore = computed(() => {
  return testStageStore.currentStage === 'basic_interest' ? basicInterestStore : hollandCodeStore;
});

const currentTestData = computed(() => {
  return testStageStore.currentStage === 'basic_interest' 
    ? basicInterestStore.basicInterestData 
    : hollandCodeStore.hollandCodesData;
});

const currentItemIndex = computed(() => {
  return testStageStore.currentStage === 'basic_interest'
    ? basicInterestStore.currentItemIndex
    : hollandCodeStore.currentItemIndex;
});

const currentItem = computed(() => {
  return currentStore.value.currentItem;
});

const isComplete = computed(() => {
  if (testStageStore.currentStage === 'basic_interest') {
    return basicInterestStore.isComplete;
  }
  return currentStore.value?.progress?.completed || false;
});

const canProceed = computed(() => {
  if (testStageStore.currentStage === 'basic_interest') {
    const canProceed = basicInterestStore.canProceed;
    console.log('Basic Interest canProceed check:', {
      validResponses: basicInterestStore.validResponsesCount,
      totalQuestions: basicInterestStore.totalQuestionsCount,
      canProceed
    });
    return canProceed;
  }
  return currentStore.value?.progress?.completed || false;
});

const loading = computed(() => {
  return currentStore.value?.loading || false;
});

const error = computed(() => {
  return currentStore.value?.error || null;
});

const isReady = computed(() => {
  return !loading.value && !error.value;
});

// Watch for stage changes
watch(() => props.testStage, (newStage) => {
  if (newStage) {
    form.testStage = newStage;
  }
}, { immediate: true });

watch(currentItem, (newItem) => {
  if (newItem?.id && !isComplete.value) {
    form.itemId = newItem.id;
    form.category = newItem.category || testStageStore.currentStage;
  }
}, { immediate: true });

const handleDiscoveryClose = () => {
  console.log('Discovery closed');
};

onMounted(async () => {
  try {
    if (testStageStore.currentStage === 'basic_interest') {
      await basicInterestStore.initialize();
    } else {
      await hollandCodeStore.fetchHollandCodes();
    }
  } catch (err) {
    console.error('Error initializing test:', err);
  }
});

const retryFetch = () => {
  if (testStageStore.currentStage === 'basic_interest') {
    basicInterestStore.initialize();
  } else {
    hollandCodeStore.fetchHollandCodes();
  }
};

const submitAnswer = async () => {
  if (form.answer !== null || form.type === "skipped") {
    const formData = {
      itemId: form.itemId,
      answer: form.type === "skipped" ? 0 : form.answer,
      type: form.type,
      category: form.category,
      testStage: form.testStage
    };

    try {
      await currentStore.value.submitAnswer(formData);
      form.reset();
      form.type = 'answered';
      form.testStage = props.testStage;
    } catch (error) {
      console.error('Error submitting answer:', error);
    }
  }
};

const goBack = async () => {
  try {
    await currentStore.value.goBack();
  } catch (error) {
    console.error('Error going back:', error);
  }
};

const skipQuestion = () => {
  form.type = "skipped";
  form.answer = 0;
  submitAnswer();
};

const continueToNextSection = async () => {
  try {
    await testStageStore.changeStage('basic_interest');
    if (testStageStore.error) {
      console.error('Stage transition error:', testStageStore.error);
    }
  } catch (error) {
    console.error('Error transitioning to next section:', error);
  }
};
</script>

<style>
@import '/public/css/assessment.css';
</style>
