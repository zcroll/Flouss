<script setup>
import { cn } from '@/lib/utils';
import { ContextMenuSeparator } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});
</script>

<template>
  <ContextMenuSeparator
    v-bind="delegatedProps"
    :class="cn('yes-mx-1 yesmy-1 yesh-px yesbg-border', props.class)"
  />
</template>
