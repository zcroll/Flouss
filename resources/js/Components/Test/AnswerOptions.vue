<template>
  <div class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant" data-testid="answer-options">
    <div class="RadioField" v-for="option in options" :key="option.id">
      <input
        class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input TextField--dirty"
        :id="option.id" 
        tabindex="-1" 
        :data-qa-id="option.id" 
        :data-testid="option.id" 
        autocomplete="on" 
        type="radio"
        :value="option.value" 
        :checked="modelValue === option.value"
        @change="handleChange($event, option)" 
      />
      <label class="RadioField--label" :for="option.id">
        <span class="RadioField--label__inner">{{ option.text }}</span>
      </label>
    </div>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';

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
  }
});

const emit = defineEmits(['update:modelValue', 'submit']);

const handleChange = (event, option) => {
  const value = parseInt(option.value);
  emit('update:modelValue', value);
  emit('submit');
};

// Watch for changes in current item to reset selection
watch(() => props.currentItem?.id, () => {
  emit('update:modelValue', null);
}, { immediate: true });

// Watch for invalid model values
watch(() => props.modelValue, (newValue) => {
  if (newValue !== null && !props.options.some(opt => opt.value === newValue)) {
    emit('update:modelValue', null);
  }
}, { immediate: true });
</script>
