<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <span
    :class="
      cn('yesml-auto yestext-xs yestracking-widest yestext-muted-foreground', props.class)
    "
  >
    <slot />
  </span>
</template>
