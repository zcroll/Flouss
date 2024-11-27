<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <input v-model="modelValue" :class="cn('yesflex yesh-10 yesw-full yesrounded-md yesborder yesborder-input yesbg-background yespx-3 yespy-2 yestext-sm yesring-offset-background file:yesborder-0 file:yesbg-transparent file:yestext-sm file:yesfont-medium placeholder:yestext-muted-foreground focus-visible:yesoutline-none focus-visible:yesring-2 focus-visible:yesring-ring focus-visible:yesring-offset-2 disabled:yescursor-not-allowed disabled:yesopacity-50', props.class)">
</template>
