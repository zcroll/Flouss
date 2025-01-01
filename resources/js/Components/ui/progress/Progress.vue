<script setup>
import { useThemeStore } from '@/stores/theme/themeStore';
import { computed } from 'vue';

const props = defineProps({
  value: {
    type: Number,
    default: 0
  },
  showValue: {
    type: Boolean,
    default: false
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
  }
});

const themeStore = useThemeStore();

const heightClass = computed(() => ({
  'sm': 'h-1.5',
  'md': 'h-2.5',
  'lg': 'h-3',
}[props.size]));

const textSize = computed(() => ({
  'sm': 'text-xs',
  'md': 'text-sm',
  'lg': 'text-base',
}[props.size]));
</script>

<template>
  <div class="relative w-full">
    <!-- Background track -->
    <div 
      class="w-full overflow-hidden rounded-full transition-all duration-300"
      :class="[
        heightClass,
        `bg-${themeStore.currentTheme.primary}-100 dark:bg-${themeStore.currentTheme.primary}-950/20`
      ]"
      role="progressbar"
      :aria-valuenow="value"
      aria-valuemin="0"
      aria-valuemax="100"
    >
      <!-- Progress indicator -->
      <div
        class="h-full transition-all duration-500 ease-out"
        :class="`bg-${themeStore.currentTheme.primary}-500 dark:bg-${themeStore.currentTheme.primary}-400`"
        :style="{
          width: `${Math.min(Math.max(value, 0), 100)}%`,
          transition: 'width 0.5s ease-out'
        }"
      />
    </div>

    <!-- Value label -->
    <div 
      v-if="showValue"
      class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 font-medium"
      :class="[
        textSize,
        `text-${themeStore.currentTheme.primary}-700 dark:text-${themeStore.currentTheme.primary}-300`
      ]"
    >
      {{ Math.round(value) }}%
    </div>
  </div>
</template>

<style scoped>
/* Optional: Add a subtle gradient effect to the progress bar */
.bg-gradient {
  background-image: linear-gradient(
    to right,
    var(--tw-gradient-from),
    var(--tw-gradient-to)
  );
}
</style>
