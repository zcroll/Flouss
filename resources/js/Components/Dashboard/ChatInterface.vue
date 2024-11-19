<template>
  <div class="chat-interface bg-white shadow-xl rounded-lg p-4 h-[600px] flex flex-col">
    <div class="flex-1 overflow-y-auto mb-4" ref="chatContainer">
      <div v-for="(message, index) in messages" :key="index" class="mb-4">
        <div :class="[
          'rounded-lg p-3 max-w-[80%]',
          message.isUser ? 'bg-blue-100 ml-auto' : 'bg-gray-100'
        ]">
          <p class="text-sm" v-html="message.content"></p>
        </div>
      </div>
      <div v-if="isLoading" class="flex items-center space-x-2 mb-4">
        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
      </div>
    </div>

    <div class="relative">
      <div v-if="showQuestions" class="absolute bottom-full left-0 w-full bg-white border rounded-lg shadow-lg mb-2 max-h-48 overflow-y-auto">
        <div
          v-for="question in predefinedQuestions"
          :key="question"
          class="p-2 hover:bg-gray-100 cursor-pointer"
          @click="handleQuestionSelect(question)"
        >
          {{ question }}
        </div>
      </div>

      <div class="flex space-x-2">
        <button
          type="button"
          class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none"
          @click="showQuestions = !showQuestions"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
        </button>

        <input
          v-model="messageInput"
          type="text"
          class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Type your message..."
          @keyup.enter="handleSendMessage"
        />

        <button
          type="button"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none disabled:opacity-50"
          :disabled="isLoading || !messageInput.trim()"
          @click="handleSendMessage"
        >
          Send
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';

const props = defineProps({
  messages: {
    type: Array,
    required: true
  },
  predefinedQuestions: {
    type: Array,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  },
  aiMessage: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['send-message', 'select-question']);
const messageInput = ref('');
const showQuestions = ref(false);
const chatContainer = ref(null);

function handleQuestionSelect(question) {
  emit('select-question', question);
}

function handleSendMessage() {
  if (!messageInput.value.trim() || props.isLoading) return;
  
  emit('send-message', messageInput.value);
  messageInput.value = '';
  showQuestions.value = false;
}

function scrollToBottom() {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
}

onMounted(() => {
  scrollToBottom();
  document.addEventListener('click', handleClickOutside);
});

function handleClickOutside(event) {
  const container = document.querySelector('.chat-interface');
  if (container && !container.contains(event.target)) {
    showQuestions.value = false;
  }
}
</script>

<style scoped>
.chat-interface {
  height: 100%;
}
</style>
