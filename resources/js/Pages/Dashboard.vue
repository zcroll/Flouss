<template>

  <Head title="Dashboard" />

  <!-- Avatar Section -->
  <div class="flex-1 flex items-center justify-center relative" :class="{ 'md:mt-[-10rem]': true }">
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative flex md:flex-row flex-col items-center md:gap-12 gap-6 px-4 md:px-0">
        <!-- Avatar Container with Glow -->
        <div class="relative md:w-[400px] md:h-[400px] w-[280px] h-[280px]" @mouseover="showDetails = true"
          @mouseleave="showDetails = false">
          <!-- Glow Effect -->
          <div :class="[
            `bg-${themeStore.currentTheme.primary}-400/20`,
            'absolute inset-0 rounded-full blur-3xl scale-90',
            { 'animate-pulse-slow': !isAvatarLoading }
          ]"></div>

          <ArchetypeAvatar v-if="archetypeSlug" :key="avatarKey" :archetype="archetypeSlug"
            class="w-full h-full transform scale-110 relative z-10" @loaded="handleAvatarLoaded" />
        </div>

        <!-- Details Panel -->
        <div class="md:w-[340px] w-full relative overflow-hidden" :class="{ 'pointer-events-none': !showDetails }">
          <div class="space-y-6 md:space-y-8 transition-all duration-500 ease-out" :class="[
            showDetails ? 'opacity-100 md:translate-x-0' : 'opacity-0 md:translate-x-20',
            'transform'
          ]">
            <!-- Title & Description -->
            <div class="space-y-3">
              <div class="flex items-start justify-between">
                <h2
                  class="text-2xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                  {{ archetype.slug }}
                </h2>
                <!-- Animated Rarity Badge -->
                <div class="rarity-badge" :class="{ 'show-badge': showDetails }">
                  <div :class="[
                    `border-${themeStore.currentTheme.border}`,
                    'px-2 md:px-3 py-1 md:py-1.5 bg-white/80 backdrop-blur-sm rounded-full shadow-lg relative overflow-hidden'
                  ]">
                    <span :class="[
                      `text-${themeStore.currentTheme.primary}-600`,
                      'text-xs md:text-sm font-medium relative z-10'
                    ]">
                      {{ archetype.rarity_string }}
                    </span>
                    <!-- Shine Effect -->
                    <div class="shine-effect"></div>
                  </div>
                </div>
              </div>
              <p class="text-base md:text-xl text-gray-600 font-light leading-relaxed">{{ archetype.rationale }}</p>
            </div>

            <!-- Stats -->
            <div class="space-y-4 md:space-y-6">
              <!-- Top Traits -->
              <div :class="[
                themeStore.getThemeClasses('border'),
                'bg-white/60 backdrop-blur-sm rounded-xl md:rounded-2xl p-4 md:p-6 shadow-xl transition-colors duration-300',
                themeStore.isDarkMode ? 'bg-gray-800/60' : 'bg-white/60'
              ]">
                <h3 :class="[
                  'text-base md:text-lg font-semibold mb-3 md:mb-4',
                  themeStore.isDarkMode ? 'text-white' : 'text-gray-800'
                ]">
                  Top Traits
                </h3>
                <div class="space-y-3 md:space-y-4">
                  <div v-for="(value, trait, index) in topTraits" :key="trait" class="trait-item" :style="{
                    '--delay': `${index * 200}ms`,
                    '--progress': showDetails ? value : 0
                  }">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5 md:mb-2">
                      <span class="font-medium" :class="[
                        `text-${themeStore.currentTheme.primary}-600`,
                        themeStore.isDarkMode ? `text-${themeStore.currentTheme.primary}-400` : `text-${themeStore.currentTheme.primary}-600`
                      ]">
                        {{ trait }}
                      </span>
                      <span :class="[
                        themeStore.isDarkMode ? 'text-red-400' : 'text-red-500'
                      ]">
                        {{ Math.round(value * 100) }}%
                      </span>
                    </div>
                    <div class="h-2 md:h-2.5 rounded-full overflow-hidden transition-colors duration-300" :class="[
                      themeStore.isDarkMode
                        ? `bg-${themeStore.currentTheme.primary}-900/50`
                        : `bg-${themeStore.currentTheme.primary}-100/50`
                    ]">
                      <div class="progress-bar h-full rounded-full transition-all duration-500" :class="[
                        themeStore.isDarkMode
                          ? `bg-${themeStore.currentTheme.primary}-400`
                          : `bg-${themeStore.currentTheme.primary}-500`
                      ]">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Personality Traits Panel -->
              <div :class="[
                themeStore.getThemeClasses('border'),
                'bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl md:rounded-2xl p-4 md:p-6 shadow-xl transition-all duration-300 hover:shadow-2xl',
              ]">
                <h3 :class="[
                  'text-base md:text-lg font-semibold mb-4',
                  themeStore.isDarkMode ? 'text-white' : 'text-gray-800'
                ]">
                  Personality Traits
                </h3>
                
                <div class="space-y-4">
                  <!-- Introverted/Extroverted Scale -->
                  <div class="trait-scale">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5">
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Extraverted</span>
                      <span class="font-medium" :class="`text-${themeStore.currentTheme.primary}-600 dark:text-${themeStore.currentTheme.primary}-400`">
                        51% Introverted
                      </span>
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Introverted</span>
                    </div>
                    <div class="h-2 rounded-full" :class="[
                      themeStore.isDarkMode ? `bg-${themeStore.currentTheme.primary}-900/30` : `bg-${themeStore.currentTheme.primary}-100/50`
                    ]">
                      <div class="h-full rounded-full transition-all duration-500" 
                           :class="[
                             themeStore.isDarkMode ? `bg-${themeStore.currentTheme.primary}-400` : `bg-${themeStore.currentTheme.primary}-500`
                           ]"
                           :style="{ width: '51%' }"></div>
                    </div>
                  </div>

                  <!-- Observant/Intuitive Scale -->
                  <div class="trait-scale">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5">
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Intuitive</span>
                      <span class="font-medium text-amber-600 dark:text-amber-400">59% Observant</span>
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Observant</span>
                    </div>
                    <div class="h-2 rounded-full bg-amber-100/50 dark:bg-amber-900/30">
                      <div class="h-full rounded-full transition-all duration-500 bg-amber-500 dark:bg-amber-400"
                           :style="{ width: '59%' }"></div>
                    </div>
                  </div>

                  <!-- Thinking/Feeling Scale -->
                  <div class="trait-scale">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5">
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Thinking</span>
                      <span class="font-medium text-emerald-600 dark:text-emerald-400">65% Thinking</span>
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Feeling</span>
                    </div>
                    <div class="h-2 rounded-full bg-emerald-100/50 dark:bg-emerald-900/30">
                      <div class="h-full rounded-full transition-all duration-500 bg-emerald-500 dark:bg-emerald-400"
                           :style="{ width: '65%' }"></div>
                    </div>
                  </div>

                  <!-- Judging/Prospecting Scale -->
                  <div class="trait-scale">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5">
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Judging</span>
                      <span class="font-medium text-purple-600 dark:text-purple-400">53% Prospecting</span>
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Prospecting</span>
                    </div>
                    <div class="h-2 rounded-full bg-purple-100/50 dark:bg-purple-900/30">
                      <div class="h-full rounded-full transition-all duration-500 bg-purple-500 dark:bg-purple-400"
                           :style="{ width: '53%' }"></div>
                    </div>
                  </div>

                  <!-- Assertive/Turbulent Scale -->
                  <div class="trait-scale">
                    <div class="flex justify-between text-xs md:text-sm mb-1.5">
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Assertive</span>
                      <span class="font-medium text-red-600 dark:text-red-400">53% Turbulent</span>
                      <span :class="themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-600'">Turbulent</span>
                    </div>
                    <div class="h-2 rounded-full bg-red-100/50 dark:bg-red-900/30">
                      <div class="h-full rounded-full transition-all duration-500 bg-red-500 dark:bg-red-400"
                           :style="{ width: '53%' }"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom section -->
  <div class="relative mt-8 md:mt-0">
    <BottomCards :favorite-jobs="favoriteJobs" :favorite-degrees="favoriteDegrees" :archetype="archetype.slug" />
    <AIChat :predefined-questions="predefinedQuestions" :initial-messages="chatHistory" class="z-50" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch, inject } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import ArchetypeAvatar from '@/Components/Result/Archetype_Avatar/ArchetypeAvatar.vue'
import BottomCards from '@/Components/BottomCards.vue'
import AIChat from '@/Components/Dashboard/AIChat.vue'
import { useThemeStore } from '@/stores/theme/themeStore'
import { useNavigationStore } from '@/stores/navigation/navigationStore'

const props = defineProps({
  hasResult: {
    type: Boolean,
    required: true
  },
  favoriteJobs: {
    type: Array,
    required: true
  },
  favoriteDegrees: {
    type: Array,
    required: true
  },
  archetype: {
    type: Object,
    required: true,
    default: () => ({
      slug: '',
      rarity_string: '',
      rationale: ''
    })
  },
  topTraits: {
    type: Object,
    required: true,
    default: () => ({})
  },
  chatHistory: {
    type: Array,
    required: true
  },
  predefinedQuestions: {
    type: Array,
    required: true
  }
})

defineOptions({
  layout: MainLayout
})

const showDetails = ref(false)
const themeStore = useThemeStore()
const navigationStore = useNavigationStore()
const avatarKey = ref(0)
const isAvatarLoading = ref(true)
const isLayoutInitialized = inject('isLayoutInitialized')
const isComponentMounted = ref(false)

// Computed property for archetype slug
const archetypeSlug = computed(() => props.archetype.slug)

// Handle avatar loaded event
const handleAvatarLoaded = () => {
  isAvatarLoading.value = false;
  showDetails.value = true;
};

// Initialize component
const initializeComponent = async () => {
  if (!isComponentMounted.value || !isLayoutInitialized.value) return;

  console.log('Initializing Dashboard component');
  isAvatarLoading.value = true;

  try {
    await nextTick();
    if (props.archetype.slug) {
      console.log('Loading avatar for archetype:', props.archetype.slug);
      avatarKey.value++;
    }
  } catch (error) {
    console.error('Error initializing Dashboard:', error);
  }
};

// Watch for layout initialization
watch([isLayoutInitialized, () => isComponentMounted.value], async ([layoutInit, componentInit]) => {
  if (layoutInit && componentInit) {
    await initializeComponent();
  }
}, { immediate: true });

// Watch for archetype changes
watch(() => props.archetype, async (newArchetype) => {
  if (!isComponentMounted.value || !isLayoutInitialized.value) return;

  isAvatarLoading.value = true;
  await nextTick();
  avatarKey.value++;
}, { deep: true });

onMounted(async () => {
  isComponentMounted.value = true;
  if (isLayoutInitialized.value) {
    await initializeComponent();
  }
});

// Debug logging
console.log('Dashboard setup with:', {
  archetype: archetypeSlug.value,
  theme: themeStore.currentTheme,
  layoutInitialized: isLayoutInitialized.value,
  componentMounted: isComponentMounted.value
})
</script>

<style scoped>
.font-japanese {
  font-family: "Noto Sans JP", sans-serif;
}

.font-display {
  font-family: "Montserrat", sans-serif;
}

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

/* Enhanced animations */
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

/* Transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Hover Effects */
.trait-item:hover .progress-bar {
  filter: brightness(1.1);
  transform: scaleY(1.2);
  transition: all 0.3s ease;
}

/* Glass Effects */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
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

/* Make the badge slightly more glossy */
.rarity-badge div {
  background: linear-gradient(135deg,
      rgba(255, 255, 255, 0.9),
      rgba(255, 255, 255, 0.6));
}

/* Mobile-specific animations */
@media (max-width: 768px) {
  .trait-item {
    animation-duration: 0.3s;
  }

  .rarity-badge {
    transition-delay: 0.1s;
  }
}

/* Adjust shine effect for mobile */
@media (max-width: 768px) {
  .shine-effect {
    width: 30%;
  }
}

/* Ensure smooth scrolling on mobile */
@media (max-width: 768px) {
  .custom-scrollbar {
    -webkit-overflow-scrolling: touch;
  }
}

/* Add these to your existing styles */
.trait-scale {
  opacity: 0;
  transform: translateX(-20px);
  animation: slideIn 0.5s ease forwards;
  position: relative;
  overflow: hidden;
}

.trait-scale::after {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: linear-gradient(
    to right,
    transparent,
    rgba(255, 255, 255, 0.1),
    transparent
  );
  transform: translateX(-100%);
  transition: transform 0.5s ease;
}

.trait-scale:hover::after {
  transform: translateX(100%);
}

.trait-scale:nth-child(1) { animation-delay: 100ms; }
.trait-scale:nth-child(2) { animation-delay: 200ms; }
.trait-scale:nth-child(3) { animation-delay: 300ms; }
.trait-scale:nth-child(4) { animation-delay: 400ms; }
.trait-scale:nth-child(5) { animation-delay: 500ms; }

@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Enhanced hover effects */
.trait-scale:hover .h-full {
  filter: brightness(1.1);
  transform: scaleY(1.1);
  transition: all 0.3s ease;
}

/* Glass effect enhancement */
.backdrop-blur-sm {
  backdrop-filter: blur(8px) saturate(180%);
  -webkit-backdrop-filter: blur(8px) saturate(180%);
}
</style>
