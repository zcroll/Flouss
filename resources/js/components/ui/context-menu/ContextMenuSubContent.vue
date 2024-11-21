<script setup>
import { cn } from '@/lib/utils';
import { ContextMenuSubContent, useForwardPropsEmits } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  forceMount: { type: Boolean, required: false },
  loop: { type: Boolean, required: false },
  sideOffset: { type: Number, required: false },
  alignOffset: { type: Number, required: false },
  avoidCollisions: { type: Boolean, required: false },
  collisionBoundary: { type: null, required: false },
  collisionPadding: { type: [Number, Object], required: false },
  arrowPadding: { type: Number, required: false },
  sticky: { type: String, required: false },
  hideWhenDetached: { type: Boolean, required: false },
  updatePositionStrategy: { type: String, required: false },
  prioritizePosition: { type: Boolean, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});
const emits = defineEmits([
  'escapeKeyDown',
  'pointerDownOutside',
  'focusOutside',
  'interactOutside',
  'entryFocus',
  'openAutoFocus',
  'closeAutoFocus',
]);

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
  <ContextMenuSubContent
    v-bind="forwarded"
    :class="
      cn(
        'yesz-50 yesmin-w-32 yesoverflow-hidden yesrounded-md yesborder yesbg-popover yesp-1 yestext-popover-foreground yesshadow-lg data-[state=open]:yesanimate-in data-[state=closed]:yesanimate-out data-[state=closed]:yesfade-out-0 data-[state=open]:yesfade-in-0 data-[state=closed]:yeszoom-out-95 data-[state=open]:yeszoom-in-95 data-[side=bottom]:yesslide-in-from-top-2 data-[side=left]:yesslide-in-from-right-2 data-[side=right]:yesslide-in-from-left-2 data-[side=top]:yesslide-in-from-bottom-2',
        props.class,
      )
    "
  >
    <slot />
  </ContextMenuSubContent>
</template>
