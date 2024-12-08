<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <span :class="cn('yesml-auto yestext-xs yestracking-widest yestext-muted-foreground', props.class)">
    <slot />
  </span>
</template>
