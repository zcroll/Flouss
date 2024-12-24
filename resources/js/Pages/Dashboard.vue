<template>
  <Head title="Dashboard" />
  
  <!-- Avatar Section -->
  <div class="flex-1 flex items-center justify-center relative" style="margin-top: -10rem">
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative flex items-center gap-12">
        <!-- Avatar Container with Glow -->
        <div class="relative w-[400px] h-[400px]" 
             @mouseover="showDetails = true"
             @mouseleave="showDetails = false">
          <!-- Glow Effect -->
          <div class="absolute inset-0 bg-yellow-400/20 rounded-full blur-3xl scale-90 animate-pulse-slow"></div>
          
          <ArchetypeAvatar 
            :archetype="archetype.slug" 
            class="w-full h-full transform scale-110 relative z-10"
          />
        </div>

        <!-- Details Panel -->
        <div class="w-[340px] relative overflow-hidden"
             :class="{ 'pointer-events-none': !showDetails }">
          <div class="space-y-8 transition-all duration-500 ease-out"
               :class="[
                 showDetails ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-20',
                 'transform'
               ]">
            <!-- Title & Description -->
            <div class="space-y-3">
              <div class="flex items-start justify-between">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                  {{ archetype.slug }}
                </h2>
                <!-- Animated Rarity Badge -->
                <div class="rarity-badge"
                     :class="{ 'show-badge': showDetails }">
                  <div class="px-3 py-1.5 bg-white/80 backdrop-blur-sm rounded-full border border-yellow-400/20 shadow-lg relative overflow-hidden">
                    <span class="text-sm font-medium bg-gradient-to-r from-yellow-600 to-yellow-400 bg-clip-text text-transparent relative z-10">
                      {{ archetype.rarity_string }}
                    </span>
                    <!-- Shine Effect -->
                    <div class="shine-effect"></div>
                  </div>
                </div>
              </div>
              <p class="text-xl text-gray-600 font-light leading-relaxed">{{ archetype.rationale }}</p>
            </div>

            <!-- Stats -->
            <div class="space-y-6">
              <!-- Top Traits -->
              <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Traits</h3>
                <div class="space-y-4">
                  <div v-for="(value, trait, index) in topTraits" 
                       :key="trait"
                       class="trait-item"
                       :style="{
                         '--delay': `${index * 200}ms`,
                         '--progress': showDetails ? value : 0
                       }">
                    <div class="flex justify-between text-sm mb-2">
                      <span class="font-medium text-gray-700">{{ trait }}</span>
                      <span class="text-gray-500">{{ Math.round(value * 100) }}%</span>
                    </div>
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                      <div class="progress-bar h-full bg-gradient-to-r from-yellow-400 to-yellow-300 rounded-full">
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
  </div>

  <!-- Bottom section -->
  <div class="relative">
    <BottomCards :favorite-jobs="favoriteJobs" :favorite-degrees="favoriteDegrees" />
    <AIChat 
      :predefined-questions="predefinedQuestions"
      :initial-messages="chatHistory"
      class="z-50"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import ArchetypeAvatar from '@/Components/Result/Archetype_Avatar/ArchetypeAvatar.vue'
import BottomCards from '@/Components/BottomCards.vue'
import AIChat from '@/Components/Dashboard/AIChat.vue'

defineProps({
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
  layout: MainLayout,
})

const showDetails = ref(false)
</script>

<style scoped>
.font-japanese {
  font-family: "Noto Sans JP", sans-serif;
}

.font-display {
  font-family: "Montserrat", sans-serif;
}

.trait-item {
  --progress: 0;
  --delay: 0ms;
}

.progress-bar {
  width: calc(var(--progress) * 100%);
  transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1) var(--delay);
}

/* Animations */
@keyframes pulse-slow {
  0%, 100% {
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

/* Shine Effect */
.shine-effect {
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(
    120deg,
    transparent,
    rgba(255, 255, 255, 0.6),
    transparent
  );
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

/* Make the badge slightly more glossy */
.rarity-badge div {
  background: linear-gradient(
    135deg,
    rgba(255, 255, 255, 0.9),
    rgba(255, 255, 255, 0.6)
  );
}
</style>
