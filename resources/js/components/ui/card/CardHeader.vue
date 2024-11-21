<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div :class="cn('yesflex yesflex-col yesgap-y-1.5 yesp-6', props.class)">
    <slot />
  </div>
</template>
