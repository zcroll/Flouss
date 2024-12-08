<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Search } from 'lucide-vue-next'
import { ComboboxInput, type ComboboxInputProps, useForwardProps } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps<ComboboxInputProps & {
  class?: HTMLAttributes['class']
}>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <div class="yesflex yesitems-center yesborder-b yespx-3" cmdk-input-wrapper>
    <Search class="yesmr-2 yesh-4 yesw-4 yesshrink-0 yesopacity-50" />
    <ComboboxInput
      v-bind="{ ...forwardedProps, ...$attrs }"
      auto-focus
      :class="cn('yesflex yesh-11 yesw-full yesrounded-md yesbg-transparent yespy-3 yestext-sm yesoutline-none placeholder:yestext-muted-foreground disabled:yescursor-not-allowed disabled:yesopacity-50', props.class)"
    />
  </div>
</template>
