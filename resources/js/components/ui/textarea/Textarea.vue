<script setup>
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';

const props = defineProps({
  class: { type: null, required: false },
  defaultValue: { type: [String, Number], required: false },
  modelValue: { type: [String, Number], required: false },
});

const emits = defineEmits(['update:modelValue']);

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
});
</script>

<template>
  <textarea
    v-model="modelValue"
    :class="
      cn(
        'yesflex yesmin-h-[60px] yesw-full yesrounded-md yesborder yesborder-input yesbg-transparent yespx-3 yespy-2 yestext-sm yesshadow-sm placeholder:yestext-muted-foreground focus-visible:yesoutline-none focus-visible:yesring-1 focus-visible:yesring-ring disabled:yescursor-not-allowed disabled:yesopacity-50',
        props.class,
      )
    "
  />
</template>
