<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ChevronDown } from 'lucide-vue-next'
import { SelectIcon, SelectTrigger, type SelectTriggerProps, useForwardProps } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<SelectTriggerProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <SelectTrigger
    v-bind="forwardedProps"
    :class="cn(
      'yesflex yesh-10 yesw-full yesitems-center yesjustify-between yesrounded-md yesborder yesborder-input yesbg-background yespx-3 yespy-2 yestext-sm yesring-offset-background data-[placeholder]:yestext-muted-foreground focus:yesoutline-none focus:yesring-2 focus:yesring-ring focus:yesring-offset-2 disabled:yescursor-not-allowed disabled:yesopacity-50 [&>span]:yestruncate yestext-start',
      props.class,
    )"
  >
    <slot />
    <SelectIcon as-child>
      <ChevronDown class="yesw-4 yesh-4 yesopacity-50 yesshrink-0" />
    </SelectIcon>
  </SelectTrigger>
</template>
