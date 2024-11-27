<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ChevronUp } from 'lucide-vue-next'
import { SelectScrollUpButton, type SelectScrollUpButtonProps, useForwardProps } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<SelectScrollUpButtonProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <SelectScrollUpButton v-bind="forwardedProps" :class="cn('yesflex yescursor-default yesitems-center yesjustify-center yespy-1', props.class)">
    <slot>
      <ChevronUp class="yesh-4 yesw-4" />
    </slot>
  </SelectScrollUpButton>
</template>
