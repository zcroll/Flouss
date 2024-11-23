<template>
  <div :id="'radio-item-' + currentItem.id"
    class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant"
    :data-value-selected="modelValue"
    data-testid="">
    <div class="RadioField"
      :aria-labelledby="'radio-item-' + currentItem.id"
      v-for="option in options"
      :key="option.id">
      <input class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input TextField--dirty"
        :id="option.id"
        :name="'radio-item-' + currentItem.id"
        tabindex="-1"
        :data-qa-id="option.id"
        :data-testid="option.id"
        autocomplete="on"
        type="radio"
        :value="option.value"
        :checked="modelValue === option.value"
        v-model="selectedValue"
        @click="handleClick(option.value)" />
      <label class="RadioField--label" :for="option.id">
        <span class="RadioField--label__inner">{{ option.label }}</span>
      </label>
    </div>
  </div>
</template>

<script setup>
import __ from '@/lang';
import { computed, ref, watch } from 'vue';

const props = defineProps({
  currentItem: {
    type: Object,
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
  modelValue: {
    type: [String, Number],
    default: null
  }
});

const emit = defineEmits(['update:modelValue', 'submit', 'change']);

const selectedValue = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
  selectedValue.value = newValue;
});

const handleClick = (value) => {
  // Always emit the value on click, even if it's the same as before
  selectedValue.value = value;
  emit('update:modelValue', value);
  emit('change', value);
  emit('submit');
};

const options = [
  { id: 'love-it', value: 5, label: __('test.love_it') },
  { id: 'like-it', value: 4, label: __('test.like_it') },
  { id: 'neutral', value: 3, label: __('test.neutral') },
  { id: 'dislike-it', value: 2, label: __('test.dislike_it') },
  { id: 'hate-it', value: 1, label: __('test.hate_it') }
];
</script>
