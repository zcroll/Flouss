<script setup>
import { computed } from 'vue'

const props = defineProps({
  class: {
    type: String,
    default: ''
  }
})

const borderClass = computed(() => {
  return [
    'relative before:absolute after:absolute before:bg-neutral-950 after:bg-neutral-950/10 before:left-0 before:top-0 before:h-px before:w-6 after:left-8 after:right-0 after:top-0 after:h-px',
    props.class
  ].filter(Boolean).join(' ')
})
</script>

<template>
  <div :class="borderClass">
    <slot />
  </div>
</template> 