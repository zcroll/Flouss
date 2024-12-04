<template>
  <div class="Assessment__Item--forward-enter-done">
    <div 
      class="Assessment__Item Assessment__Interstitial Assessment__Interstitial--active"
      :data-item-index="0"
      :data-itemset-index="1" 
      :id="`degree-matching-${currentStage}`"
      :aria-label="`${$t('degree.matching_results')}. ${$t('degree.completion_message')}`"
      tabindex="1"
      aria-atomic="true"
      aria-live="assertive"
    >
      <div>
        <h4 aria-hidden="true">{{ $t('degree.results_header') }}</h4>
        <h3 aria-hidden="true">{{ $t('degree.matching_results') }}</h3>
        
        <div v-if="loading" class="loading-state">
          <span class="spinner"></span>
          {{ $t('degree.loading') }}
        </div>
        
        <div v-else-if="error" class="error-message">
          {{ error }}
          <button class="retry-button" @click="handleRetry">
            {{ $t('common.retry') }}
          </button>
        </div>
        
        <div v-else class="degree-results">
          <div class="degree-list">
            <div v-for="(degree, index) in degreeMatches" 
                 :key="index" 
                 class="degree-item"
                 :class="{ 'active': selectedDegree === degree.id }"
            >
              <div class="degree-header">
                <h5>{{ degree.name }}</h5>
                <div class="match-score">
                  {{ $t('degree.match_score', { score: calculateMatchPercentage(degree.score) }) }}
                </div>
              </div>
              
              <div class="degree-details" v-if="selectedDegree === degree.id">
                <p>{{ degree.description }}</p>
                <div class="categories">
                  <span v-for="(category, idx) in degree.categories" 
                        :key="idx" 
                        class="category-tag">
                    {{ category }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div class="action-buttons">
            <button 
              class="alans-butt--grey"
              @click="handleContinue"
              :disabled="transitioning"
            >
              <span v-if="transitioning" class="loading-indicator">
                <span class="spinner"></span>
                {{ $t('common.transitioning') }}
              </span>
              <span v-else>
                {{ $t('degree.next_step') }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showNextStep" class="next-step-section">
      <NextStep 
        :title="testStageStore.nextStageName || 'Complete'"
        :description="testStageStore.currentStageDescription || 'You have completed this section.'"
        :button-text="testStageStore.nextStageName ? `Continue to ${testStageStore.nextStageName}` : 'View Results'"
        :current-stage="currentStage"
        @continue="handleNextStep"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useDegreeStore } from '@/stores/degreeStore';
import { useTestStageStore } from '@/stores/testStageStore';
import { useTestProgressStore } from '@/stores/testProgressStore';
import { useI18n } from 'vue-i18n';
import NextStep from './NextStep.vue';

const { t } = useI18n();
const degreeStore = useDegreeStore();
const testStageStore = useTestStageStore();
const progressStore = useTestProgressStore();

const props = defineProps({
  degreeMatches: {
    type: Array,
    required: true
  },
  currentStage: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['continue']);

const loading = ref(false);
const error = ref(null);
const transitioning = ref(false);
const selectedDegree = ref(null);

const calculateMatchPercentage = (score) => {
  return Math.round(score * 100);
};

const handleDegreeSelect = (degreeId) => {
  selectedDegree.value = selectedDegree.value === degreeId ? null : degreeId;
};

const showNextStep = computed(() => {
  const currentProgress = progressStore.stages[props.currentStage];
  return currentProgress?.completed && currentProgress?.canTransition;
});

const handleNextStep = async () => {
  if (transitioning.value) return;
  
  transitioning.value = true;
  error.value = null;
  
  try {
    const nextStage = testStageStore.getNextStage();
    if (!nextStage) {
      emit('continue');
      return;
    }

    const currentProgress = progressStore.stages[props.currentStage];
    
    if (!currentProgress?.completed) {
      throw new Error('Please complete the current stage before continuing.');
    }
    
    if (!currentProgress?.canTransition) {
      throw new Error('Cannot proceed yet. Please ensure all required steps are completed.');
    }

    await testStageStore.changeStage(nextStage);
    if (testStageStore.error) {
      throw new Error(testStageStore.error);
    }
    emit('continue');
  } catch (err) {
    error.value = err.message || t('degree.error.transition');
    console.error('Stage transition error:', err);
  } finally {
    transitioning.value = false;
  }
};

const handleRetry = () => {
  error.value = null;
  handleContinue();
};
</script>

<style scoped>
.degree-results {
  padding: 2rem 0;
}

.degree-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

.degree-item {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
}

.degree-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.degree-item.active {
  border: 2px solid #0066cc;
}

.degree-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.match-score {
  background: #e5f6ff;
  color: #0066cc;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
}

.degree-details {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.categories {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 1rem;
}

.category-tag {
  background: #f5f5f5;
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.875rem;
}

.action-buttons {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
}

.error-message {
  color: #dc2626;
  margin: 1rem 0;
  padding: 0.5rem;
  border: 1px solid #dc2626;
  border-radius: 0.375rem;
  background-color: #fee2e2;
}

.retry-button {
  margin-left: 0.5rem;
  padding: 0.25rem 0.5rem;
  background-color: #dc2626;
  color: white;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
}

.loading-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid #f3f3f3;
  border-top: 2px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-state {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding: 2rem;
  color: #666;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.next-step-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #eee;
}
</style> 