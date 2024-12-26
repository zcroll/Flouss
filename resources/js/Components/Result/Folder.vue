<template>
  <div class="w-full h-full flex items-center justify-center">
    <div class="flex-1 flex items-center justify-center">
      <div class="relative flex items-center gap-12 max-w-6xl mx-auto px-6">
        <!-- Avatar Container with Glow -->
        <div class="relative w-[400px] h-[400px]" @mouseover="showDetails = true" @mouseleave="showDetails = false">
          <!-- Glow Effect -->
          <div :class="[
            `bg-${themeStore.currentTheme.button.primary}-400/20`,
            'absolute inset-0 rounded-full blur-3xl scale-90 animate-pulse-slow'
          ]"></div>

          <ArchetypeAvatar :archetype="archetypeDiscovery?.slug || 'default'"
            class="w-full h-full transform scale-110 relative z-10" />
        </div>

        <!-- Details Panel -->
        <div class="w-[340px] relative overflow-hidden" :class="{ 'pointer-events-none': !showDetails }">
          <div class="space-y-8 transition-all duration-500 ease-out" :class="[
            showDetails ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-20',
            'transform'
          ]">
            <!-- Title & Description -->
            <div class="space-y-3">
              <div class="flex items-start justify-between">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                  {{ archetypeDiscovery?.slug }}
                </h2>
                <!-- Animated Rarity Badge -->
                <div class="rarity-badge" :class="{ 'show-badge': showDetails }">
                  <div :class="[
                    `border-${themeStore.currentTheme.border.primary}`,
                    'px-3 py-1.5 bg-white/80 backdrop-blur-sm rounded-full shadow-lg relative overflow-hidden'
                  ]">
                    <span :class="[
                      `text-${themeStore.currentTheme.button.primary}-600`,
                      'text-sm font-medium relative z-10'
                    ]">
                      {{ archetypeDiscovery?.rarity_string }}
                    </span>
                    <!-- Shine Effect -->
                    <div class="shine-effect"></div>
                  </div>
                </div>
              </div>
              <p class="text-xl text-gray-600 font-light leading-relaxed">
                {{ archetypeDiscovery?.rationale }}
              </p>
            </div>

            <!-- Stats -->
            <div class="space-y-6">
              <!-- Top Traits - Exactly like Dashboard -->
              <div :class="[
                themeStore.getThemeClasses('border'),
                'bg-white/60 backdrop-blur-sm rounded-2xl p-6 shadow-xl transition-colors duration-300',
                themeStore.isDarkMode ? 'bg-gray-800/60' : 'bg-white/60'
              ]">
                <h3 :class="[
                  'text-lg font-semibold mb-4',
                  themeStore.isDarkMode ? 'text-white' : 'text-gray-800'
                ]">
                  Top Traits
                </h3>
                <div class="space-y-4">
                  <div v-for="(value, trait, index) in topTraits" :key="trait" class="trait-item" :style="{
                    '--delay': `${index * 200}ms`,
                    '--progress': showDetails ? value : 0
                  }">
                    <div class="flex justify-between text-sm mb-2">
                      <span class="font-medium" :class="[
                        `text-${themeStore.currentTheme.button.primary}-600`,
                        themeStore.isDarkMode ? `text-${themeStore.currentTheme.button.primary}-400` : `text-${themeStore.currentTheme.button.primary}-600`
                      ]">
                        {{ trait }}
                      </span>
                      <span :class="[
                        themeStore.isDarkMode ? 'text-red-400' : 'text-red-500'
                      ]">
                        {{ Math.round(value * 100) }}%
                      </span>
                    </div>
                    <div class="h-2.5 rounded-full overflow-hidden transition-colors duration-300" :class="[
                      themeStore.isDarkMode
                        ? `bg-${themeStore.currentTheme.button.primary}-900/50`
                        : `bg-${themeStore.currentTheme.button.primary}-100/50`
                    ]">
                      <div class="progress-bar h-full rounded-full transition-all duration-500" :class="[
                        themeStore.isDarkMode
                          ? `bg-${themeStore.currentTheme.button.primary}-400`
                          : `bg-${themeStore.currentTheme.button.primary}-500`
                      ]">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- View Report Button -->
              <Link :href="`/results/${userId}/personality`"
                class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                :class="[
                  themeStore.getThemeClasses('button'),
                  themeStore.getThemeClasses('hover'),
                  'text-white shadow-sm',
                  themeStore.getThemeClasses('border'),
                  'focus:ring-2 focus:ring-offset-2 focus:outline-none',
                  `focus:ring-${themeStore.currentTheme.button.primary}`
                ]" tabindex="0">
              {{ __('results.view_report') }}
              <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" />
              </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import ArchetypeAvatar from './Archetype_Avatar/ArchetypeAvatar.vue';

const themeStore = useThemeStore();
const isMobile = ref(window.innerWidth <= 768);
const showDetails = ref(false);

const props = defineProps({
  userId: {
    type: String,
    required: true
  },
  archetypeDiscovery: {
    type: Object,
    required: true
  },
  topTraits: {
    type: Object,
    required: true,
    default: () => ({})
  }
});

const avatars = [
  { slug: 'Advocat' },
  { slug: 'Architect' },
  { slug: 'Captain' },
  { slug: 'Caregiver' },
  { slug: 'Composer' },
  { slug: 'Designer' },
  { slug: 'Enthusiast' },
  { slug: 'Visionary' },
  { slug: 'Producer' },
  { slug: 'Protector' },
  { slug: 'Groundbreaker' },
  { slug: 'Humanitarian' },
  // ... rest of the avatars
];

const selectAvatar = (slug) => {
  console.log(`Selected avatar: ${slug}`);
};

const updateIsMobile = () => {
  isMobile.value = window.innerWidth <= 768;
};

onMounted(() => {
  window.addEventListener('resize', updateIsMobile);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateIsMobile);
});
</script>

<style scoped>
/* Animations from Dashboard */
.font-japanese {
  font-family: "Noto Sans JP", sans-serif;
}

.font-display {
  font-family: "Montserrat", sans-serif;
}

@keyframes pulse-slow {

  0%,
  100% {
    opacity: 0.4;
    transform: scale(0.9);
  }

  50% {
    opacity: 0.6;
    transform: scale(0.95);
  }
}

.animate-pulse-slow {
  animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Enhanced shine effect */
.shine-effect {
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(120deg,
      transparent,
      rgba(255, 255, 255, 0.6),
      transparent);
  animation: shine 3s infinite;
}

@keyframes shine {
  0% {
    left: -100%;
  }

  20% {
    left: 100%;
  }

  100% {
    left: 100%;
  }
}

/* Rarity Badge Animation */
.rarity-badge {
  opacity: 0;
  transform: translateY(-10px) scale(0.9);
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  transition-delay: 0.2s;
}

.rarity-badge.show-badge {
  opacity: 1;
  transform: translateY(0) scale(1);
}

.rarity-badge div {
  background: linear-gradient(135deg,
      rgba(255, 255, 255, 0.9),
      rgba(255, 255, 255, 0.6));
}

/* Glass Effects */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Import existing styles */
@import 'public/css/folder.css';
@import 'public/css/career_list.css';

/* Add trait animation styles */
.trait-item {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s ease forwards;
  animation-delay: var(--delay);
}

.progress-bar {
  width: calc(var(--progress) * 100%);
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Hover effect for trait bars */
.trait-item:hover .progress-bar {
  filter: brightness(1.1);
  transform: scaleY(1.2);
  transition: all 0.3s ease;
}
</style>
