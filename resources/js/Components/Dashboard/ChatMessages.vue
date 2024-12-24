<template>
  <div class="flex flex-col space-y-4">
    <!-- Message Bubbles -->
    <div v-for="(message, index) in messages" 
         :key="index"
         class="flex flex-col"
         :class="[
           message.role === 'user' ? 'items-end' : 'items-start',
         ]">
      <!-- Message Content -->
      <div class="flex items-end gap-2 max-w-[80%]"
           :class="[
             message.role === 'user' ? 'flex-row-reverse' : 'flex-row'
           ]">
        <!-- Avatar -->
        <div v-if="message.role !== 'user'" 
             class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
        </div>
        <div v-else
             class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>

        <!-- Message Bubble -->
        <div class="flex flex-col space-y-1">
          <div class="px-4 py-2 rounded-2xl text-sm"
               :class="[
                 message.role === 'user' 
                   ? 'bg-yellow-400 text-white rounded-br-none' 
                   : 'bg-white/80 backdrop-blur-sm text-gray-700 rounded-bl-none shadow-sm'
               ]">
            {{ message.content }}
          </div>
          <span class="text-xs text-gray-400 px-2"
                :class="[
                  message.role === 'user' ? 'text-right' : 'text-left'
                ]">
            {{ message.timestamp }}
          </span>
        </div>
      </div>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="flex items-center space-x-2">
      <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center animate-pulse">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
      </div>
      <div class="flex space-x-1">
        <div class="w-2 h-2 bg-yellow-400/60 rounded-full animate-bounce" 
             style="animation-delay: 0ms"></div>
        <div class="w-2 h-2 bg-yellow-400/60 rounded-full animate-bounce" 
             style="animation-delay: 100ms"></div>
        <div class="w-2 h-2 bg-yellow-400/60 rounded-full animate-bounce" 
             style="animation-delay: 200ms"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  messages: {
    type: Array,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  }
});

const scrollToBottom = () => {
  const container = document.querySelector('.messages-container');
  if (container) {
    container.scrollTop = container.scrollHeight;
  }
};

defineExpose({
  scrollToBottom
});
</script>

<style scoped>
.messages-container {
  scroll-behavior: smooth;
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

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-4px);
  }
}

.animate-bounce {
  animation: bounce 1s infinite;
}
</style> 