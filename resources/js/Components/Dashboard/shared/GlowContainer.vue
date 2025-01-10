<template>
  <div class="relative md:w-[400px] md:h-[400px] w-[280px] h-[280px]" 
       @mouseover="$emit('show-details')"
       @mouseleave="$emit('hide-details')">
    <!-- Glow Effect -->
    <div :class="[
      `bg-${theme.primary}-400/20`,
      'absolute inset-0 rounded-full blur-3xl scale-90',
      { 'animate-pulse-slow': shouldPulse }
    ]"></div>
    
    <slot></slot>
  </div>
</template>

<script setup>
defineProps({
  theme: {
    type: Object,
    required: true
  },
  shouldPulse: {
    type: Boolean,
    default: true
  }
})

defineEmits(['show-details', 'hide-details'])
</script> 