<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <TransitionGroup tag="div" class="contents" enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4" enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in" leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4">
        <CareerCard v-for="job in displayedJobs" :key="job.slug" :job="job" @select="$emit('select-job', job)" />
      </TransitionGroup>
    </div>

    <div class="flex justify-center" v-if="!showAllJobs && hasMoreJobs">
      <button
        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition-all duration-200"
        :class="[
          themeStore.getThemeClasses('button'),
          themeStore.getThemeClasses('hover'),
          'text-white',
          themeStore.getThemeClasses('border'),
          `focus:ring-2 focus:ring-${themeStore.currentTheme.button.primary} focus:ring-offset-2 focus:outline-none`
        ]" @click="$emit('toggle-show-more')">
        {{ __("results.See_more_matches") }}
        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-y-0.5"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 9l6 6 6-6" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme/themeStore';
import CareerCard from './CareerCard.vue';

const themeStore = useThemeStore();

defineProps({
  displayedJobs: {
    type: Array,
    required: true
  },
  showAllJobs: {
    type: Boolean,
    default: false
  },
  hasMoreJobs: {
    type: Boolean,
    required: true
  }
});

defineEmits(['select-job', 'toggle-show-more']);
</script>

<style scoped>
.Button {
  @apply font-medium text-sm;
}

.Button-icon {
  @apply transition-transform duration-200;
}

.Button:hover .Button-icon {
  @apply transform translate-y-0.5;
}
</style>
