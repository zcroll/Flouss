<template>
  <div class="bg-stone-950 rounded-2xl p-8 mb-4 border border-stone-700">
    <div class="flex flex-col md:flex-row justify-between items-center">
      <div>
        <h1 class="text-4xl font-bold text-stone-100">
          {{ __('dashboard.welcome') }} {{ username }}!
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  username: {
    type: String,
    required: true
  },
  hasResult: {
    type: Boolean,
    default: false
  }
});
</script>
