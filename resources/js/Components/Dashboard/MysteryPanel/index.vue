<template>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="space-y-3">
      <div class="flex items-start justify-between">
        <h2 class="text-2xl md:text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
          Unlock Your Journey
        </h2>
        <div class="rarity-badge" :class="{ 'show-badge': showDetails }">
          <div class="px-2 md:px-3 py-1 md:py-1.5 bg-white/80 backdrop-blur-sm rounded-full shadow-lg relative overflow-hidden border border-indigo-200">
            <span class="text-xs md:text-sm font-medium relative z-10 text-indigo-600">
              Mystery Awaits
            </span>
            <div class="shine-effect"></div>
          </div>
        </div>
      </div>
      <p class="text-base md:text-xl text-gray-600 dark:text-gray-300 font-light leading-relaxed">
        Embark on a journey of self-discovery and unlock the secrets of your personality
      </p>
    </div>

    <UnlockCards :theme="theme" />
  </div>
</template>

<script setup>
import UnlockCards from './UnlockCards.vue'

defineProps({
  theme: {
    type: Object,
    required: true
  },
  showDetails: {
    type: Boolean,
    required: true
  }
})
</script> 