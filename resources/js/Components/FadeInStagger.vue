<script setup>
import { provide, ref } from 'vue'

const props = defineProps({
  faster: {
    type: Boolean,
    default: false
  }
})

provide('staggerDelay', props.faster ? 120 : 200)
const staggerIndex = ref(0)

provide('getStaggerDelay', () => {
  staggerIndex.value++
  return staggerIndex.value * (props.faster ? 120 : 200)
})
</script>

<template>
  <div>
    <slot />
  </div>
</template> 