<script setup lang="ts">
import type { ComboboxGroupProps } from 'radix-vue'
import { cn } from '@/lib/utils'
import { ComboboxGroup, ComboboxLabel } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<ComboboxGroupProps & {
  class?: HTMLAttributes['class']
  heading?: string
}>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})
</script>

<template>
  <ComboboxGroup
    v-bind="delegatedProps"
    :class="cn('yesoverflow-hidden yesp-1 yestext-foreground [&_[cmdk-group-heading]]:yespx-2 [&_[cmdk-group-heading]]:yespy-1.5 [&_[cmdk-group-heading]]:yestext-xs [&_[cmdk-group-heading]]:yesfont-medium [&_[cmdk-group-heading]]:yestext-muted-foreground', props.class)"
  >
    <ComboboxLabel v-if="heading" class="yespx-2 yespy-1.5 yestext-xs yesfont-medium yestext-muted-foreground">
      {{ heading }}
    </ComboboxLabel>
    <slot />
  </ComboboxGroup>
</template>
