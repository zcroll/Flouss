<script setup>
import { computed } from 'vue'
import FadeInStagger from '@/Components/FadeInStagger.vue'

const props = defineProps({
  class: {
    type: String,
    default: ''
  }
})

const listClass = computed(() => {
  return ['text-base text-neutral-600', props.class].filter(Boolean).join(' ')
})
</script>

<template>
  <FadeInStagger>
    <ul role="list" :class="listClass">
      <slot />
    </ul>
  </FadeInStagger>
</template> 