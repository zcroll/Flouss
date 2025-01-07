<template>
  <div class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active" :data-i="'i'" aria-atomic="true"
    aria-live="assertive">
    <div v-if="!store.progress.completed">
      <BackButton 
        :loading="store.loading" 
        :disabled="!currentItemIndex" 
        @go-back="handleGoBack"
      />

      <form @submit.prevent="submitAnswer" ref="formRef">
        <h4 v-if="testData?.lead_in_text">
          {{ testData.lead_in_text }}
        </h4>
        <h3 aria-hidden="true" v-if="currentItem">
          {{ currentItem.text }}
        </h3>

        <AnswerOptions 
          v-if="currentItem && optionSet" 
          :current-item="currentItem" 
          :options="optionSet.options"
          :test-stage="testStage" 
          v-model="form.answer" 
          :store="store" 
          @submit="submitAnswer" 
        />

        <!-- <div class="Assessment__Question__actions">
          <button 
            type="button" 
            class="Assessment__Question__skip" 
            data-testid="skip-question"
            :disabled="store.loading"
            @click="handleSkip"
          >
            {{ store.loading ? 'Skipping...' : 'Skip question' }}
          </button>
        </div> -->
      </form>
    </div>

    <div v-else class="Assessment__completion">
      <div class="Assessment__completion-content">
        <h2>Basic Interests Assessment Complete!</h2>
        <p>Great job! You've completed the Basic Interests assessment.</p>
        <div class="Assessment__completion-actions">
          <NextStep 
            :test-stage="testStage"
            :is-completed="true"
            @proceed="handleProceed"
          />
        </div>
      </div>
      <MatchJob v-if="store.progress.jobMatching" @close="closeJobMatches" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { storeToRefs } from 'pinia';
import BackButton from './BackButton.vue';
import AnswerOptions from './AnswerOptions.vue';
import NextStep from './NextStep.vue';
import MatchJob from './matchjob.vue';

const props = defineProps({
  testData: {
    type: Object,
    required: true
  },
  form: {
    type: Object,
    required: true
  },
  currentItem: {
    type: Object,
    required: true
  },
  testStage: {
    type: String,
    required: true
  },
  store: {
    type: Object,
    required: true
  },
  currentItemIndex: {
    type: Number,
    required: true
  }
});

const formRef = ref(null);
const showJobMatches = ref(false);

// Get reactive store properties
const { progress } = storeToRefs(props.store);

// Computed property for current option set
const optionSet = computed(() => {
  if (props.testData?.option_sets?.length > 0) {
    return props.testData.option_sets[0];
  }
  return null;
});

// Watch for test completion
watch(() => progress.value?.completed, (newValue) => {
  if (newValue && progress.value?.jobMatching) {
    showJobMatches.value = true;
  }
});

onMounted(() => {
  if (props.currentItem) {
    props.form.itemId = props.currentItem.id;
    props.form.category = props.testStage;
  }
});

const emit = defineEmits(['submit', 'go-back', 'skip']);

const submitAnswer = async () => {
  if (props.store.loading) return;
  emit('submit');
};

const handleGoBack = () => {
  emit('go-back');
};

const handleSkip = () => {
  emit('skip');
};

const handleProceed = () => {
  emit('proceed');
};

const closeJobMatches = () => {
  showJobMatches.value = false;
};

defineExpose({ formRef });
</script>

<style scoped>
.Assessment__Item {
  position: relative;
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.Assessment__completion {
  text-align: center;
  padding: 2rem;
}

.Assessment__completion-content {
  max-width: 600px;
  margin: 0 auto;
}

.Assessment__completion-content h2 {
  font-size: 1.8rem;
  color: #2d3748;
  margin-bottom: 1rem;
}

.Assessment__completion-content p {
  color: #4a5568;
  margin-bottom: 2rem;
}

.Assessment__completion-actions {
  margin-top: 2rem;
}

.Assessment__Question__actions {
  margin-top: 2rem;
  text-align: center;
}

.Assessment__Question__skip {
  background: none;
  border: none;
  color: #4a5568;
  cursor: pointer;
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  transition: color 0.2s;
}

.Assessment__Question__skip:hover {
  color: #2d3748;
}

.Assessment__Question__skip:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>