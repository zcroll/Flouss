<template>
  <div class="bg-stone-950 rounded-2xl border border-stone-700 flex flex-col h-[600px]">
    <div class="p-6 border-b border-stone-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></div>
          <h2 class="text-xl font-semibold text-stone-100">AI Career Guide</h2>
        </div>
      </div>
    </div>

    <ChatMessages :messages="localMessages" :is-loading="isLoading" ref="chatContainer" />

    <ChatInput v-model="form.message" :is-loading="isLoading" :show-questions="showQuestions"
      :predefined-questions="predefinedQuestions" @send="sendMessage" @select-question="selectQuestion"
      @toggle-questions="showQuestions = !showQuestions" />
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
</script>