<script setup>
import { computed } from 'vue'
import Container from '@/Components/Container.vue'
import FadeIn from '@/Components/FadeIn.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  eyebrow: {
    type: String,
    default: ''
  },
  smaller: {
    type: Boolean,
    default: false
  },
  invert: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  }
})

const titleClasses = computed(() => {
  return [
    'block font-display tracking-tight [text-wrap:balance]',
    props.smaller ? 'text-2xl font-semibold' : 'text-4xl font-medium sm:text-5xl',
    props.invert ? 'text-white' : 'text-neutral-950'
  ]
})

const eyebrowClasses = computed(() => {
  return [
    'mb-6 block font-display text-base font-semibold',
    props.invert ? 'text-white' : 'text-neutral-950'
  ]
})

const contentClasses = computed(() => {
  return [
    'mt-6 text-xl',
    props.invert ? 'text-neutral-300' : 'text-neutral-600'
  ]
})
</script>

<template>
  <Container :class="props.class">
    <FadeIn class="max-w-7xl">
      <h2>
        <span v-if="eyebrow" :class="eyebrowClasses">
          {{ eyebrow }}
          <span class="sr-only"> - </span>
        </span>
        <span :class="titleClasses">{{ title }}</span>
      </h2>
      <div v-if="$slots.default" :class="contentClasses">
        <slot />
      </div>
    </FadeIn>
  </Container>
</template> 