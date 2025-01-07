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
          'TextField--disabled': disabled || isAnimating
        }"
        :id="option.id" 
        :name="currentItem?.id"
        :aria-label="option.text"
        :data-qa-id="option.id" 
        :data-testid="option.id" 
        type="radio"
        :value="option.value"
        :checked="modelValue === option.value"
        :disabled="disabled || isAnimating"
        @change="handleChange($event, option)" 
      />
      <label 
        class="RadioField--label" 
        :for="option.id"
        :class="{ 
          'disabled': disabled || isAnimating,
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
const isAnimating = ref(false);

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

const handleChange = async (event, option) => {
  if (isAnimating.value) return;
  
  isAnimating.value = true;
  
  emit('update:modelValue', option.value);
  await new Promise(resolve => setTimeout(resolve, 50)); // Small delay for state update
  emit('submit');
  
  setTimeout(() => {
    isAnimating.value = false;
  }, 500); // Match animation duration
};

// Watch for store responses changes
watch(() => props.store.responses[props.currentItem?.id], (newResponse) => {
  if (newResponse !== undefined) {
    emit('update:modelValue', newResponse);
  } else {
    emit('update:modelValue', null);
  }
}, { immediate: true });

onMounted(() => {
  const response = props.store.responses[props.currentItem?.id];
  if (response !== undefined) {
    emit('update:modelValue', response);
  }
});
</script>

<style scoped>
.RadioFieldCollection {
    /* Force hardware acceleration */
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000px;
}

.RadioField {
    /* Optimize transitions */
    transform: translate3d(0,0,0);
    transition: opacity 0.2s ease;
    will-change: opacity;
}

.RadioField--label {
    transition: opacity 0.2s ease;
    /* Force GPU rendering */
    transform: translateZ(0);
    backface-visibility: hidden;
}

.RadioField--label.disabled {
    opacity: 0.5;
    pointer-events: none;
}

/* Optimize input state changes */
.TextField--radiofield {
    transition: none; /* Remove transition for better performance */
}

.TextField--dirty {
    transition: none;
}
</style>
