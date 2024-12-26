<template>
  <div class="group relative rounded-3xl overflow-hidden transition-all duration-500 hover:shadow-xl"
    :class="[themeStore.getThemeClasses('base')]" :style="{ animationDelay: `${index * 100}ms` }">
    <div class="relative p-6 h-full flex flex-col">
      <!-- Card Header -->
      <div class="flex items-start gap-4 mb-6">
        <div
          class="relative w-16 h-16 rounded-2xl overflow-hidden group-hover:scale-105 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-br"
            :class="[`from-${themeStore.currentTheme.primary}-100 to-${themeStore.currentTheme.primary}-50`]">
          </div>
          <img :src="job.image" :alt="`${job.name} icon`"
            class="relative w-full h-full object-contain p-3 transition-transform duration-500 group-hover:scale-110" />
        </div>

        <div class="flex-1">
          <h3 class="text-lg font-bold mb-1 bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
            {{ job.name }}
          </h3>
          <p class="text-sm text-gray-600">{{ job.category }}</p>
        </div>
      </div>

      <!-- Job Details -->
      <JobDetails :job="job" :theme-store="themeStore" />

      <!-- Card Footer -->
      <div class="mt-6 pt-4 border-t border-gray-100">
        <Link :href="route('career', job.id)"
          class="group inline-flex items-center gap-2 text-sm font-medium transition-colors duration-300"
          :class="[`text-${themeStore.currentTheme.primary}-600`, `hover:text-${themeStore.currentTheme.primary}-700`]">
        View Details
        <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import JobDetails from './JobDetails.vue';

defineProps({
  job: Object,
  index: Number,
  themeStore: Object
});
</script>