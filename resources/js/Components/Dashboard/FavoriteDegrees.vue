<template>
  <div class="bg-stone-950 rounded-2xl p-4 sm:p-6 border border-stone-700">
    <div class="flex items-center justify-between mb-4 sm:mb-6">
      <h2 class="text-xl sm:text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_formations') }}</h2>
      <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-stone-700 rounded-full text-xs sm:text-sm text-stone-300">
        {{ favoriteDegrees.length }}
      </span>
    </div>
    <div v-if="favoriteDegrees.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
      <div v-for="degree in favoriteDegrees" :key="degree.id"
        class="bg-stone-700/50 hover:bg-stone-700 rounded-xl p-3 sm:p-4 transition-all duration-300">
        <Link :href="`/degree/${degree.slug}`" class="flex items-center justify-between">
          <div>
            <h3 class="text-base sm:text-lg font-medium text-stone-100">{{ degree.name }}</h3>
            <p class="text-xs sm:text-sm text-stone-400">Education Path</p>
          </div>
          <button @click.prevent="$emit('remove', degree.id)"
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  favoriteDegrees: {
    type: Array,
    required: true
  }
});

defineEmits(['remove']);
</script>
