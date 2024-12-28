<template>
  <div :class="[
    'absolute w-40 h-40 rounded-full blur-2xl transform',
    position === 'top-left' ? '-translate-x-1/2 -translate-y-1/2 top-0 left-0' : '',
    position === 'bottom-right' ? 'translate-x-1/2 translate-y-1/2 bottom-0 right-0' : '',
    themeStore.isDarkMode ? themeStore.getThemeClasses('background').medium : themeStore.getThemeClasses('background').light,
    themeStore.isDarkMode ? 'opacity-30' : 'opacity-100'
  ]"></div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme/themeStore';

const themeStore = useThemeStore();

defineProps({
  position: {
    type: String,
    required: true,
    validator: (value) => ['top-left', 'bottom-right'].includes(value)
  }
});
</script>