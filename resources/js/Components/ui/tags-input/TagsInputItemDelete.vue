<script setup lang="ts">
import { cn } from '@/lib/utils'
import { X } from 'lucide-vue-next'
import { TagsInputItemDelete, type TagsInputItemDeleteProps, useForwardProps } from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<TagsInputItemDeleteProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <TagsInputItemDelete v-bind="forwardedProps" :class="cn('yesflex yesrounded yesbg-transparent yesmr-1', props.class)">
    <slot>
      <X class="yesw-4 yesh-4" />
    </slot>
  </TagsInputItemDelete>
</template>
