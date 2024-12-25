<template>
  <div class="Assessment__Item--forward-enter-done">
    <div 
      class="Assessment__Item Assessment__Interstitial Assessment__Interstitial--active"
      :data-item-index="0"
      :data-itemset-index="1" 
      :id="`next-step-${currentStage}`"
      :aria-label="`${title}. Press Enter to continue.`"
      tabindex="1"
      aria-atomic="true"
      aria-live="assertive"
    >
      <div>
        <h4 aria-hidden="true">{{ __('test.next_step') }}</h4>
        <h3 aria-hidden="true">{{ title }}</h3>

        <div v-if="error" class="error-message">
          {{ error }}
          <button class="retry-button" @click="handleRetry">{{ __('test.retry') }}</button>
        </div>
        <button 
          id="interstitial-enter-button"
          class="alans-butt--grey"
          @click="handleContinue"
          :disabled="loading || disabled"
        >
          <span v-if="loading" class="loading-indicator">
            <span class="spinner"></span>
            {{ __('test.transitioning') }}
          </span>
          <span v-else>
            {{ buttonText }}
          </span>
        </button>
        <h5 aria-hidden="true">
          {{ description }}
        </h5>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useTestStageStore } from '@/stores/testStageStore';
import { useTestProgressStore } from '@/stores/testProgressStore';
import { useBasicInterestStore } from '@/stores/basicInterestStore';
import { useDegreeStore } from '@/stores/degreeStore';

const props = defineProps({
  title: {
    type: String,
    default: 'Continue to Next Section'
  },
  description: {
    type: String,
    default: 'You have completed this section. Click below to continue to the next part of your assessment.'
  },
  buttonText: {
    type: String,
    default: 'Continue'
  },
  currentStage: {
    type: String,
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['continue']);
const testStageStore = useTestStageStore();
const progressStore = useTestProgressStore();
const basicInterestStore = useBasicInterestStore();
const degreeStore = useDegreeStore();
const loading = ref(false);
const error = ref(null);

const handleContinue = async () => {
  if (loading.value) return;
  
  loading.value = true;
  error.value = null;
  
  try {
    const nextStage = testStageStore.getNextStage();
    
    // If there's no next stage, we're at the final step
    if (!nextStage) {
      try {
        // Debug stage completion status
        const stageStatus = testStageStore.stages.map(stage => ({
          stage,
          completed: testStageStore.isStageComplete(stage),
          progress: progressStore.stages[stage]
        }));
        console.log('Stage completion status:', stageStatus);

        // Check current stage completion
        const currentStageComplete = progressStore.stages[props.currentStage]?.completed;
        if (!currentStageComplete) {
          throw new Error('Please complete the current stage before continuing.');
        }

        // Make API call to save results
        const response = await axios.post(route('test.save-results'));
        
        console.log('Save results response:', response);
        if (response.data.success) {
          testStageStore.clearError();
          // Always navigate to results page on success
          router.visit(route('results'));
        } else {
          throw new Error(response.data.message || 'Failed to save results');
        }
        
        return;
      } catch (err) {
        console.error('Save results error:', err);
        throw new Error(err.response?.data?.message || err.message || 'Failed to save your results. Please try again.');
      }
    }

    const currentProgress = progressStore.stages[props.currentStage];
    
    if (!currentProgress?.completed) {
      throw new Error('Please complete the current stage before continuing.');
    }
    
    if (!currentProgress?.canTransition) {
      throw new Error('Cannot proceed yet. Please ensure all required steps are completed.');
    }

    if (props.currentStage === 'basic_interests' && nextStage === 'degree') {
      if (!basicInterestStore?.progress?.jobMatching) {
        throw new Error('Please wait for job matching to complete before proceeding.');
      }
    }

    if (props.currentStage === 'degree' && !nextStage) {
      if (!degreeStore?.progress?.degreeMatching) {
        throw new Error('Please wait for degree matching to complete before proceeding.');
      }
    }

    const success = await testStageStore.changeStage(nextStage);
    if (!success) {
      throw new Error(testStageStore.error || 'Failed to change stage');
    }
    emit('continue');
  } catch (err) {
    error.value = err.message || 'Failed to proceed to next stage';
    console.error('Stage transition error:', err);
  } finally {
    loading.value = false;
  }
};

const handleRetry = () => {
  error.value = null;
  handleContinue();
};
</script>

<style scoped>
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

.retry-button:hover {
  background-color: #b91c1c;
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

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

</style>
