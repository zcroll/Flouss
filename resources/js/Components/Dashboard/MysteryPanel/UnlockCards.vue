<template>
  <div class="grid gap-4">
    <div v-for="(item, index) in unlockItems" 
         :key="item.title"
         class="unlock-card group"
         :style="{ '--delay': `${(index + 1) * 200}ms` }"
    >
      <div class="relative overflow-hidden rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm p-4 border border-indigo-100/50 dark:border-indigo-900/50 transition-all duration-300 hover:shadow-xl">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-indigo-500/5 to-transparent translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000"></div>
        
        <div class="relative flex items-start space-x-4">
          <!-- Icon -->
          <div :class="[
            'w-12 h-12 rounded-lg flex items-center justify-center transition-transform duration-300 group-hover:scale-110',
            item.bgColor
          ]">
            <svg class="w-6 h-6" :class="item.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
            </svg>
          </div>
          
          <!-- Content -->
          <div class="flex-1 space-y-1">
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ item.title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ item.description }}</p>
          </div>
          
          <!-- Lock Icon -->
          <div class="text-gray-400 dark:text-gray-600 transition-colors duration-300 group-hover:text-indigo-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  theme: {
    type: Object,
    required: true
  }
})

const unlockItems = computed(() => [
  {
    title: 'Personality Archetype',
    description: 'Discover your unique personality type and core traits',
    icon: 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z',
    bgColor: 'bg-indigo-100 dark:bg-indigo-900/50',
    iconColor: 'text-indigo-600 dark:text-indigo-400'
  },
  {
    title: 'Career Insights',
    description: 'Get personalized job recommendations that match your personality',
    icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    bgColor: 'bg-purple-100 dark:bg-purple-900/50',
    iconColor: 'text-purple-600 dark:text-purple-400'
  },
  {
    title: 'Degree Matches',
    description: 'Find educational paths aligned with your career goals',
    icon: 'M12 14l9-5-9-5-9 5 9 5z M12 14l9-5-9-5-9 5 9 5zm0 0L3 9m9 5v5m0-5l9-5',
    bgColor: 'bg-blue-100 dark:bg-blue-900/50',
    iconColor: 'text-blue-600 dark:text-blue-400'
  }
])
</script>

<style scoped>
.unlock-card {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s ease forwards;
  animation-delay: var(--delay);
}

.unlock-card:hover {
  transform: translateY(-2px);
}

/* Enhanced card hover effects */
.unlock-card .group-hover\:scale-110 {
  transition-delay: 100ms;
}

.unlock-card .group-hover\:translate-x-\[200\%\] {
  transition-delay: 200ms;
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

/* Glass effect enhancement */
.backdrop-blur-sm {
  backdrop-filter: blur(8px) saturate(180%);
  -webkit-backdrop-filter: blur(8px) saturate(180%);
}
</style> 