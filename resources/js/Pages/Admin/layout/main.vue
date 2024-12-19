<script setup>
import { computed } from 'vue'

const props = defineProps({
  fixed: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  }
})

const mainClasses = computed(() => {
  return [
    'px-4 py-6',
    props.fixed && 'flex flex-col flex-grow overflow-hidden',
    props.class
  ]
})
</script>

<template>
  <main :class="mainClasses">
    <slot />
  </main>
</template>
