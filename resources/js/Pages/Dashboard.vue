<template>
  <AppLayout title="dashboard" >
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex flex-wrap gap-4 mb-6">
        <button
          @click="testError"
          class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
        >
          Test 404 Error
        </button>

        <button
          @click="testBackendError"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
        >
          Test Backend Messages
        </button>

        <div class="flex flex-wrap gap-2">
          <button
            @click="testFetch('success')"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
            :disabled="loading"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Test Success Fetch
          </button>

          <button
            @click="testFetch('error')"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700"
            :disabled="loading"
          >
            Test Error Fetch
          </button>

          <button
            @click="testFetch('unauthorized')"
            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700"
            :disabled="loading"
          >
            Test Unauthorized
          </button>

          <button
            @click="testFetch('validation')"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
            :disabled="loading"
          >
            Test Validation Error
          </button>
        </div>
      </div>

      <div v-if="testData" class="bg-white p-4 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-2">Test Response Data:</h3>
        <pre class="bg-gray-50 p-3 rounded text-sm">{{ JSON.stringify(testData, null, 2) }}</pre>
      </div>
    </div>

    <div class="min-h-screen">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <WelcomeBanner 
          :username="$page.props.auth.user.name"
          :has-result="hasResult"
        />
        
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <FavoritesList
            :title="__('dashboard.liked_jobs')"
            :empty-message="__('dashboard.no_liked_jobs')"
            :browse-message="__('dashboard.discover_jobs')"
            :items="favoriteJobs"
            type="career"
            browse-link="/jobs"
            @remove="removeFavorite"
          />
          
          <FavoritesList
            :title="__('dashboard.liked_formations')"
            :empty-message="__('dashboard.no_liked_formations')"
            :browse-message="__('dashboard.discover_formations')"
            :items="favoriteDegrees"
            type="degree"
            browse-link="/degrees"
            @remove="removeFavorite"
          />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <AssessmentResults 
            :hasResult="hasResult"
            :archetype="archetype"
            :topTraits="topTraits"
            :parsed-scales="parsedScales"
            class="lg:col-span-2"
          />
          
          <AIChat
            :predefined-questions="predefinedQuestions"
            :initial-messages="chatHistory"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import WelcomeBanner from '@/Components/Dashboard/WelcomeBanner.vue';
import FavoritesList from '@/Components/Dashboard/FavoritesList.vue';
import AssessmentResults from '@/Components/Dashboard/AssessmentResults.vue';
import AIChat from '@/Components/Dashboard/AIChat.vue';
import { ref } from 'vue';

const props = defineProps({
  hasResult: Boolean,
  favoriteJobs: Array,
  favoriteDegrees: Array,
  predefinedQuestions: Array,
  archetype: Object,
  chatHistory: Array,
  topTraits: Object,
  parsedScales: Object,
  testData: Object
});

const loading = ref(false);

// Test error handling with different scenarios
const testFetch = (scenario) => {
  loading.value = true;
  
  router.get(route('dashboard.test-fetch', { scenario }), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      loading.value = false;
    },
    onError: (errors) => {
      loading.value = false;
      console.error('Fetch errors:', errors);
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

const removeFavorite = (id) => {
  router.delete(`/favorites/${id}`);
};

// Test error handling
const testError = () => {
  // This will trigger the error handler in the backend
  router.get('/non-existent-route');
};

const testBackendError = () => {
  // This will trigger our test error endpoint
  router.get(route('dashboard.test-messages'));
};
</script>