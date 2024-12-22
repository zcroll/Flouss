<script setup>
import { computed } from 'vue'

const props = defineProps({
  as: {
    type: String,
    default: 'div'
  },
  class: {
    type: String,
    default: ''
  }
})

const containerClass = computed(() => {
  return ['mx-auto max-w-7xl px-6 lg:px-8 rounded-4xl', props.class].filter(Boolean).join(' ')
})
</script>

<template>
  <component :is="as" :class="containerClass">
    <div class="mx-auto max-w-2xl lg:max-w-none">
      <slot />
    </div>
  </component>
</template> 