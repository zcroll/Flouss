<template>
  <div 
    class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant"
    role="radiogroup"
    :aria-label="currentItem?.text"
    data-testid="answer-options"
  >
    <div class="RadioField" v-for="option in options" :key="option.id">
      <input
        class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input"
        :class="{ 
          'TextField--dirty': modelValue === option.value,
          'TextField--disabled': disabled
        }"
        :id="option.id" 
        :name="currentItem?.id"
        :aria-label="option.text"
        :data-qa-id="option.id" 
        :data-testid="option.id" 
        type="radio"
        :value="option.value"
        :checked="modelValue === option.value"
        :disabled="disabled"
        @change="handleChange($event, option)" 
      />
      <label 
        class="RadioField--label" 
        :for="option.id"
        :class="{ 
          'disabled': disabled,
          'active': modelValue === option.value
        }"
      >
        <span class="RadioField--label__inner">
          <i v-if="modelValue === option.value" class="fas fa-check" aria-hidden="true"></i>
          {{ option.text }}
        </span>
      </label>
    </div>
  </div>
</template>

<script setup>
import { computed, watch, onMounted, ref } from 'vue';
import { useHollandCodeStore } from '@/stores/hollandCodeStore';

const hollandCodeStore = useHollandCodeStore();

const props = defineProps({
  options: {
    type: Array,
    required: true,
    default: () => []
  },
  testStage: {
    type: String,
    required: true
  },
  modelValue: {
    type: [String, Number],
    default: null
  },
  currentItem: {
    type: Object,
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  },
  store: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update:modelValue', 'submit']);

const handleChange = (event, option) => {
  console.log('Option change:', { 
    optionId: option.id,
    optionValue: option.value,
    currentItem: props.currentItem,
    currentResponse: props.store.responses[props.currentItem?.id]
  });
  
  emit('update:modelValue', option.value);
  emit('submit');
};

// Watch for store responses changes
watch(() => props.store.responses[props.currentItem?.id], (newResponse) => {
  console.log('Store response changed:', {
    itemId: props.currentItem?.id,
    newResponse,
    allResponses: props.store.responses
  });
  
  if (newResponse !== undefined) {
    emit('update:modelValue', newResponse);
  } else {
    emit('update:modelValue', null);
  }
}, { immediate: true });

onMounted(() => {
  console.log('AnswerOptions mounted:', {
    currentItem: props.currentItem,
    modelValue: props.modelValue,
    storeResponses: props.store.responses,
    currentResponse: props.store.responses[props.currentItem?.id]
  });
  
  // Set initial value from store response
  const response = props.store.responses[props.currentItem?.id];
  if (response !== undefined) {
    emit('update:modelValue', response);
  }
});
</script>
