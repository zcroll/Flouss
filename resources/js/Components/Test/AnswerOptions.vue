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
        v-model="selectedValue"
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
const selectedValue = ref(null);

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
  const value = parseInt(option.value);
  selectedValue.value = value;
  emit('update:modelValue', value);
  emit('submit');
};

// Watch for changes in current item
watch(() => props.currentItem?.id, () => {
  if (props.currentItem?.id && props.store?.responses) {
    const response = props.store.responses[props.currentItem.id];
    if (response) {
      selectedValue.value = response;
      emit('update:modelValue', response);
    } else {
      selectedValue.value = null;
      emit('update:modelValue', null);
    }
  }
}, { immediate: true });

// Watch for model value changes
watch(() => props.modelValue, (newValue) => {
  selectedValue.value = newValue;
}, { immediate: true });
</script>
