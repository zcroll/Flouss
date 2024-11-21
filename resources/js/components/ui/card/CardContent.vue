<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div :class="cn('yesp-6 yespt-0', props.class)">
    <slot />
  </div>
</template>
