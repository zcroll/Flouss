<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useThemeStore } from '@/stores/theme/themeStore'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()

const themeStore = useThemeStore()
</script>

<template>
  <div
    :class="
      cn(
        'rounded-lg backdrop-blur-sm bg-white/30 dark:bg-gray-800/30 text-card-foreground shadow-lg',
        `border-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '900' : '300'}`,
        'border transition-[border] duration-200 ease-in-out',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
