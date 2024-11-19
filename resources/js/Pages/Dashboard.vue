<template>
  <AppLayout  title="dashboard">
    <div class="min-h-screen">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <!-- Welcome Banner -->
        <div class="bg-stone-950 rounded-2xl p-8 mb-4 border border-stone-700">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h1 class="text-4xl font-bold text-stone-100">
                {{ __('dashboard.welcome') }} {{ $page.props.auth.user.name }}!
              </h1>
              <p class="mt-2 text-lg text-stone-400">
                {{ __('dashboard.orientation_process') }}
              </p>
            </div>
            <div class="mt-4 md:mt-0">
              <Link v-if="!hasResult" href="/main-test"
                class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-stone-900 font-medium rounded-lg transition-colors duration-200">
              {{ __('dashboard.begin_assessment') }}
              <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
              </Link>
            </div>
          </div>
        </div>
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <!-- Liked Jobs -->
          <div class="bg-stone-950 rounded-2xl p-6 border border-stone-700">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_jobs') }}</h2>
              <span class="px-4 py-2 bg-stone-700 rounded-full text-sm text-stone-300">
                {{ favoriteJobsCount }}
              </span>
            </div>
            <div v-if="favoriteJobsCount > 0"
              class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
              <div v-for="job in favoriteJobs" :key="job.id"
                class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-4 transition-all duration-300">
                <Link :href="`/career/${job.slug}`" class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-medium text-stone-100">{{ job.name }}</h3>
                  <p class="text-sm text-stone-400">Career Path</p>
                </div>
                <button @click.prevent="removeFavorite(job.id)"
                  class="opacity-0 group-hover:opacity-100 p-2 hover:bg-stone-600 rounded-full transition-all">
                  <svg class="w-5 h-5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
                </Link>
              </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center py-8 text-center">
              <svg class="w-16 h-16 text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <p class="text-stone-400 mb-2">{{ __('dashboard.no_liked_jobs') }}</p>
              <Link href="/jobs" class="text-amber-500 hover:text-amber-400 text-sm">
              {{ __('dashboard.discover_jobs') }} →
              </Link>
            </div>
          </div>

          <!-- Liked Formations -->
          <div class="bg-stone-950 rounded-2xl p-4 sm:p-6 border border-stone-700">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
              <h2 class="text-xl sm:text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_formations') }}</h2>
              <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-stone-700 rounded-full text-xs sm:text-sm text-stone-300">
                {{ favoriteDegreesCount }}
              </span>
            </div>
            <div v-if="favoriteDegreesCount > 0"
              class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
              <div v-for="degree in favoriteDegrees" :key="degree.id"
                class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-3 sm:p-4 transition-all duration-300">
                <Link :href="`/degree/${degree.slug}`" class="flex items-center justify-between">
                <div>
                  <h3 class="text-base sm:text-lg font-medium text-stone-100">{{ degree.name }}</h3>
                  <p class="text-xs sm:text-sm text-stone-400">Education Path</p>
                </div>
                <button @click.prevent="removeFavorite(degree.id)"
                  class="opacity-0 group-hover:opacity-100 p-1.5 sm:p-2 hover:bg-stone-600 rounded-full transition-all">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
                </Link>
              </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center py-6 sm:py-8 text-center">
              <svg class="w-12 h-12 sm:w-16 sm:h-16 text-stone-600 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21l9-5-9-5-9 5 9 5z" />
              </svg>
              <p class="text-sm sm:text-base text-stone-400 mb-2">{{ __('dashboard.no_liked_formations') }}</p>
              <Link href="/degrees" class="text-xs sm:text-sm text-amber-500 hover:text-amber-400">
              {{ __('dashboard.discover_formations') }} →
              </Link>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Assessment Results Card -->
          <div v-if="hasResult" class="lg:col-span-2 bg-stone-950 rounded-2xl p-6 border border-stone-700">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.your_assessment_results') }}</h2>
              <Link href="/results"
                class="px-3 py-1.5 text-sm bg-white hover:bg-stone-300/30 hover:text-white text-stone-900 rounded-lg transition-colors duration-200 w-auto text-center">
              {{ __('dashboard.view_full_results') }}
              </Link>
            </div>

            <!-- Archetype Card -->
            <div
              class="bg-[#353535] rounded-2xl p-4 sm:p-8 mb-6 shadow-2xl hover:shadow-amber-500/5 transition-all duration-500 border border-stone-700/50 relative overflow-hidden"
              style="background-image: url('https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607545512/dashboard-sharing-bg-lg_ngkdfq.png'); background-size: cover; background-position: center;">
              <div class="absolute top-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-amber-500/5 rounded-full blur-3xl -mr-24 sm:-mr-32 -mt-24 sm:-mt-32"></div>
              <div class="absolute bottom-0 left-0 w-48 sm:w-64 h-48 sm:h-64 bg-stone-600/5 rounded-full blur-3xl -ml-24 sm:-ml-32 -mb-24 sm:-mb-32"></div>

              <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6 relative">
                <div class="flex items-center gap-4">

                  <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                      <h3
                        class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent tracking-tight hover:scale-105 transition-transform duration-300">
                        {{ archetype?.slug || 'No Archetype' }}
                      </h3>
                      <span
                        class="px-2 sm:px-3 py-1 text-xs font-semibold text-amber-400 bg-amber-400/10 rounded-full border border-amber-400/20">
                        {{ archetype?.rarity_string || 'Unknown' }}
                      </span>
                    </div>
                    <div class="flex items-center gap-2">
                      <svg class="w-3 h-3 sm:w-4 sm:h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                      </svg>
                      <span class="text-stone-400 text-xs sm:text-sm font-medium">Personality Type</span>
                    </div>
                    <p class="text-sm sm:text-base text-stone-300 font-bold leading-relaxed tracking-wide">
                      {{ archetype?.rationale || 'No rationale available' }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 pt-4 sm:pt-6">
                <div v-for="(scale, index) in parsedScales" :key="scale.scale_id"
                  class="bg-black rounded-xl p-3 sm:p-6 transition-all duration-300 border border-stone-700 hover:border-amber-500/30 group">
                  <div class="flex justify-between items-center mb-3 sm:mb-4">
                    <h4 class="text-base sm:text-lg font-semibold text-stone-100 tracking-wide">
                      {{ scale.scale_name }}
                    </h4>
                    <span class="text-lg sm:text-xl font-bold text-amber-400 bg-amber-400/10 px-2 sm:px-3 py-0.5 sm:py-1 rounded-lg">
                      {{ Math.round(scale.percentile_score) }}%
                    </span>
                  </div>
                  <div class="w-full bg-stone-800 rounded-full h-4 sm:h-5 mb-3 sm:mb-4 overflow-hidden">
                    <div class="bg-amber-500 h-4 sm:h-5 rounded-full transition-all duration-700 ease-out"
                      :style="{ width: `${Math.round(scale.percentile_score)}%` }">
                    </div>
                  </div>
                  <div @click="toggleDescription(scale.scale_id)"
                    class="cursor-pointer group-hover:bg-stone-800/50 rounded-lg p-2 sm:p-3 transition-colors">
                    <p class="text-xs sm:text-sm leading-relaxed text-stone-300 font-medium tracking-wide group-hover:text-stone-200 transition-colors"
                      :class="{ 'line-clamp-2': !expandedDescriptions[scale.scale_id] }">
                      {{ scale.single_sentence_description }}
                    </p>
                    <span class="text-amber-400 text-xs sm:text-sm mt-1 sm:mt-2 hover:text-amber-300"
                      v-if="scale.single_sentence_description.length > 100">
                      {{ expandedDescriptions[scale.scale_id] ? '▲ Show less' : '▼ Read more' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Traits Grid -->

          </div>

     
          <!-- AI Assistant Card -->
          <div class="bg-stone-950 rounded-2xl border border-stone-700 flex flex-col h-[600px]">
            <div class="p-6 border-b border-stone-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></div>
                  <h2 class="text-xl font-semibold text-stone-100">AI Career Guide</h2>
                </div>
                </div>
            
            </div>
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar" ref="chatContainer">
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
            <div class="p-4 border-t border-stone-700">
              <form @submit.prevent="sendMessage">
                <div class="flex items-center gap-2">
                  <div class="relative flex-1">
                    <input v-model="form.message" @focus="showQuestions = true" type="text"
                      class="w-full h-10 px-4 bg-stone-900 text-stone-100 rounded-xl border border-stone-700 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors pl-10"
                      :placeholder="__('dashboard.type_your_message')" :disabled="isLoading" />
                    <div class="absolute left-3 top-1/2 -translate-y-1/2">
                      <svg v-if="!isLoading" class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                      </svg>
                      <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </div>

                    <!-- Predefined Questions Dropdown -->
                    <div v-if="showQuestions && !isLoading" @click.outside="showQuestions = false"
                      class="absolute bottom-full left-0 right-0 mb-2 bg-stone-900/95 backdrop-blur-sm rounded-xl border border-stone-700 shadow-lg max-h-[400px] overflow-y-auto transform transition-all duration-200 ease-out z-50"
                      :class="{ 'opacity-0 scale-95': !showQuestions, 'opacity-100 scale-100': showQuestions }">
                      <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                          <h3 class="text-stone-300 font-medium">Suggested Questions</h3>
                          <button @click="showQuestions = false" class="p-1 hover:bg-stone-800 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                          </button>
                        </div>
                        <div v-for="category in predefinedQuestions || []" :key="category.category"
                          class="mb-6 last:mb-0">
                          <div class="flex items-center space-x-2 mb-3">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                :d="category.icon" />
                            </svg>
                            <h3 class="text-stone-300 font-medium">{{ category.category }}</h3>
                          </div>
                          <div class="space-y-2 ml-7">
                            <button v-for="question in category.questions" :key="question"
                              @click.prevent="selectQuestion(question)"
                              class="w-full text-left p-3 text-stone-400 hover:text-stone-200 hover:bg-stone-800/50 rounded-lg transition-all duration-200 text-sm group">
                              <div class="flex items-center justify-between">
                                <span>{{ question }}</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity text-amber-500"
                                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                                </svg>
                              </div>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="submit"
                    class="h-10 w-10 flex items-center justify-center bg-amber-500 hover:bg-amber-600 disabled:bg-stone-700 text-stone-900 disabled:text-stone-400 rounded-xl transition-all duration-200 disabled:cursor-not-allowed"
                    :disabled="!form.message.trim() || isLoading">
                    <svg v-if="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
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
          </div>

        </div>
      </div>
      </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, nextTick, onMounted, onUnmounted, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
  hasResult: Boolean,
  favoriteJobs: {
    type: Array,
    default: () => []
  },
  favoriteDegrees: {
    type: Array,
    default: () => []
  },
  predefinedQuestions: {
    type: Array,
    default: () => []
  },
  archetype: {
    type: Object,
    default: null
  },
  topTraits: {
    type: Array,
    default: () => []
  },
  topJobs: {
    type: Array,
    default: () => []
  },
  aiMessage: String,
  chatHistory: {
    type: Array,
    default: () => []
  }
});

// Computed properties for safe access
const favoriteJobsCount = computed(() => props.favoriteJobs?.length || 0);
const favoriteDegreesCount = computed(() => props.favoriteDegrees?.length || 0);
const parsedScales = computed(() => {
  if (!props.archetype?.scales) return [];
  try {
    return JSON.parse(props.archetype.scales);
  } catch (e) {
    console.error('Error parsing archetype scales:', e);
    return [];
  }
});

const messages = ref([]);
const showQuestions = ref(false);
const chatContainer = ref(null);
const expandedDescriptions = ref({});
const isLoading = ref(false);

const form = ref({
  message: ''
});

// Initialize messages with chat history
onMounted(() => {
  if (props.chatHistory && props.chatHistory.length > 0) {
    messages.value = props.chatHistory.map(chat => ({
      role: 'user',
      content: chat.message,
      timestamp: new Date(chat.created_at).toLocaleTimeString(),
      id: chat.id
    })).concat(props.chatHistory.map(chat => ({
      role: 'assistant',
      content: chat.response,
      timestamp: new Date(chat.created_at).toLocaleTimeString(),
      id: chat.id
    })));
  }
  document.addEventListener('click', handleClickOutside);
  if (props.aiMessage) {
    typingEffect(props.aiMessage);
  }
});

// Handle click outside to close questions dropdown
const handleClickOutside = (event) => {
  const inputElement = document.querySelector('input[type="text"]');
  const questionsDropdown = document.querySelector('.predefined-questions');

  if (!event.target.closest('.relative') && showQuestions.value) {
    showQuestions.value = false;
  }
};

// Watch for messages changes to scroll to bottom
watch(messages, () => {
  nextTick(() => {
    scrollToBottom();
  });
}, { deep: true });

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

const selectQuestion = (question) => {
  form.value.message = question;
  showQuestions.value = false;
  // Focus the input after selection
  nextTick(() => {
    document.querySelector('input[type="text"]').focus();
  });
};

const toggleDescription = (scaleId) => {
  expandedDescriptions.value[scaleId] = !expandedDescriptions.value[scaleId];
};

const sendMessage = async () => {
  if (!form.value.message.trim()) return;

  const userMessage = form.value.message;
  form.value.message = '';

  messages.value.push({
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
      messages.value.push({
        role: 'assistant',
        content: response.data.aiMessage,
        timestamp: new Date().toLocaleTimeString(),
      });
    }
  } catch (error) {
    console.error('Error sending message:', error);
    messages.value.push({
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

const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

const removeFavorite = (id) => {
  router.delete(`/favorites/${id}`);
};

const aiMessage = ref(props.aiMessage);

const formattedAiMessage = computed(() => {
  if (!aiMessage.value) return '';
  return aiMessage.value.replace(/\n/g, '<br>');
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

.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
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

@keyframes pulse {

  0%,
  100% {
    opacity: 1;
  }

  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>