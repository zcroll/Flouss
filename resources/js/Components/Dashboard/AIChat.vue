<template>
  <!-- Desktop View -->
  <div class="hidden md:block fixed bottom-4 right-4 z-50">
    <!-- Chat Toggle Button -->
    <button
      @click="isOpen = !isOpen"
      class="group flex items-center gap-2 bg-white/80 backdrop-blur-xl hover:bg-white/90 text-gray-700 px-4 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all duration-300"
      :class="{ 
        'rounded-full': !isOpen, 
        'rounded-t-xl rounded-b-none': isOpen
      }"
    >
      <div class="h-6 w-6 bg-yellow-400 rounded-full flex items-center justify-center transform group-hover:rotate-45 transition-transform duration-300">
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-3.5 w-3.5 text-white transition-transform group-hover:scale-110" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </div>
      <span class="text-sm font-medium">Chat with AI</span>
    </button>

    <!-- Chat Window -->
    <div
      v-show="isOpen"
      class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/20 flex flex-col w-[400px] h-[600px] absolute bottom-16 right-0 shadow-lg transition-all duration-300 overflow-hidden"
    >
      <!-- Header -->
      <div class="p-4 border-b border-white/20 bg-white/40 backdrop-blur-sm">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="relative">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></div>
            </div>
            <div>
              <h2 class="text-sm font-medium text-gray-700">AI Career Guide</h2>
              <p class="text-xs text-gray-500">Always here to help</p>
            </div>
          </div>
          <button
            @click="isOpen = false"
            class="h-8 w-8 bg-white/60 hover:bg-white/80 rounded-full flex items-center justify-center transition-colors duration-200"
          >
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Messages Container -->
      <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-4">
        <ChatMessages 
          :messages="localMessages" 
          :is-loading="isLoading" 
          ref="chatContainer"
        />
      </div>

      <!-- Input Section -->
      <div class="border-t border-white/20 p-4 bg-white/60 backdrop-blur-sm">
        <ChatInput
          v-model="form.message"
          :is-loading="isLoading"
          :show-questions="showQuestions"
          :predefined-questions="predefinedQuestions"
          @send="sendMessage"
          @select-question="selectQuestion"
          @toggle-questions="toggleQuestions"
          class="bg-white/80 rounded-xl shadow-sm"
        />
      </div>
    </div>
  </div>

  <!-- Mobile View -->
  <div class="md:hidden">
    <!-- Mobile Toggle -->
    <button
      @click="isOpen = !isOpen"
      class="fixed bottom-20 right-4 z-50 bg-white/60 backdrop-blur-xl p-3 rounded-full shadow-sm hover:shadow-md transition-all duration-300"
    >
      <div class="h-5 w-5 bg-yellow-400 rounded-full flex items-center justify-center">
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-3.5 w-3.5 text-white" 
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
      </div>
    </button>

    <!-- Mobile Chat Window -->
    <div
      v-show="isOpen"
      class="fixed inset-0 z-[100] bg-white/60 backdrop-blur-xl flex flex-col"
    >
      <!-- Mobile Header -->
      <div class="px-4 py-3 border-b border-white/20 bg-white/40">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
            <h2 class="text-sm font-medium text-gray-700">AI Career Guide</h2>
          </div>
          <button
            @click="isOpen = false"
            class="h-5 w-5 bg-yellow-400/20 rounded-full flex items-center justify-center"
          >
            <svg class="w-3.5 h-3.5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Messages -->
      <div class="flex-1 overflow-y-auto custom-scrollbar">
        <ChatMessages :messages="localMessages" :is-loading="isLoading" ref="chatContainer" />
      </div>

      <!-- Mobile Input -->
      <div class="border-t border-white/20 p-4 bg-white/40 backdrop-blur-sm">
        <ChatInput
          v-model="form.message"
          :is-loading="isLoading"
          :show-questions="showQuestions"
          :predefined-questions="predefinedQuestions"
          @send="sendMessage"
          @select-question="selectQuestion"
          @toggle-questions="toggleQuestions"
          class="bg-white/60 rounded-lg"
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
/* Custom scrollbar styling */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(250, 204, 21, 0.3);
  border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(250, 204, 21, 0.5);
}

/* Message animations */
.message-enter-active,
.message-leave-active {
  transition: all 0.3s ease;
}

.message-enter-from,
.message-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

/* Prevent body scroll when chat is open on mobile */
:deep(body.chat-open) {
  overflow: hidden;
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>