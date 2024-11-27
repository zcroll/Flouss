<script setup>
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
  },
  size: {
    type: String,
    default: 'default',
  },
})

const buttonVariants = computed(() => {
  return cn(
    'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background',
    {
      'bg-primary text-primary-foreground hover:bg-primary/90': props.variant === 'default',
      'bg-destructive text-destructive-foreground hover:bg-destructive/90': props.variant === 'destructive',
      'border border-input hover:bg-accent hover:text-accent-foreground': props.variant === 'outline',
      'hover:bg-accent hover:text-accent-foreground': props.variant === 'ghost',
      'bg-secondary text-secondary-foreground hover:bg-secondary/80': props.variant === 'secondary',
      'underline-offset-4 hover:underline text-primary': props.variant === 'link',
      'h-10 py-2 px-4': props.size === 'default',
      'h-9 px-3': props.size === 'sm',
      'h-11 px-8': props.size === 'lg',
    }
  )
})
</script>

<template>
  <button :class="buttonVariants">
    <slot />
  </button>
</template>
