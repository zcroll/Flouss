<template>
  <div class="flex-1 overflow-y-auto p-6 custom-scrollbar" ref="messagesContainer">
    <div class="space-y-6">
      <template v-for="(message, index) in messages" :key="index">
        <!-- AI Message -->
        <div v-if="message.role === 'assistant'" class="flex items-start space-x-3"
          :class="{ 'animate-fade-in': true }">
          <div class="w-8 h-8 rounded-full bg-amber-500/20 flex-shrink-0 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-2 mb-1">
              <span class="text-amber-400 text-sm font-medium">AI Assistant</span>
              <span class="text-stone-500 text-xs">•</span>
              <span class="text-stone-500 text-xs">{{ message.timestamp }}</span>
            </div>
            <div class="bg-black/50 rounded-2xl p-4 border border-stone-800 backdrop-blur-sm break-words">
              <p class="text-sm text-stone-300 leading-relaxed whitespace-pre-wrap">{{ message.content }}</p>
            </div>
          </div>
        </div>

        <!-- User Message -->
        <div v-else-if="message.role === 'user'" class="flex items-start justify-end space-x-3"
          :class="{ 'animate-fade-in': true }">
          <div class="flex-1 max-w-[80%] min-w-0">
            <div class="flex items-center justify-end space-x-2 mb-1">
              <span class="text-stone-500 text-xs">{{ message.timestamp }}</span>
              <span class="text-stone-500 text-xs">•</span>
              <span class="text-stone-300 text-sm font-medium">You</span>
            </div>
            <div class="bg-amber-500/10 rounded-2xl p-4 border border-amber-500/20 break-words">
              <p class="text-sm text-stone-300 leading-relaxed whitespace-pre-wrap">{{ message.content }}</p>
            </div>
          </div>
        </div>
      </template>

      <!-- Loading Skeleton -->
      <div v-if="isLoading" class="flex items-start space-x-3 animate-pulse">
        <div class="w-8 h-8 rounded-full bg-stone-800"></div>
        <div class="flex-1">
          <div class="h-5 bg-stone-800 rounded w-24 mb-2"></div>
          <div class="space-y-2">
            <div class="h-4 bg-stone-800 rounded w-3/4"></div>
            <div class="h-4 bg-stone-800 rounded w-1/2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
  messages: {
    type: Array,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  }
});

const messagesContainer = ref(null);

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};

onMounted(() => {
  scrollToBottom();
});

watch(() => props.messages, () => {
  scrollToBottom();
}, { deep: true });

defineExpose({
  scrollToBottom
});
</script>

<style scoped>
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: theme('colors.stone.700') theme('colors.stone.900');
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: theme('colors.stone.900');
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: theme('colors.stone.700');
  border-radius: 3px;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out forwards;
}
</style> 