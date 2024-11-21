<script setup>
import { cn } from '@/lib/utils';
import { ChevronRightIcon } from '@radix-icons/vue';
import { ContextMenuSubTrigger, useForwardProps } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  disabled: { type: Boolean, required: false },
  textValue: { type: String, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
  inset: { type: Boolean, required: false },
});

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <ContextMenuSubTrigger
    v-bind="forwardedProps"
    :class="
      cn(
        'yesflex yescursor-default yesselect-none yesitems-center yesrounded-sm yespx-2 yespy-1.5 yestext-sm yesoutline-none focus:yesbg-accent focus:yestext-accent-foreground data-[state=open]:yesbg-accent data-[state=open]:yestext-accent-foreground',
        inset && 'yespl-8',
        props.class,
      )
    "
  >
    <slot />
    <ChevronRightIcon class="yesml-auto yesh-4 yesw-4" />
  </ContextMenuSubTrigger>
</template>
