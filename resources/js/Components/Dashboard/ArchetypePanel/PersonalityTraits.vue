<template>
  <div :class="[
    getBorderClass(),
    'bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl md:rounded-2xl p-4 md:p-6 shadow-xl transition-all duration-300 hover:shadow-2xl',
  ]">
    <h3 :class="[
      'text-base md:text-lg font-semibold mb-4',
      theme.isDarkMode ? 'text-white' : 'text-gray-800'
    ]">
      Personality Traits
    </h3>
    
    <div class="space-y-4">
      <div v-for="(trait, index) in personalityTraits" 
           :key="index"
           class="trait-scale">
        <div class="flex justify-between text-xs md:text-sm mb-1.5">
          <span :class="theme.isDarkMode ? 'text-gray-400' : 'text-gray-600'">
            {{ trait.left }}
          </span>
          <span class="font-medium" :class="trait.color">
            {{ trait.value }}% {{ trait.dominant }}
          </span>
          <span :class="theme.isDarkMode ? 'text-gray-400' : 'text-gray-600'">
            {{ trait.right }}
          </span>
        </div>
        <div class="h-2 rounded-full" :class="trait.bgClass">
          <div class="h-full rounded-full transition-all duration-500" 
               :class="trait.barClass"
               :style="{ width: `${trait.value}%` }">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useThemeStore } from '@/stores/theme/themeStore'

const themeStore = useThemeStore()

const props = defineProps({
  theme: {
    type: Object,
    required: true
  }
})

// Helper function to get border class
const getBorderClass = () => {
  return `border border-${props.theme.primary}-200 dark:border-${props.theme.primary}-800`
}

// Theme colors for each trait
const traitColors = {
  mind: {
    color: 'blue',
    darkClass: 'bg-blue-400',
    lightClass: 'bg-blue-500',
    bgDark: 'bg-blue-900/30',
    bgLight: 'bg-blue-100/50'
  },
  energy: {
    color: 'amber',
    darkClass: 'bg-amber-400',
    lightClass: 'bg-amber-500',
    bgDark: 'bg-amber-900/30',
    bgLight: 'bg-amber-100/50'
  },
  nature: {
    color: 'emerald',
    darkClass: 'bg-emerald-400',
    lightClass: 'bg-emerald-500',
    bgDark: 'bg-emerald-900/30',
    bgLight: 'bg-emerald-100/50'
  },
  tactics: {
    color: 'violet',
    darkClass: 'bg-violet-400',
    lightClass: 'bg-violet-500',
    bgDark: 'bg-violet-900/30',
    bgLight: 'bg-violet-100/50'
  },
  identity: {
    color: 'rose',
    darkClass: 'bg-rose-400',
    lightClass: 'bg-rose-500',
    bgDark: 'bg-rose-900/30',
    bgLight: 'bg-rose-100/50'
  }
}

const personalityTraits = computed(() => [
  {
    left: 'Extraverted',
    right: 'Introverted',
    value: 53,
    dominant: 'Introverted',
    color: `text-${traitColors.mind.color}-600 dark:text-${traitColors.mind.color}-400`,
    bgClass: props.theme.isDarkMode ? traitColors.mind.bgDark : traitColors.mind.bgLight,
    barClass: props.theme.isDarkMode ? traitColors.mind.darkClass : traitColors.mind.lightClass
  },
  {
    left: 'Intuitive',
    right: 'Observant',
    value: 53,
    dominant: 'Intuitive',
    color: `text-${traitColors.energy.color}-600 dark:text-${traitColors.energy.color}-400`,
    bgClass: props.theme.isDarkMode ? traitColors.energy.bgDark : traitColors.energy.bgLight,
    barClass: props.theme.isDarkMode ? traitColors.energy.darkClass : traitColors.energy.lightClass
  },
  {
    left: 'Thinking',
    right: 'Feeling',
    value: 54,
    dominant: 'Feeling',
    color: `text-${traitColors.nature.color}-600 dark:text-${traitColors.nature.color}-400`,
    bgClass: props.theme.isDarkMode ? traitColors.nature.bgDark : traitColors.nature.bgLight,
    barClass: props.theme.isDarkMode ? traitColors.nature.darkClass : traitColors.nature.lightClass
  },
  {
    left: 'Judging',
    right: 'Prospecting',
    value: 58,
    dominant: 'Prospecting',
    color: `text-${traitColors.tactics.color}-600 dark:text-${traitColors.tactics.color}-400`,
    bgClass: props.theme.isDarkMode ? traitColors.tactics.bgDark : traitColors.tactics.bgLight,
    barClass: props.theme.isDarkMode ? traitColors.tactics.darkClass : traitColors.tactics.lightClass
  },
  {
    left: 'Assertive',
    right: 'Turbulent',
    value: 54,
    dominant: 'Assertive',
    color: `text-${traitColors.identity.color}-600 dark:text-${traitColors.identity.color}-400`,
    bgClass: props.theme.isDarkMode ? traitColors.identity.bgDark : traitColors.identity.bgLight,
    barClass: props.theme.isDarkMode ? traitColors.identity.darkClass : traitColors.identity.lightClass
  }
])
</script>

<style scoped>
.trait-scale {
  opacity: 0;
  transform: translateX(-20px);
  animation: slideIn 0.5s ease forwards;
  position: relative;
  overflow: hidden;
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
</style> 