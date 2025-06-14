<template>
  <div
    class="group relative flex items-center gap-6 rounded-xl border p-5 transition-all duration-300 hover:-translate-y-0.5"
    :class="[
      themeStore.getThemeClasses('background', 'light'),
      themeStore.getThemeClasses('border'),
      'hover:shadow-lg hover:shadow-black/5 backdrop-blur-sm'
    ]" :aria-labelledby="`career-${job.slug}`" @click="$emit('select', job)">
    <!-- Image Container -->
    <div class="relative h-16 w-16 overflow-hidden rounded-xl shadow-sm" :class="[
      `bg-${themeStore.currentTheme.button}/5`,
      themeStore.getThemeClasses('border')
    ]">
      <img :src="job.image" :alt="job.career"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" role="presentation"
        aria-hidden="true" />
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
      <div :id="`career-${job.slug}`" class="font-medium text-base transition-colors duration-200">
        <Link :href="`/career/${job.slug}`"
          class="block truncate focus:outline-none focus:ring-2 rounded-md transition-colors duration-200"
          :class="[
            `focus:ring-${themeStore.currentTheme.ring}`,
            `hover:text-${themeStore.currentTheme.hover}`,
            `dark:hover:text-${themeStore.currentTheme.hover}`
          ]" tabindex="0">
        {{ job.name }}
        </Link>
      </div>
    </div>

    <!-- Arrow Icon -->
    <div
      class="flex items-center transition-transform duration-300 group-hover:translate-x-1 opacity-40 group-hover:opacity-100"
      :class="`text-${themeStore.currentTheme.button}`">
      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 18l6-6-6-6" />
      </svg>
    </div>

    <!-- Shine Effect -->
    <div
      class="absolute inset-0 -z-10 overflow-hidden rounded-xl opacity-0 transition-opacity duration-300 group-hover:opacity-100">
      <div
        class="absolute inset-0 translate-x-[-100%] transform bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-[100%]" />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useThemeStore } from '@/stores/theme/themeStore';

const themeStore = useThemeStore();

defineProps({
  job: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);
</script>

<style scoped>
@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Add animation delay for each card */
:deep(.career-card:nth-child(1)) {
  animation-delay: 0.1s;
}

:deep(.career-card:nth-child(2)) {
  animation-delay: 0.2s;
}

:deep(.career-card:nth-child(3)) {
  animation-delay: 0.3s;
}

:deep(.career-card:nth-child(4)) {
  animation-delay: 0.4s;
}

:deep(.career-card:nth-child(5)) {
  animation-delay: 0.5s;
}
</style>