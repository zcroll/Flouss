<template>
  <div class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active" :data-i="'i'" aria-atomic="true"
    aria-live="assertive">
    <div>
      <BackButton 
        :loading="hollandCodeStore.loading" 
        :disabled="!hollandCodeStore.currentItemIndex" 
        @go-back="handleGoBack"
      />

      <form @submit.prevent="submitAnswer" ref="formRef">
        <h4 v-if="hollandCodes?.lead_in_text">
          {{ hollandCodes.lead_in_text }}
        </h4>
        <h3 aria-hidden="true" v-if="currentItem">
          {{ currentItem.text }}
        </h3>

        <AnswerOptions v-if="currentItem && optionSet" :current-item="currentItem" :options="optionSet.options"
          :test-stage="testStage" v-model="form.answer" :store="hollandCodeStore" @submit="submitAnswer" />

        <div class="Assessment__Question__actions">
          <button 
            type="button" 
            class="Assessment__Question__skip" 
            data-testid="skip-question"
            :disabled="hollandCodeStore.loading"
            @click="handleSkip"
          >
            {{ hollandCodeStore.loading ? 'Skipping...' : 'Skip question' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import BackButton from './BackButton.vue';
import AnswerOptions from './AnswerOptions.vue';

const props = defineProps({
  hollandCodes: {
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
  hollandCodeStore: {
    type: Object,
    required: true
  },
  currentItemIndex: {
    type: Number,
    required: true
  }
});

const formRef = ref(null);

// Computed property for current option set
const optionSet = computed(() => {
  if (props.hollandCodes?.option_sets?.length > 0) {
    return props.hollandCodes.option_sets[0];
  }
  return null;
});

onMounted(() => {
  if (props.currentItem) {
    props.form.itemId = props.currentItem.id;
    props.form.category = 'holland_codes';
  }
});

// Watch for changes in current item
watch(() => props.currentItem, (newItem) => {
  if (newItem) {
    props.form.itemId = newItem.id;
    props.form.category = 'holland_codes';
  }
}, { immediate: true });

const emit = defineEmits(['submit', 'go-back', 'skip']);

const submitAnswer = () => {
  try {
    if (props.form.answer !== null) {
      emit('submit');
    }
  } catch (error) {
    props.hollandCodeStore.logError('submission', error);
  }
};

const handleGoBack = () => {
  emit('go-back');
};

const handleSkip = () => {
  if (props.hollandCodeStore.loading) return;
  emit('skip');
};

defineExpose({ formRef });
</script>

<style scoped>
.Assessment__Item {
  position: relative;
  margin-bottom: 2rem;
}

/* ... */

.Assessment__RadioItem__skip {
  margin-top: 1rem;
  color: #6b7280;
  font-size: 0.875rem;
  text-decoration: underline;
  background: none;
  border: none;
  cursor: pointer;
}

.Assessment__RadioItem__skip:hover {
  color: #4f46e5;
}

.Assessment__Question__actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 1rem;
}


.Assessment__Question__skip:hover:not(:disabled) {
  opacity: 1;
}

.Assessment__Question__skip:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>