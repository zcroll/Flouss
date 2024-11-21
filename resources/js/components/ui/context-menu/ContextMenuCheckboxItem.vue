<script setup>
import { cn } from '@/lib/utils';
import { CheckIcon } from '@radix-icons/vue';
import {
  ContextMenuCheckboxItem,
  ContextMenuItemIndicator,
  useForwardPropsEmits,
} from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  checked: { type: [Boolean, String], required: false },
  disabled: { type: Boolean, required: false },
  textValue: { type: String, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});
const emits = defineEmits(['select', 'update:checked']);

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
  <ContextMenuCheckboxItem
    v-bind="forwarded"
    :class="
      cn(
        'yesrelative yesflex yescursor-default yesselect-none yesitems-center yesrounded-sm yespy-1.5 yespl-8 yespr-2 yestext-sm yesoutline-none focus:yesbg-accent focus:yestext-accent-foreground data-[disabled]:yespointer-events-none data-[disabled]:yesopacity-50',
        props.class,
      )
    "
  >
    <span class="yesabsolute yesleft-2 yesflex yesh-3.5 yesw-3.5 yesitems-center yesjustify-center">
      <ContextMenuItemIndicator>
        <CheckIcon class="yesh-4 yesw-4" />
      </ContextMenuItemIndicator>
    </span>
    <slot />
  </ContextMenuCheckboxItem>
</template>
