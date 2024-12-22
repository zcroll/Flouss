<script setup>
import FadeIn from '@/Components/FadeIn.vue'
import Border from '@/Components/Border.vue'

defineProps({
  title: {
    type: String,
    default: ''
  }
})
</script>

<template>
  <li class="group mt-10 first:mt-0">
    <FadeIn>
      <Border class="pt-10 group-first:pt-0 group-first:before:hidden group-first:after:hidden">
        <strong v-if="title" class="font-semibold text-neutral-950">{{ title }}. </strong>
        <slot />
      </Border>
    </FadeIn>
  </li>
</template> 