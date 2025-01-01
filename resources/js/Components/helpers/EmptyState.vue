<template>
  <div :class="[
    'text-center py-12 px-4 border-2 border-dashed rounded-2xl backdrop-blur-xl transition duration-300',
    `border-${themeStore.currentTheme.border}`,
    themeStore.getThemeClasses('background', 'light')
  ]">
    <div class="flex flex-col items-center">
      <!-- Empty box illustration -->
      <svg :class="[
        'w-20 h-20 mb-4',
        `text-${themeStore.currentTheme.button}`
      ]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
      </svg>
      
      <!-- Title -->
      <h3 class="text-lg font-medium text-gray-900 mb-2">
        {{ title }}
      </h3>
      
      <!-- Description -->
      <p class="text-gray-500 max-w-sm mb-6">
        {{ description }}
      </p>
      
      <!-- Optional action button -->
      <slot name="action">
        <button
          v-if="buttonText"
          @click="$emit('action')"
          :class="[
            'inline-flex items-center px-4 py-2 border rounded-lg text-sm font-medium transition-colors duration-200',
            `border-${themeStore.currentTheme.button}`,
            `text-${themeStore.currentTheme.button}`,
            `hover:bg-${themeStore.currentTheme.button}`,
            'hover:text-white'
          ]"
        >
          {{ buttonText }}
        </button>
      </slot>
    </div>
  </div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme/themeStore'

const themeStore = useThemeStore()

defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  buttonText: {
    type: String,
    default: ''
  }
})

defineEmits(['action'])
</script>