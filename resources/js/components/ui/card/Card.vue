<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div
    :class="
      cn('yesrounded-xl yesborder yesbg-card yestext-card-foreground yesshadow', props.class)
    "
  >
    <slot />
  </div>
</template>
