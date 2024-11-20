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
        :checked="previousAnswers[currentItem.id] === option.value"
        :model-value="modelValue"
        @change="$emit('update:modelValue', $event.target.value); $emit('submit')"
        @click="$emit('update:modelValue', option.value); $emit('submit')" />
      <label class="RadioField--label" :for="option.id">
        <span class="RadioField--label__inner">{{ option.label }}</span>
      </label>
    </div>
  </div>
</template>

<script setup>
import __ from '@/lang';
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

const options = [
  { id: 'love-it', value: 5, label: __('test.love_it') },
  { id: 'like-it', value: 2, label: __('test.like_it') },
  { id: 'neutral', value: 3, label: __('test.neutral') },
  { id: 'dislike-it', value: 4, label: __('test.dislike_it') },
  { id: 'hate-it', value: 1, label: __('test.hate_it') }
];

defineEmits(['update:modelValue', 'submit']);
</script>
