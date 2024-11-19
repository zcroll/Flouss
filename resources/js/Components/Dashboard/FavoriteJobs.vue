<template>
  <div class="bg-stone-950 rounded-2xl p-6 border border-stone-700">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_jobs') }}</h2>
      <span class="px-4 py-2 bg-stone-700 rounded-full text-sm text-stone-300">
        {{ favoriteJobs.length }}
      </span>
    </div>
    <div v-if="favoriteJobs.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
      <div v-for="job in favoriteJobs" :key="job.id"
        class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-4 transition-all duration-300">
        <Link :href="`/career/${job.slug}`" class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-medium text-stone-100">{{ job.name }}</h3>
            <p class="text-sm text-stone-400">Career Path</p>
          </div>
          <button @click.prevent="$emit('remove', job.id)"
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  favoriteJobs: {
    type: Array,
    required: true
  }
});

defineEmits(['remove']);
</script>
