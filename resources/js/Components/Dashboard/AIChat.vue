<template>
  <!-- Desktop View -->
  <div class="hidden md:block fixed bottom-4 right-4 z-50">
    <!-- Enhanced Chat Toggle Button -->
    <button
      @click="isOpen = !isOpen"
      class="group flex items-center space-x-3 bg-violet-500 hover:bg-violet-600 text-white px-4 py-3 rounded-full shadow-lg transition-all duration-200 hover:shadow-violet-500/25"
      :class="{ 
        'rounded-full': !isOpen, 
        'rounded-t-xl rounded-b-none': isOpen,
        'shadow-lg': !isOpen,
        'shadow-xl hover:shadow-violet-500/50': isOpen
      }"
    >
      <!-- Icon -->
      <svg 
        xmlns="http://www.w3.org/2000/svg" 
        class="h-5 w-5 transition-transform group-hover:scale-110" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
        />
      </svg>

      <!-- Text -->

    </button>

    <!-- Desktop Chat Window -->
    <div
      v-show="isOpen"
      class="bg-stone-950 rounded-xl border border-stone-700 flex flex-col w-[400px] h-[600px] absolute bottom-16 right-0 shadow-2xl transition-all duration-200"
    >
      <div class="p-6 border-b border-stone-700">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-3 h-3 bg-violet-500 rounded-full animate-pulse"></div>
            <h2 class="text-xl font-semibold text-stone-100">{{ __('dashboard.ai_career_guide') }}</h2>
          </div>
          <button
            @click="isOpen = false"
            class="text-stone-400 hover:text-stone-200 transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <ChatMessages :messages="localMessages" :is-loading="isLoading" ref="chatContainer" />

      <ChatInput
        v-model="form.message"
        :is-loading="isLoading"
        :show-questions="showQuestions"
        :predefined-questions="predefinedQuestions"
        @send="sendMessage"
        @select-question="selectQuestion"
        @toggle-questions="toggleQuestions"
      />
    </div>
  </div>

  <!-- Mobile View -->
  <div class="md:hidden">
    <!-- Mobile Toggle Button -->
    <button
    @click="isOpen = !isOpen"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    class="group fixed bottom-20 right-4 z-50 p-4 rounded-full transition-all duration-300
           bg-gradient-to-br from-violet-500 to-violet-600
           hover:from-violet-400 hover:to-violet-600
           shadow-lg hover:shadow-xl
           hover:-translate-y-0.5
           active:translate-y-0.5
           overflow-hidden"
  >
    <!-- Shine effect overlay -->
    <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100
                bg-gradient-to-br from-white/20 to-transparent
                transition-opacity duration-300" />
    
    <!-- 3D bottom edge -->
    <div class="absolute -bottom-1 left-0 right-0 h-2
                bg-violet-800/50 rounded-full
                transform transition-all duration-200
                group-active:scale-y-0" />
    
    <!-- Glow effect -->
    <div class="absolute -inset-1 bg-violet-500/20 rounded-full blur-lg
                opacity-0 group-hover:opacity-100
                transition-opacity duration-300" />
    
    <!-- Main icon -->
    <svg 
      xmlns="http://www.w3.org/2000/svg" 
      class="relative h-6 w-6 text-white transform transition-transform duration-300
             group-hover:scale-110 group-active:scale-95"
      fill="none" 
      viewBox="0 0 24 24" 
      stroke="currentColor"
    >
      <path 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        stroke-width="2" 
        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
      />
    </svg>

    <!-- Ripple particles -->
    <div class="absolute inset-0 pointer-events-none">
      <div v-for="i in 3" :key="i"
           class="absolute h-2 w-2 rounded-full bg-white/40
                  animate-ping opacity-0 group-hover:opacity-100"
           :style="{
             top: `${Math.random() * 100}%`,
             left: `${Math.random() * 100}%`,
             animationDelay: `${i * 0.2}s`,
             animationDuration: '1.5s'
           }" />
    </div>
  </button>
    

    <!-- Mobile Chat Window -->
    <div
      v-show="isOpen"
      class="fixed inset-0 z-[100] bg-stone-950 flex flex-col"
    >
      <!-- Mobile Header -->
      <div class="px-4 py-3 border-b border-stone-700 bg-stone-900">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-violet-500 rounded-full animate-pulse"></div>
            <h2 class="text-lg font-semibold text-stone-100">{{ __('dashboard.ai_career_guide') }}</h2>
          </div>
          <button
            @click="isOpen = false"
            class="p-2 text-stone-400 hover:text-stone-200 transition-colors duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Messages -->
      <div class="flex-1 overflow-y-auto pb-16">
        <ChatMessages :messages="localMessages" :is-loading="isLoading" ref="chatContainer" />
      </div>

      <!-- Mobile Input - Adjusted positioning -->
      <div class="fixed bottom-0 left-0 right-0 border-t border-stone-700 bg-stone-900 z-[101]">
        <ChatInput
          v-model="form.message"
          :is-loading="isLoading"
          :show-questions="showQuestions"
          :predefined-questions="predefinedQuestions"
          @send="sendMessage"
          @select-question="selectQuestion"
          @toggle-questions="toggleQuestions"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, watch, onMounted } from 'vue';
import axios from 'axios';
import ChatMessages from './ChatMessages.vue';
import ChatInput from './ChatInput.vue';

const props = defineProps({
  predefinedQuestions: {
    type: Array,
    required: true
  },
  initialMessages: {
    type: Array,
    default: () => []
  }
});

const localMessages = ref([]);
const showQuestions = ref(false);
const chatContainer = ref(null);
const isLoading = ref(false);
const form = ref({
  message: ''
});

// Add isOpen ref for controlling popup visibility
const isOpen = ref(false);

// Initialize messages with chat history
onMounted(() => {
  localMessages.value = props.initialMessages;
});

// Watch for changes in initialMessages prop
watch(() => props.initialMessages, (newMessages) => {
  localMessages.value = newMessages;
}, { deep: true });

watch(localMessages, () => {
  nextTick(() => {
    scrollToBottom();
  });
}, { deep: true });

const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollToBottom();
  }
};

const selectQuestion = (question) => {
  form.value.message = question;
  showQuestions.value = false;
};

const sendMessage = async () => {
  if (!form.value.message.trim()) return;

  const userMessage = form.value.message;
  form.value.message = '';

  localMessages.value.push({
    role: 'user',
    content: userMessage,
    timestamp: new Date().toLocaleTimeString(),
  });

  isLoading.value = true;

  try {
    const response = await axios.post('/chat', {
      message: userMessage
    });

    if (response.data.success) {
      localMessages.value.push({
        role: 'assistant',
        content: response.data.aiMessage,
        timestamp: new Date().toLocaleTimeString(),
      });
    }
  } catch (error) {
    console.error('Error sending message:', error);
    localMessages.value.push({
      role: 'system',
      content: 'Sorry, there was an error sending your message.',
      timestamp: new Date().toLocaleTimeString(),
    });
  } finally {
    isLoading.value = false;
  }

  await nextTick();
  scrollToBottom();
};

const toggleQuestions = (value) => {
  showQuestions.value = value;
};

watch(isOpen, (newValue) => {
  if (typeof document !== 'undefined') {
    if (newValue) {
      document.body.classList.add('chat-open');
    } else {
      document.body.classList.remove('chat-open');
    }
  }
});
</script>

<style scoped>
/* Prevent body scroll when chat is open on mobile */
:deep(body.chat-open) {
  overflow: hidden;
}
/* Optional: Custom animation for the ripple effect */
@keyframes ping {
  0% {
    transform: scale(0.2);
    opacity: 0.6;
  }
  70%, 100% {
    transform: scale(2);
    opacity: 0;
  }
}

.animate-ping {
  animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

</style>