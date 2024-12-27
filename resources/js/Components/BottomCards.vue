<template>
  <div class="absolute bottom-2 left-2 right-2 flex md:flex-row flex-col gap-2">
    <!-- Complete Card (Favorite Jobs) -->
    <div class="relative flex-1 group rounded-2xl backdrop-blur-sm transition-all duration-300" :style="{
      height: activeCard === 'complete' ? (isMobile ? '220px' : '280px') : (isMobile ? '50px' : '60px'),
      marginTop: activeCard === 'complete' ? (isMobile ? '-170px' : '-220px') : '0',
      zIndex: activeCard === 'complete' ? 10 : 1,
      backgroundColor: `rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.05)`,
      border: `1px solid rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.1)`
    }" @click="toggleCard('complete')" @mouseenter="!isMobile && (activeCard = 'complete')"
      @mouseleave="!isMobile && (activeCard = null)">
      <div class="p-2">
        <div class="flex items-center justify-between mb-1.5">
          <div class="flex items-center gap-2">
            <span :class="[`text-${themeStore.currentTheme.primary}-600`, 'text-sm font-medium']">Favorite Jobs</span>
            <span :class="[
              `bg-${themeStore.currentTheme.primary}-100/30`,
              `text-${themeStore.currentTheme.primary}-600`,
              'text-xs px-2 py-0.5 rounded-full',
              { 'opacity-0': activeCard === 'complete' }
            ]">{{ props.favoriteJobs.length }}</span>
          </div>
          <div :class="[
            `bg-${themeStore.currentTheme.button}`,
            'h-5 w-5 rounded-full flex items-center justify-center transition-transform duration-300',
            { 'rotate-90': activeCard === 'complete' }
          ]">
            <ArrowRight class="h-3.5 w-3.5 text-white" />
          </div>
        </div>

        <!-- Card Content -->
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
            <div class="max-h-[140px] md:max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
              <Link v-for="(job, index) in props.favoriteJobs" :key="job.id" :href="`/career/${job.slug}`"
                class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/job hover:bg-white/70 active:bg-white/90 transition-all duration-200"
                :style="{ transitionDelay: `${index * 50}ms` }"
                :class="{ 'translate-x-0 opacity-100': activeCard === 'complete', 'translate-x-4 opacity-0': activeCard !== 'complete' }">
              <div class="w-6 md:w-7 h-6 md:h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <img :src="job.image" :alt="job.name" class="w-full h-full object-contain filter contrast-125" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs md:text-sm font-medium text-gray-700 truncate">{{ job.name }}</h4>
              </div>
              <ArrowRight :class="[
                `text-${themeStore.currentTheme.primary}-600`,
                'w-3 md:w-3.5 h-3 md:h-3.5 transform group-hover/job:translate-x-0.5 transition-transform'
              ]" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Favorite Degrees Card -->
    <div class="relative flex-1 group rounded-2xl backdrop-blur-sm transition-all duration-300" :style="{
      height: activeCard === 'degrees' ? (isMobile ? '220px' : '280px') : (isMobile ? '50px' : '60px'),
      marginTop: activeCard === 'degrees' ? (isMobile ? '-170px' : '-220px') : '0',
      zIndex: activeCard === 'degrees' ? 10 : 1,
      backgroundColor: `rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.05)`,
      border: `1px solid rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.1)`
    }" @click="toggleCard('degrees')" @mouseenter="!isMobile && (activeCard = 'degrees')"
      @mouseleave="!isMobile && (activeCard = null)">
      <div class="p-2">
        <div class="flex items-center justify-between mb-1.5">
          <div class="flex items-center gap-2">
            <span :class="[`text-${themeStore.currentTheme.primary}-600`, 'text-sm font-medium']">Favorite
              Degrees</span>
            <span :class="[
              `bg-${themeStore.currentTheme.primary}-100/30`,
              `text-${themeStore.currentTheme.primary}-600`,
              'text-xs px-2 py-0.5 rounded-full',
              { 'opacity-0': activeCard === 'degrees' }
            ]">{{ props.favoriteDegrees.length }}</span>
          </div>
          <div :class="[
            `bg-${themeStore.currentTheme.button}`,
            'h-5 w-5 rounded-full flex items-center justify-center transition-transform duration-300',
            { 'rotate-90': activeCard === 'degrees' }
          ]">
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
            <div class="max-h-[140px] md:max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
              <Link v-for="(degree, index) in props.favoriteDegrees" :key="degree.id" :href="`/degree/${degree.slug}`"
                class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/degree hover:bg-white/70 active:bg-white/90 transition-all duration-200"
                :style="{ transitionDelay: `${index * 50}ms` }"
                :class="{ 'translate-x-0 opacity-100': activeCard === 'degrees', 'translate-x-4 opacity-0': activeCard !== 'degrees' }">
              <div class="w-6 md:w-7 h-6 md:h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <img :src="degree.image" :alt="degree.name" class="w-full h-full object-contain filter contrast-125" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs md:text-sm font-medium text-gray-700 truncate">{{ degree.name }}</h4>
              </div>
              <ArrowRight :class="[
                `text-${themeStore.currentTheme.primary}-600`,
                'w-3 md:w-3.5 h-3 md:h-3.5 transform group-hover/degree:translate-x-0.5 transition-transform'
              ]" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Watch Card -->
    <div class="relative flex-1 group rounded-2xl backdrop-blur-sm transition-all duration-300" :style="{
      height: activeCard === 'watch' ? (isMobile ? '220px' : '280px') : (isMobile ? '50px' : '60px'),
      marginTop: activeCard === 'watch' ? (isMobile ? '-170px' : '-220px') : '0',
      zIndex: activeCard === 'watch' ? 10 : 1,
      backgroundColor: `rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.05)`,
      border: `1px solid rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.1)`
    }" @click="toggleCard('watch')" @mouseenter="!isMobile && (activeCard = 'watch')"
      @mouseleave="!isMobile && (activeCard = null)">
      <div class="p-2">
        <div class="flex items-center justify-between mb-1.5">
          <div class="flex items-center gap-2">
            <span :class="[`text-${themeStore.currentTheme.primary}-600`, 'text-sm font-medium']">Watch</span>
            <span :class="[
              `bg-${themeStore.currentTheme.primary}-100/30`,
              `text-${themeStore.currentTheme.primary}-600`,
              'text-xs px-2 py-0.5 rounded-full',
              { 'opacity-0': activeCard === 'watch' }
            ]">{{ streamingPlatforms.length }}</span>
          </div>
          <div :class="[
            `bg-${themeStore.currentTheme.button}`,
            'h-5 w-5 rounded-full flex items-center justify-center transition-transform duration-300',
            { 'rotate-90': activeCard === 'watch' }
          ]">
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
            <div class="max-h-[140px] md:max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
              <Link v-for="(platform, index) in streamingPlatforms" :key="platform.name"
                :href="`/watch/${platform.name.toLowerCase()}`"
                class="flex items-center gap-1.5 p-1.5 rounded-lg bg-white/50 backdrop-blur-sm mb-1 group/platform hover:bg-white/70 active:bg-white/90 transition-all duration-200"
                :style="{ transitionDelay: `${index * 50}ms` }"
                :class="{ 'translate-x-0 opacity-100': activeCard === 'watch', 'translate-x-4 opacity-0': activeCard !== 'watch' }">
              <div class="w-6 md:w-7 h-6 md:h-7 rounded-lg overflow-hidden bg-white/50 p-1.5 backdrop-blur-sm">
                <component :is="platform.icon" class="w-full h-full text-gray-700" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs md:text-sm font-medium text-gray-700 truncate">{{ platform.name }}</h4>
              </div>
              <ArrowRight :class="[
                `text-${themeStore.currentTheme.primary}-600`,
                'w-3 md:w-3.5 h-3 md:h-3.5 transform group-hover/platform:translate-x-0.5 transition-transform'
              ]" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Play, Tv, Monitor, Laptop } from 'lucide-vue-next'
import { useThemeStore } from '@/stores/theme/themeStore'

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
const isMobile = ref(false)

// Initialize theme store
const themeStore = useThemeStore()

const streamingPlatforms = [
  { name: 'Crunchyroll', icon: Play },
  { name: 'Netflix', icon: Tv },
  { name: 'Funimation', icon: Monitor },
  { name: 'HIDIVE', icon: Laptop }
]

// Check if device is mobile
onMounted(() => {
  const checkMobile = () => {
    isMobile.value = window.innerWidth < 768
  }
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

// Toggle card for mobile
const toggleCard = (cardName) => {
  if (isMobile.value) {
    activeCard.value = activeCard.value === cardName ? null : cardName
  }
}
</script>

<style scoped>
.group {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.group:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

/* Mobile optimizations */
@media (max-width: 768px) {
  .group {
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
  }

  .group:active {
    transform: scale(0.98);
  }
}

.custom-scrollbar::-webkit-scrollbar {
  width: 3px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  @apply rounded-full;
  background-color: v-bind('`rgb(var(--${themeStore.currentTheme.primary}-rgb), 0.2)`');
}

@keyframes count-up {
  from {
    transform: translateY(100%);
    opacity: 0;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.animate-number {
  animation: count-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Touch-friendly scrolling */
@media (max-width: 768px) {
  .custom-scrollbar {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }

  .custom-scrollbar::-webkit-scrollbar {
    display: none;
  }
}
</style>