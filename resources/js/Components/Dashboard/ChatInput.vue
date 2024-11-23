<template>
  <div class="p-4 border-t border-stone-700">
    <form @submit.prevent="$emit('send')">
      <div class="flex items-center gap-2">
        <div class="relative flex-1">
          <input 
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            @focus="handleFocus"
            type="text"
            class="w-full h-10 px-4 bg-stone-900 text-stone-100 rounded-xl border border-stone-700 focus:border-violet-700 focus:ring-1 focus:ring-purple-950 transition-colors pl-10"
            :placeholder="__('dashboard.type_your_message')"
            :disabled="isLoading" 
          />
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg v-if="!isLoading" class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
          </div>

          <!-- Predefined Questions Dropdown -->
          <div v-show="showQuestions && !isLoading" 
               ref="dropdown"
               class="absolute bottom-full left-0 right-0 mb-2 bg-stone-900/95 backdrop-blur-sm rounded-xl border border-stone-700 shadow-lg max-h-[400px] overflow-y-auto transform transition-all duration-200 ease-out z-50"
               :class="{ 'opacity-0 scale-95': !showQuestions, 'opacity-100 scale-100': showQuestions }">
            <div class="p-4">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-stone-300 font-medium">Suggested Questions</h3>
                <button @click="closeQuestions" type="button" class="p-1 hover:bg-stone-800 rounded-lg transition-colors">
                  <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-for="category in predefinedQuestions" :key="category.category" class="mb-6 last:mb-0">
                <div class="flex items-center space-x-2 mb-3">
                  <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="category.icon" />
                  </svg>
                  <h3 class="text-stone-300 font-medium">{{ category.category }}</h3>
                </div>
                <div class="space-y-2 ml-7">
                  <button v-for="question in category.questions" :key="question"
                    @click="handleQuestionSelect(question)"
                    type="button"
                    class="w-full text-left p-3 text-stone-400 hover:text-stone-200 hover:bg-stone-800/50 rounded-lg transition-all duration-200 text-sm group">
                    <div class="flex items-center justify-between">
                      <span>{{ question }}</span>
                      <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity text-violet-500"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button type="submit"
          class="h-10 w-10 flex items-center justify-center bg-violet-500 hover:bg-violet-600 disabled:bg-stone-700 text-stone-900 disabled:text-stone-400 rounded-xl transition-all duration-200 disabled:cursor-not-allowed"
          :disabled="!modelValue.trim() || isLoading">
          <svg v-if="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  },
  showQuestions: {
    type: Boolean,
    default: false
  },
  predefinedQuestions: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['update:modelValue', 'send', 'select-question', 'toggle-questions']);
const dropdown = ref(null);

const handleClickOutside = (event) => {
  if (props.showQuestions && 
      dropdown.value && 
      !dropdown.value.contains(event.target) && 
      !event.target.closest('input')) {
    emit('toggle-questions', false);
  }
};

const handleFocus = () => {
  emit('toggle-questions', true);
};

const closeQuestions = () => {
  emit('toggle-questions', false);
};

const handleQuestionSelect = (question) => {
  emit('select-question', question);
  closeQuestions();
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script> 