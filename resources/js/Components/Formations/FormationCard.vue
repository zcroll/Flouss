<template>
  <Card class="group relative backdrop-blur-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
    <CardContent class="p-4">
      <!-- Header with Title -->
      <div class="min-w-0 mb-3">
        <h3 :class="[
          'text-base font-bold line-clamp-2',
          themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
        ]">
          {{ formation.nom }}
        </h3>
      </div>

      <!-- Formation Details -->
      <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
        <div v-if="formation.type_etablissement" class="flex items-center gap-1 text-gray-600">
          <GraduationCap class="w-3.5 h-3.5" />
          <span class="truncate">{{ formation.type_etablissement }}</span>
        </div>
        <div v-if="formation.ville" class="flex items-center gap-1 text-gray-600">
          <MapPin class="w-3.5 h-3.5" />
          <span class="truncate">{{ formation.ville }}</span>
        </div>
      </div>

      <!-- Tags -->
      <div class="flex flex-wrap gap-1.5 mb-4">
        <span v-if="formation.niveau" class="px-2 py-0.5 text-xs rounded-full" :class="[themeClasses.tag]">
          {{ formation.niveau }}
        </span>
        <span v-if="formation.diploma" class="px-2 py-0.5 text-xs rounded-full bg-gray-100/80 text-gray-700">
          {{ formation.diploma }}
        </span>
      </div>

      <!-- Action Button -->
      <Link :href="route('formation.show', formation.id)"
        class="inline-flex items-center justify-between w-full px-4 py-2 bg-white/60 backdrop-blur-sm font-medium rounded-full border border-white/20 transition-all duration-300 text-sm"
        :class="[themeClasses.button, themeClasses.text]">
      <span>{{ __('formations.learn_more') }}</span>
      <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
      </Link>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Card, CardContent } from "@/Components/ui/card";
import { ArrowRight, GraduationCap, MapPin } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
  formation: {
    type: Object,
    required: true
  }
});

const themeStore = useThemeStore();

const themeClasses = computed(() => ({
  text: [
    themeStore.isDarkMode
      ? `text-${themeStore.currentTheme.primary}-400`
      : `text-${themeStore.currentTheme.primary}-600`
  ],
  button: [
    'transition-colors duration-200 hover:bg-white/80',
    themeStore.isDarkMode
      ? `hover:bg-${themeStore.currentTheme.primary}-600/10`
      : `hover:bg-${themeStore.currentTheme.primary}-500/10`,
  ],
  tag: [
    'bg-white/50 border border-white/20',
    themeStore.isDarkMode
      ? `text-${themeStore.currentTheme.primary}-400`
      : `text-${themeStore.currentTheme.primary}-600`,
  ]
}));
</script>

<style scoped>
.grid>div {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
