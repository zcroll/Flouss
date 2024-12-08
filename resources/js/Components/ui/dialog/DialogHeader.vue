<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    :class="cn('yesflex yesflex-col yesgap-y-1.5 yestext-center sm:yestext-left', props.class)"
  >
    <slot />
  </div>
</template>
