<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { SelectLabel, type SelectLabelProps } from 'radix-vue'

const props = defineProps<SelectLabelProps & { class?: HTMLAttributes['class'] }>()
</script>

<template>
  <SelectLabel :class="cn('yespy-1.5 yespl-8 yespr-2 yestext-sm yesfont-semibold', props.class)">
    <slot />
  </SelectLabel>
</template>
