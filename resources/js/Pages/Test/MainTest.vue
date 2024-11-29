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
            {{ currentStore?.testData?.lead_in_text }}
          </section>

          <div class="Assessment__scroll-container">
            <div v-if="!isComplete && currentItem?.id">
              <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <TestQuestion :key="`question-${currentItem.id}-${testStageStore.currentStage}`"
                  :current-item="currentItem" :current-item-index="currentItemIndex"
                  :test-stage="testStageStore.currentStage" :test-data="currentTestData" :form="form"
                  :store="currentStore" @submit="submitAnswer" @go-back="goBack" @skip="skipQuestion"
                  ref="questionRef" />
              </div>
            </div>

            <Discovery
              v-if="isComplete && testStageStore.currentStage === 'holland_codes' && hollandCodeStore?.progress?.archetypeDiscovery"
              :archetype-discovery="hollandCodeStore.progress.archetypeDiscovery" 
              @close="handleDiscoveryClose" 
            />

            <section class="discovery"
              v-if="isComplete">
              <div class="next-stage-info" v-if="testStageStore.nextStageName">
                <h3>Next Step: {{ testStageStore.nextStageName }}</h3>
                <p>{{ testStageStore.currentStageDescription }}</p>
              </div>
              <NextStep 
                :title="testStageStore.nextStageName || 'Complete'"
                :description="testStageStore.currentStageDescription || 'You have completed this section.'"
                :button-text="testStageStore.nextStageName ? `Continue to ${testStageStore.nextStageName}` : 'View Results'"
                :current-stage="testStageStore.currentStage"
                @continue="continueToNextSection" 
              />
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
import BackButton from "@/Components/Test/BackButton.vue";

const hollandCodeStore = useHollandCodeStore();
const testStageStore = useTestStageStore();
const basicInterestStore = useBasicInterestStore();

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
    default:
      return null;
  }
});

const currentTestData = computed(() => {
  switch (testStageStore.currentStage) {
    case 'basic_interests':
      return basicInterestStore.basicInterestData;
    case 'holland_codes':
      return hollandCodeStore.hollandCodesData;
    default:
      return null;
  }
});

const currentItem = computed(() => currentStore.value?.currentItem || null);
const currentItemIndex = computed(() => currentStore.value?.currentItemIndex || 0);
const isComplete = computed(() => {
  if (testStageStore.currentStage === 'holland_codes') {
    return hollandCodeStore.isTestComplete;
  }
  return currentStore.value?.isComplete || false;
});
const canProceed = computed(() => {
  if (testStageStore.currentStage === 'holland_codes') {
    return hollandCodeStore.isTestComplete;
  }
  return currentStore.value?.canProceed || false;
});
const loading = computed(() => currentStore.value?.loading || false);
const error = computed(() => currentStore.value?.error || null);
const isReady = computed(() => currentStore.value && currentTestData.value);

onMounted(async () => {
  try {
    if (testStageStore.currentStage === 'basic_interests') {
      await basicInterestStore.initialize();
    } else if (testStageStore.currentStage === 'holland_codes') {
      await hollandCodeStore.fetchHollandCodes();
    }
  } catch (error) {
    console.error('Error initializing test:', error);
  }
});

const retryFetch = async () => {
  try {
    if (testStageStore.currentStage === 'basic_interests') {
      await basicInterestStore.initialize();
    } else if (testStageStore.currentStage === 'holland_codes') {
      await hollandCodeStore.fetchHollandCodes();
    }
  } catch (error) {
    console.error('Error retrying fetch:', error);
  }
};

const submitAnswer = async () => {
  if (!currentStore.value) return;
  
  form.testStage = testStageStore.currentStage;
  await currentStore.value.submitAnswer(form);
  form.answer = null;
};

const goBack = () => {
  if (!currentStore.value) return;
  currentStore.value.goBack();
};

const skipQuestion = () => {
  if (!currentStore.value || currentStore.value.loading) return;
  currentStore.value.skipQuestion();
};

const handleDiscoveryClose = () => {
  if (testStageStore.currentStage === 'holland_codes') {
    hollandCodeStore.progress.archetypeDiscovery = null;
  }
};

const continueToNextSection = async () => {
  try {
    const nextStage = testStageStore.getNextStage();
    if (nextStage) {
      await testStageStore.changeStage(nextStage);
    } else {
      router.visit(route('dashboard'));
    }
  } catch (error) {
    console.error('Failed to continue to next section:', error);
  }
};
</script>

<style>
@import '/public/css/assessment.css';
</style>
