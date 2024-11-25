<template>
  <section class="Roadmap" data-testid="roadmap">
    <div v-if="hollandCodeStore.hasErrors" class="error-container">
      <div v-for="error in hollandCodeStore.getErrorMessages" :key="error.type" class="error-alert">
        {{ error.message }}
      </div>
    </div>

    <div class="assessment-with-progress">
      <SidebarMainTest 
        :progress="{
          hollandCodes: Number(hollandCodeStore.progress?.progress_percentage || 0),
          basicInterest: 0,
          completed: Boolean(hollandCodeStore.progress?.completed || false)
        }"
        :test-stage="testStage" 
      />
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
          <section class="Assessment__ItemSetLeadIn" v-if="hollandCodeStore.hollandCodesData && !hollandCodeStore.progress.completed">
            {{ hollandCodeStore.hollandCodesData.lead_in_text }}
          </section>

          <div class="Assessment__scroll-container">
            <div v-if="!hollandCodeStore.progress.completed && currentItem?.id">
              <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <TestQuestion 
                  :key="`question-${currentItem.id}-${hollandCodeStore.currentItemIndex}`" 
                  :current-item="currentItem"
                  :current-item-index="hollandCodeStore.currentItemIndex" 
                  :test-stage="testStage"
                  :holland-codes="hollandCodeStore.hollandCodesData" 
                  :form="form" 
                  :holland-code-store="hollandCodeStore"
                  @submit="submitAnswer" 
                  @go-back="goBack" 
                  @skip="skipQuestion" 
                  ref="questionRef" 
                />
              </div>
            </div>
            
            <div v-else>
              <Discovery 
                v-if="hollandCodeStore.progress.archetypeDiscovery" 
                :archetype-discovery="hollandCodeStore.progress.archetypeDiscovery" 
              />
              <NextStep @continue="continueToNextSection" />
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import NextStep from "@/Components/Test/NextStep.vue";
import SidebarMainTest from "./SidebarMainTest.vue";
import TestQuestion from "@/Components/Test/TestQuestion.vue";
import { useHollandCodeStore } from "@/stores/hollandCodeStore";
import Discovery from "@/Components/Test/Discovery.vue";
import { storeToRefs } from "pinia";

const hollandCodeStore = useHollandCodeStore();

// Initialize form with regular values
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

const showNextStep = ref(false);
const questionRef = ref(null);

// Get store refs after initialization
const {
  currentItem,
  loading,
  error,
  isComplete,
  isReady,
} = storeToRefs(hollandCodeStore);

// Watch for test stage changes
watch(() => props.testStage, (newStage) => {
  if (newStage) {
    form.testStage = newStage;
  }
}, { immediate: true });

// Watch for current item changes
watch(currentItem, (newItem) => {
  if (newItem?.id && !hollandCodeStore.progress.completed) {
    form.itemId = newItem.id;
    form.category = newItem.category || 'holland_codes';
  }
}, { immediate: true });

onMounted(async () => {
  try {
    await hollandCodeStore.fetchHollandCodes();
  } catch (err) {
    console.error('Error fetching holland codes:', err);
  }
});

const retryFetch = () => {
  hollandCodeStore.fetchHollandCodes();
};

const submitAnswer = async () => {
  if (form.answer !== null || form.type === "skipped") {
    await hollandCodeStore.submitAnswer({
      itemId: form.itemId,
      answer: form.answer,
      type: form.type,
      category: form.category,
      testStage: form.testStage
    });
    form.reset();
    form.type = 'answered';
    form.testStage = props.testStage;
  }
};

const goBack = () => {
  hollandCodeStore.goBack();
};

const skipQuestion = () => {
  form.type = "skipped";
  submitAnswer();
};

const continueToNextSection = () => {
  hollandCodeStore.continueToNextSection();
};
</script>

<style>
@import '/public/css/assessment.css';
</style>
