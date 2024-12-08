<script setup lang="ts">
import { cn } from '@/lib/utils'
import { TagsInputInput, type TagsInputInputProps, useForwardProps } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<TagsInputInputProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <TagsInputInput v-bind="forwardedProps" :class="cn('yestext-sm yesmin-h-6 focus:yesoutline-none yesflex-1 yesbg-transparent yespx-1', props.class)" />
</template>
