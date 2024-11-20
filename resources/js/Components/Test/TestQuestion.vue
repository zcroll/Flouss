<template>
  <div class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active"
    :data-item-index="currentItemIndex"
    :data-itemset-index="currentSetIndex"
    :id="'item-' + currentItem.id"
    :aria-label="ariaLabel"
    tabindex="1"
    aria-atomic="true"
    aria-live="assertive">
    <div>
      <BackButton v-if="currentItemIndex > 0" @click="$emit('go-back')" />
      <form @submit.prevent="$emit('submit')" ref="formRef">
        <h4 v-if="currentItemIndex === 0" aria-hidden="true">
          {{ testStage === 'holland_codes' ? 'Would you like to...' : 'Would you enjoy...' }}
        </h4>
        <h3 v-if="testStage === 'holland_codes' && currentItem.text_fr" aria-hidden="true">
          {{ currentItem.text_fr }}
        </h3>
        <h3 v-else-if="testStage === 'holland_codes'" aria-hidden="true">
          {{ currentItem.text }}
        </h3>
        <h3 v-if="testStage === 'basic_interests' && currentItem.category_fr" aria-hidden="true">
          {{ currentItem.category_fr }}
        </h3>
        <h3 v-else-if="testStage === 'basic_interests'" aria-hidden="true">
          {{ currentItem.category }}
        </h3>
        <h5 v-if="testStage === 'basic_interests' && currentItem.question_fr" aria-hidden="true">
          <p>{{ currentItem.question_fr }}</p>
        </h5>
        <h5 v-else-if="testStage === 'basic_interests'" aria-hidden="true">
          <p>{{ currentItem.question }}</p>
        </h5>
        
        <AnswerOptions 
          :current-item="currentItem"
          :test-stage="testStage"
          :previous-answers="previousAnswers"
          v-model="form.answer"
          @submit="$emit('submit')"
        />

        <button type="button"
          title="Skip question"
          aria-label="Skip question"
          aria-hidden="true"
          class="Assessment__RadioItem__skip"
          data-testid="skip-item"
          @click="$emit('skip')">
          Skip question
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BackButton from './BackButton.vue';
import AnswerOptions from './AnswerOptions.vue';

const props = defineProps({
  currentItem: {
    type: Object,
    required: true
  },
  currentItemIndex: {
    type: Number,
    required: true
  },
  currentSetIndex: {
    type: Number,
    required: true
  },
  testStage: {
    type: String,
    required: true
  },
  previousAnswers: {
    type: Object,
    required: true
  },
  form: {
    type: Object,
    required: true
  }
});

const formRef = ref(null);

const ariaLabel = computed(() => {
  const prefix = props.testStage === 'holland_codes' ? 'Would you like to' : 'Would you enjoy';
  const questionText = props.currentItem.text || props.currentItem.question;
  return `${prefix} ${questionText}? Use the number keys to select one of the following; 1: Hate it. 2: Dislike it. 3: Neutral. 4: Like it. 5: Love it. To skip this question, press the down arrow. To go back to the previous question, press the up arrow.`;
});

defineExpose({ formRef });
defineEmits(['submit', 'go-back', 'skip']);
</script> 