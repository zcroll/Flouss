<template>
  <Card class="group relative backdrop-blur-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
    <CardContent class="p-4">
      <!-- Header with Image and Title -->
      <div class="flex items-start gap-3 mb-3">
        <div :class="[
          'w-12 h-12 rounded-xl overflow-hidden p-2 flex-shrink-0 transition-colors duration-300',
          themeStore.isDarkMode ? 'bg-gray-700/50' : 'bg-white/50',
          themeStore.getThemeClasses('border')
        ]">
          <img :src="degree.image" :alt="`image for ${degree.name}`"
            class="w-full h-full object-contain filter contrast-125 transition-transform duration-300 group-hover:scale-110" />
        </div>
        <div class="min-w-0">
          <h3 :class="[
            'text-base font-bold truncate',
            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
          ]">
            {{ degree.name }}
          </h3>
          <p :class="['text-sm truncate', themeClasses.text]">{{ degree.type }}</p>
        </div>
      </div>

      <!-- Description -->
      <p class="text-sm text-gray-600 line-clamp-2 mb-3 h-10">{{ degree.description }}</p>

      <!-- Key Details -->
      <div class="grid grid-cols-2 gap-2 mb-3">
        <div class="flex items-center gap-1.5">
          <Clock class="w-4 h-4 text-gray-400" />
          <span class="text-sm text-gray-600">{{ degreeDetails.duration }}</span>
        </div>
        <div class="flex items-center gap-1.5">
          <GraduationCap class="w-4 h-4 text-gray-400" />
          <span class="text-sm text-gray-600">{{ degreeDetails.level }}</span>
        </div>
      </div>

      <!-- Action Button -->
      <Link :href="`/degree/${degree.slug}`"
        class="inline-flex items-center justify-between w-full px-4 py-2 font-medium rounded-full transition-all duration-300 text-sm"
        :class="[
          themeStore.isDarkMode
            ? [
              'bg-gray-800/60 text-white border-gray-700/20',
              `hover:bg-${themeStore.currentTheme.primary}-800`
            ]
            : [
              'bg-white/60 text-gray-900 border-white/20 hover:text-white',
              `hover:bg-${themeStore.currentTheme.primary}-400`
            ],
          'backdrop-blur-sm border',
          themeClasses.button,
          themeClasses.text
        ]">
      <span>Learn More</span>
      <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
      </Link>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Card, CardContent } from "@/Components/ui/card";
import { ArrowRight, Clock, GraduationCap } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
  degree: {
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
    'transition-colors duration-200',
  ],
}));

const degreeDetails = computed(() => ({
  duration: props.degree.duration,
  level: props.degree.level
}));
</script>

<style scoped>
.grid>div {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>