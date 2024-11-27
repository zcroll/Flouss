<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Check } from 'lucide-vue-next'
import {
  SelectItem,
  SelectItemIndicator,
  type SelectItemProps,
  SelectItemText,
  useForwardProps,
} from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<SelectItemProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <SelectItem
    v-bind="forwardedProps"
    :class="
      cn(
        'yesrelative yesflex yesw-full yescursor-default yesselect-none yesitems-center yesrounded-sm yespy-1.5 yespl-8 yespr-2 yestext-sm yesoutline-none focus:yesbg-accent focus:yestext-accent-foreground data-[disabled]:yespointer-events-none data-[disabled]:yesopacity-50',
        props.class,
      )
    "
  >
    <span class="yesabsolute yesleft-2 yesflex yesh-3.5 yesw-3.5 yesitems-center yesjustify-center">
      <SelectItemIndicator>
        <Check class="yesh-4 yesw-4" />
      </SelectItemIndicator>
    </span>

    <SelectItemText>
      <slot />
    </SelectItemText>
  </SelectItem>
</template>
