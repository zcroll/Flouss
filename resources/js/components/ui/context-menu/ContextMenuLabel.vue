<script setup>
import { cn } from '@/lib/utils';
import { ContextMenuLabel } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
  inset: { type: Boolean, required: false },
});

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});
</script>

<template>
  <ContextMenuLabel
    v-bind="delegatedProps"
    :class="
      cn(
        'yespx-2 yespy-1.5 yestext-sm yesfont-semibold yestext-foreground',
        inset && 'yespl-8',
        props.class,
      )
    "
  >
    <slot />
  </ContextMenuLabel>
</template>
