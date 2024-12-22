<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: String,
  invert: Boolean
})
</script>

<template>
  <Link
    :href="href"
    :class="[
      'rounded-full px-4 py-1.5 text-sm font-medium transition',
      invert
        ? 'bg-white text-neutral-950 hover:bg-neutral-200'
        : 'bg-neutral-950 text-white hover:bg-neutral-800'
    ]"
  >
    <slot></slot>
  </Link>
</template> 