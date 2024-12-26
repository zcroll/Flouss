<template>
  <Card class="flex flex-col items-center justify-center py-12 bg-white/60 backdrop-blur-xl">
    <CardContent class="text-center">
      <div class="mb-4">
        <SearchX class="w-12 h-12 text-gray-400 mx-auto" />
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">
        {{ __("jobs.no_jobs_found") }}
      </h3>
      <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
      <button @click="$emit('reset')"
        class="px-6 py-3 font-medium rounded-full transition-colors duration-300 flex items-center gap-2 mx-auto"
        :class="themeClasses.button">
        <RefreshCw class="w-4 h-4" />
        {{ __("jobs.reset_filters") }}
      </button>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { Card, CardContent } from "@/Components/ui/card";
import { SearchX, RefreshCw } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';

const themeStore = useThemeStore();

const themeClasses = computed(() => ({
  button: [
    'transition-colors duration-200',
    themeStore.isDarkMode
      ? `bg-${themeStore.currentTheme.primary}-600 hover:bg-${themeStore.currentTheme.primary}-700`
      : `bg-${themeStore.currentTheme.primary}-500 hover:bg-${themeStore.currentTheme.primary}-600`,
    'text-white'
  ]
}));

defineEmits(['reset']);
</script>