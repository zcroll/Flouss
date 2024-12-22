<script setup>
import { computed } from 'vue'
import Container from '@/Components/Container.vue'
import FadeIn from '@/Components/FadeIn.vue'
import GridPattern from '@/Components/GridPattern.vue'

const props = defineProps({
  class: {
    type: String,
    default: ''
  }
})

const containerClass = computed(() => {
  return [
    'relative isolate bg-neutral-50 py-16 sm:py-28 md:py-32',
    props.class
  ].filter(Boolean).join(' ')
})
</script>

<template>
  <div :class="containerClass">
    <GridPattern
      class="absolute inset-0 -z-10 h-full w-full fill-neutral-100 stroke-neutral-950/5 [mask-image:linear-gradient(to_bottom_left,white_50%,transparent_60%)]"
      :y-offset="-256"
    />
    <Container>
      <FadeIn>
        <figure class="mx-auto max-w-4xl">
          <blockquote class="relative font-display text-3xl font-medium tracking-tight text-neutral-950 sm:text-4xl">
            <p class="sm:before:absolute sm:before:right-full">
              <slot />
            </p>
          </blockquote>
        </figure>
      </FadeIn>
    </Container>
  </div>
</template> 