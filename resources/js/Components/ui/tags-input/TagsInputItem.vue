<script setup lang="ts">
import { cn } from '@/lib/utils'
import { TagsInputItem, type TagsInputItemProps, useForwardProps } from 'radix-vue'

import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<TagsInputItemProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <TagsInputItem v-bind="forwardedProps" :class="cn('yesflex yesh-6 yesitems-center yesrounded yesbg-secondary data-[state=active]:yesring-ring data-[state=active]:yesring-2 data-[state=active]:yesring-offset-2 yesring-offset-background', props.class)">
    <slot />
  </TagsInputItem>
</template>
