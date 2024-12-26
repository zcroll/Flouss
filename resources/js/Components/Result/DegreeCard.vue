<template>
  <div
    class="group relative flex items-center gap-6 rounded-xl border p-5 transition-all duration-300 hover:-translate-y-0.5"
    :class="[
      themeStore.getThemeClasses('background', 'light'),
      themeStore.getThemeClasses('border'),
      'hover:shadow-lg hover:shadow-black/5 backdrop-blur-sm'
    ]" :aria-labelledby="`degree-${degree.slug}`" @click="$emit('select', degree)">
    <!-- Image Container -->
    <div class="relative h-16 w-16 overflow-hidden rounded-xl shadow-sm" :class="[
      `bg-${themeStore.currentTheme.button.secondary}/5`,
      themeStore.getThemeClasses('border')
    ]">
      <img :src="degree.image" :alt="degree.name"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" role="presentation"
        aria-hidden="true" />
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
      <div :id="`degree-${degree.slug}`" class="font-medium text-base transition-colors duration-200"
        :class="themeStore.getThemeClasses('text')">
        <Link :href="`/degree/${degree.slug}`"
          class="block truncate hover:underline focus:outline-none focus:ring-2 rounded-md"
          :class="`focus:ring-${themeStore.currentTheme.button.primary}`" tabindex="0">
        {{ degree.name }}
        </Link>
      </div>
    </div>

    <!-- Arrow Icon -->
    <div
      class="flex items-center transition-transform duration-300 group-hover:translate-x-1 opacity-40 group-hover:opacity-100"
      :class="`text-${themeStore.currentTheme.button.primary}`">
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
  degree: {
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
:deep(.degree-card:nth-child(1)) {
  animation-delay: 0.1s;
}

:deep(.degree-card:nth-child(2)) {
  animation-delay: 0.2s;
}

:deep(.degree-card:nth-child(3)) {
  animation-delay: 0.3s;
}

:deep(.degree-card:nth-child(4)) {
  animation-delay: 0.4s;
}

:deep(.degree-card:nth-child(5)) {
  animation-delay: 0.5s;
}
</style>