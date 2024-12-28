<template>
  <div class="absolute inset-0 -z-10">
    <div :class="[
      'absolute inset-0',
      themeStore.isDarkMode ? 'bg-gray-900/30 backdrop-blur-xl' : '',
      themeStore.getThemeClasses('base')
    ]"></div>
    <GradientCircle position="top-left" />
    <GradientCircle position="bottom-right" />
  </div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme/themeStore';
import GradientCircle from './GradientCircle.vue';

const themeStore = useThemeStore();
</script>