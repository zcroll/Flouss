<script setup>
import { cn } from '@/lib/utils';
import { ContextMenuItem, useForwardPropsEmits } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  disabled: { type: Boolean, required: false },
  textValue: { type: String, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
  inset: { type: Boolean, required: false },
});
const emits = defineEmits(['select']);

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
  <ContextMenuItem
    v-bind="forwarded"
    :class="
      cn(
        'yesrelative yesflex yescursor-default yesselect-none yesitems-center yesrounded-sm yespx-2 yespy-1.5 yestext-sm yesoutline-none focus:yesbg-accent focus:yestext-accent-foreground data-[disabled]:yespointer-events-none data-[disabled]:yesopacity-50',
        inset && 'yespl-8',
        props.class,
      )
    "
  >
    <slot />
  </ContextMenuItem>
</template>
