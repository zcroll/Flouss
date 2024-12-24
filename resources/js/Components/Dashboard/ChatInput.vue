<template>
  <div class="relative">
    <!-- Input Field -->
    <div class="relative flex items-end gap-2">
      <div class="flex-1 relative">
        <textarea
          v-model="localValue"
          rows="1"
          class="w-full resize-none bg-transparent px-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
          :class="{ 'opacity-50': isLoading }"
          placeholder="Type your message..."
          @keydown.enter.prevent="handleEnter"
          @input="autoGrow"
          ref="textarea"
          :disabled="isLoading"
        ></textarea>
      </div>

      <!-- Send Button -->
      <button
        @click="handleSend"
        :disabled="isLoading || !localValue.trim()"
        class="flex-shrink-0 p-2 rounded-full bg-yellow-400 hover:bg-yellow-500 disabled:opacity-50 disabled:hover:bg-yellow-400 transition-colors duration-200"
      >
        <svg
          class="w-5 h-5 text-white transform rotate-90"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
          />
        </svg>
      </button>
    </div>

    <!-- Predefined Questions -->
    <div v-if="showQuestions" 
         class="absolute bottom-full left-0 right-0 mb-2 bg-white/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/20 overflow-hidden">
      <div class="p-2 space-y-1">
        <button
          v-for="question in predefinedQuestions"
          :key="question"
          @click="$emit('select-question', question)"
          class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-yellow-400/10 rounded-lg transition-colors duration-200"
        >
          {{ question }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: String,
  isLoading: Boolean,
  showQuestions: Boolean,
  predefinedQuestions: Array
});

const emit = defineEmits(['update:modelValue', 'send', 'select-question']);

const localValue = ref(props.modelValue || '');
const textarea = ref(null);

watch(() => props.modelValue, (newValue) => {
  localValue.value = newValue;
});

watch(localValue, (newValue) => {
  emit('update:modelValue', newValue);
});

const handleSend = () => {
  if (localValue.value.trim() && !props.isLoading) {
    emit('send');
  }
};

const handleEnter = (e) => {
  if (!e.shiftKey) {
    handleSend();
  }
};

const autoGrow = () => {
  if (textarea.value) {
    textarea.value.style.height = 'auto';
    textarea.value.style.height = textarea.value.scrollHeight + 'px';
  }
};
</script> 