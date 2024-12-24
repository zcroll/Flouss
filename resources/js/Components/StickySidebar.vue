<template>
  <div class="relative">
    <div 
      class="sticky transition-all duration-300"
      :class="[
        stickyPosition === 'top' ? 'top-4' : 'bottom-4',
        width === 'full' ? 'w-full' : ''
      ]"
    >
      <div 
        class="rounded-2xl overflow-hidden backdrop-blur-sm"
        :style="{
          backgroundColor: `rgb(var(--${themeColors.primary}-rgb), 0.03)`,
          borderColor: `rgb(var(--${themeColors.primary}-rgb), 0.08)`,
          borderWidth: '1px'
        }"
      >
        <!-- Header -->
        <div v-if="title || $slots.header" 
             class="p-4 border-b"
             :style="{
               borderColor: `rgb(var(--${themeColors.primary}-rgb), 0.08)`
             }">
          <slot name="header">
            <h3 :class="[
              classes.text,
              'text-lg font-semibold'
            ]">{{ title }}</h3>
          </slot>
        </div>

        <!-- Content -->
        <div :class="[
          contentPadding ? 'p-4' : '',
          { 'space-y-4': contentSpacing }
        ]">
          <slot></slot>
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" 
             class="p-4 border-t"
             :style="{
               borderColor: `rgb(var(--${themeColors.primary}-rgb), 0.08)`
             }">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useArchetypeTheme } from '@/composables/useArchetypeTheme'

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  stickyPosition: {
    type: String,
    default: 'top',
    validator: (value) => ['top', 'bottom'].includes(value)
  },
  width: {
    type: String,
    default: 'auto',
    validator: (value) => ['auto', 'full'].includes(value)
  },
  contentPadding: {
    type: Boolean,
    default: true
  },
  contentSpacing: {
    type: Boolean,
    default: true
  },
  archetype: {
    type: String,
    required: true
  }
})

// Theme
const archetypeRef = computed(() => props.archetype)
const { classes, themeColors } = useArchetypeTheme(archetypeRef)
</script> 