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
          <img :src="job.image" :alt="`image for ${job.name}`"
            class="w-full h-full object-contain filter contrast-125 transition-transform duration-300 group-hover:scale-110" />
        </div>
        <div class="min-w-0">
          <h3 :class="[
            'text-base font-bold truncate',
            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
          ]">
            {{ job.name }}
          </h3>
          <p :class="['text-sm truncate', themeClasses.text]">{{ job.category }}</p>
        </div>
      </div>

      <!-- Description -->
      <p class="text-sm text-gray-600 line-clamp-2 mb-3 h-10">{{ job.description }}</p>

      <!-- Key Details -->
      <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
        <div v-if="jobDetails.salary_range" class="flex items-center gap-1 text-gray-600">
          <Banknote class="w-3.5 h-3.5" />
          <span class="truncate">{{ jobDetails.salary_range }}</span>
        </div>
        <div v-if="jobDetails.location" class="flex items-center gap-1 text-gray-600">
          <MapPin class="w-3.5 h-3.5" />
          <span class="truncate">{{ jobDetails.location }}</span>
        </div>
        <div v-if="jobDetails.experience" class="flex items-center gap-1 text-gray-600">
          <Clock class="w-3.5 h-3.5" />
          <span class="truncate">{{ jobDetails.experience }}</span>
        </div>
        <div v-if="jobDetails.employment_type" class="flex items-center gap-1 text-gray-600">
          <Briefcase class="w-3.5 h-3.5" />
          <span class="truncate">{{ jobDetails.employment_type }}</span>
        </div>
      </div>

      <!-- Skills -->
      <div v-if="job.skills?.length" class="mb-4">
        <div class="flex flex-wrap gap-1">
          <span v-for="skill in job.skills.slice(0, 3)" :key="skill"
            class="px-2 py-0.5 text-xs rounded-full bg-white/50 border border-white/20 transition-colors duration-300"
            :class="themeStore.getThemeClasses('border')">
            {{ skill }}
          </span>
          <span v-if="job.skills.length > 3" class="px-2 py-0.5 text-xs text-gray-500">
            +{{ job.skills.length - 3 }}
          </span>
        </div>
      </div>

      <!-- Action Button -->
      <Link :href="`/career/${job.slug}`"
        class="inline-flex items-center justify-between w-full px-4 py-2 bg-white/60 backdrop-blur-sm font-medium rounded-full border border-white/20 transition-all duration-300 text-sm"
        :class="[themeClasses.button, themeClasses.text]">
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
import { ArrowRight, Banknote, MapPin, Clock, Briefcase } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
  job: {
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
}));

const jobDetails = computed(() => ({
  salary_range: props.job.salary_range,
  experience: props.job.experience,
  location: props.job.location,
  employment_type: props.job.employment_type
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