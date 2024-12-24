<template>
  <div class="absolute bottom-2 left-2 right-2 flex gap-2">
    <!-- Complete Card (Favorite Jobs) -->
    <div class="relative bg-white/60 backdrop-blur-xl rounded-lg p-2 shadow-sm hover:shadow-md transition-all duration-300 flex-1 group"
         :style="{
           height: activeCard === 'complete' ? '280px' : '60px',
           marginTop: activeCard === 'complete' ? '-220px' : '0'
         }"
         @mouseenter="activeCard = 'complete'"
         @mouseleave="activeCard = null">
      <div class="flex items-center justify-between mb-1.5">
        <div class="flex items-center gap-2">
          <span class="text-sm font-medium text-gray-700">Favorite Jobs</span>
          <!-- Compact counter - only visible when not active -->
          <span class="text-xs bg-yellow-400/20 px-2 py-0.5 rounded-full"
                :class="{ 'opacity-0': activeCard === 'complete' }">
            {{ props.favoriteJobs.length }}
          </span>
        </div>
        <div class="h-5 w-5 bg-yellow-400 rounded-full flex items-center justify-center transform group-hover:rotate-45 transition-transform duration-300">
          <ArrowRight class="h-3.5 w-3.5 text-white" />
        </div>
      </div>
      
      <div class="space-y-1.5 overflow-hidden transition-all duration-300"
           :class="{ 'opacity-100': activeCard === 'complete', 'opacity-0': activeCard !== 'complete' }">
        <!-- Jobs Counter -->
        <div class="relative overflow-hidden">
          <div class="flex items-baseline gap-1">
            <div class="text-xl font-bold text-gray-800 tracking-tight"
                 :class="{ 'animate-number': activeCard === 'complete' }">
              {{ props.favoriteJobs.length }}
            </div>
            <span class="text-xs text-gray-500">Saved Jobs</span>
          </div>
        </div>

        <!-- Favorite Jobs List -->
        <div class="transition-all duration-300 transform"
             :class="{ 'translate-y-0': activeCard === 'complete', 'translate-y-4': activeCard !== 'complete' }">
          <div class="max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
            <Link v-for="(job, index) in props.favoriteJobs" 
                  :key="job.id"
                  :href="`/career/${job.slug}`"
                  class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/job hover:bg-white/70 transition-all duration-200"
                  :style="{ transitionDelay: `${index * 50}ms` }"
                  :class="{ 'translate-x-0 opacity-100': activeCard === 'complete', 'translate-x-4 opacity-0': activeCard !== 'complete' }">
              <div class="w-7 h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <img :src="job.image" 
                     :alt="job.name"
                     class="w-full h-full object-contain filter contrast-125" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-700 truncate">{{ job.name }}</h4>
              </div>
              <ArrowRight class="w-3.5 h-3.5 text-yellow-500 transform group-hover/job:translate-x-0.5 transition-transform" />
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Favorite Degrees Card -->
    <div class="relative bg-white/60 backdrop-blur-xl rounded-lg p-2 shadow-sm hover:shadow-md transition-all duration-300 flex-1 group"
         :style="{
           height: activeCard === 'degrees' ? '280px' : '60px',
           marginTop: activeCard === 'degrees' ? '-220px' : '0'
         }"
         @mouseenter="activeCard = 'degrees'"
         @mouseleave="activeCard = null">
      <div class="flex items-center justify-between mb-1.5">
        <div class="flex items-center gap-2">
          <span class="text-sm font-medium text-gray-700">Favorite Degrees</span>
          <!-- Compact counter - only visible when not active -->
          <span class="text-xs bg-yellow-400/20 px-2 py-0.5 rounded-full"
                :class="{ 'opacity-0': activeCard === 'degrees' }">
            {{ props.favoriteDegrees.length }}
          </span>
        </div>
        <div class="h-5 w-5 bg-yellow-400 rounded-full flex items-center justify-center transform group-hover:rotate-45 transition-transform duration-300">
          <ArrowRight class="h-3.5 w-3.5 text-white" />
        </div>
      </div>

      <div class="space-y-1.5 overflow-hidden transition-all duration-300"
           :class="{ 'opacity-100': activeCard === 'degrees', 'opacity-0': activeCard !== 'degrees' }">
        <!-- Degrees Counter -->
        <div class="relative overflow-hidden">
          <div class="flex items-baseline gap-1">
            <div class="text-xl font-bold text-gray-800 tracking-tight"
                 :class="{ 'animate-number': activeCard === 'degrees' }">
              {{ props.favoriteDegrees.length }}
            </div>
            <span class="text-xs text-gray-500">Saved Degrees</span>
          </div>
        </div>

        <!-- Favorite Degrees List -->
        <div class="transition-all duration-300 transform"
             :class="{ 'translate-y-0': activeCard === 'degrees', 'translate-y-4': activeCard !== 'degrees' }">
          <div class="max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
            <Link v-for="(degree, index) in props.favoriteDegrees" 
                  :key="degree.id"
                  :href="`/degree/${degree.slug}`"
                  class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/degree hover:bg-white/70 transition-all duration-200"
                  :style="{ transitionDelay: `${index * 50}ms` }"
                  :class="{ 'translate-x-0 opacity-100': activeCard === 'degrees', 'translate-x-4 opacity-0': activeCard !== 'degrees' }">
              <div class="w-7 h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <img :src="degree.image" 
                     :alt="degree.name"
                     class="w-full h-full object-contain filter contrast-125" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-700 truncate">{{ degree.name }}</h4>
              </div>
              <ArrowRight class="w-3.5 h-3.5 text-yellow-500 transform group-hover/degree:translate-x-0.5 transition-transform" />
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Watch Card -->
    <div class="relative bg-white/60 backdrop-blur-xl rounded-lg p-2 shadow-sm hover:shadow-md transition-all duration-300 flex-1 group"
         :style="{
           height: activeCard === 'watch' ? '280px' : '60px',
           marginTop: activeCard === 'watch' ? '-220px' : '0'
         }"
         @mouseenter="activeCard = 'watch'"
         @mouseleave="activeCard = null">
      <div class="flex items-center justify-between mb-1.5">
        <div class="flex items-center gap-2">
          <span class="text-sm font-medium text-gray-700">Watch</span>
          <!-- Compact counter - only visible when not active -->
          <span class="text-xs bg-yellow-400/20 px-2 py-0.5 rounded-full"
                :class="{ 'opacity-0': activeCard === 'watch' }">
            {{ streamingPlatforms.length }}
          </span>
        </div>
        <div class="h-5 w-5 bg-yellow-400 rounded-full flex items-center justify-center transform group-hover:rotate-45 transition-transform duration-300">
          <ArrowRight class="h-3.5 w-3.5 text-white" />
        </div>
      </div>

      <div class="space-y-1.5 overflow-hidden transition-all duration-300"
           :class="{ 'opacity-100': activeCard === 'watch', 'opacity-0': activeCard !== 'watch' }">
        <!-- Watch Counter -->
        <div class="relative overflow-hidden">
          <div class="flex items-baseline gap-1">
            <div class="text-xl font-bold text-gray-800 tracking-tight"
                 :class="{ 'animate-number': activeCard === 'watch' }">
              {{ streamingPlatforms.length }}
            </div>
            <span class="text-xs text-gray-500">Platforms</span>
          </div>
        </div>
        
        <!-- Streaming Services -->
        <div class="transition-all duration-300 transform"
             :class="{ 'translate-y-0': activeCard === 'watch', 'translate-y-4': activeCard !== 'watch' }">
          <div class="max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
            <Link v-for="(platform, index) in streamingPlatforms" 
                  :key="platform.name"
                  :href="`/watch/${platform.name.toLowerCase()}`"
                  class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/platform hover:bg-white/70 transition-all duration-200"
                  :style="{ transitionDelay: `${index * 50}ms` }"
                  :class="{ 'translate-x-0 opacity-100': activeCard === 'watch', 'translate-x-4 opacity-0': activeCard !== 'watch' }">
              <div class="w-7 h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <component :is="platform.icon" class="w-full h-full text-gray-700" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-700 truncate">{{ platform.name }}</h4>
              </div>
              <ArrowRight class="w-3.5 h-3.5 text-yellow-500 transform group-hover/platform:translate-x-0.5 transition-transform" />
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Play, Tv, Monitor, Laptop } from 'lucide-vue-next'

// Define props
const props = defineProps({
  favoriteJobs: {
    type: Array,
    required: true,
    default: () => []
  },
  favoriteDegrees: {
    type: Array,
    required: true,
    default: () => []
  }
})

// Reactive state
const activeCard = ref(null)

const streamingPlatforms = [
  { name: 'Crunchyroll', icon: Play },
  { name: 'Netflix', icon: Tv },
  { name: 'Funimation', icon: Monitor },
  { name: 'HIDIVE', icon: Laptop }
]
</script>

<style scoped>
@keyframes count-up {
  from { transform: translateY(100%); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes progress-fill {
  from { transform: scaleX(0); }
  to { transform: scaleX(1); }
}

.animate-number {
  animation: count-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-progress {
  transform-origin: left;
  animation: progress-fill 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.ease-out-expo {
  transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
}

/* Custom scrollbar for jobs list */
.custom-scrollbar::-webkit-scrollbar {
  width: 3px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(250, 204, 21, 0.3);
  border-radius: 1.5px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(250, 204, 21, 0.5);
}

/* Add new transition styles */
.group {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Smooth height transition */
.group {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Enhanced hover effect */
.group:hover {
  transform: translateY(-2px);
}

/* Content fade animation */
.space-y-1.5 {
  transition: opacity 0.3s ease-in-out;
}

/* Staggered animation for list items */
.group:hover .translate-x-4 {
  transition-delay: calc(var(--tw-translate-x) * 50ms);
}

/* Smooth scrolling */
.custom-scrollbar {
  scroll-behavior: smooth;
}
</style> 