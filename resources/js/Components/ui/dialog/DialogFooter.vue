<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: HTMLAttributes['class'] }>()
</script>

<template>
  <div
    :class="
      cn(
        'yesflex yesflex-col-reverse sm:yesflex-row sm:yesjustify-end sm:yesgap-x-2',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
