<script setup lang="ts">
import { cn } from '@/lib/utils'
import { X } from 'lucide-vue-next'
import {
  DialogClose,
  DialogContent,
  type DialogContentEmits,
  type DialogContentProps,
  DialogOverlay,
  DialogPortal,
  useForwardPropsEmits,
} from 'radix-vue'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<DialogContentProps & { class?: HTMLAttributes['class'] }>()
const emits = defineEmits<DialogContentEmits>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
</script>

<template>
  <DialogPortal>
    <DialogOverlay
      class="yesfixed yesinset-0 yesz-50 yesgrid yesplace-items-center yesoverflow-y-auto yesbg-black/80 yes data-[state=open]:yesanimate-in data-[state=closed]:yesanimate-out data-[state=closed]:yesfade-out-0 data-[state=open]:yesfade-in-0"
    >
      <DialogContent
        :class="
          cn(
            'yesrelative yesz-50 yesgrid yesw-full yesmax-w-lg yesmy-8 yesgap-4 yesborder yesborder-border yesbg-background yesp-6 yesshadow-lg yesduration-200 sm:yesrounded-lg md:yesw-full',
            props.class,
          )
        "
        v-bind="forwarded"
        @pointer-down-outside="(event) => {
          const originalEvent = event.detail.originalEvent;
          const target = originalEvent.target as HTMLElement;
          if (originalEvent.offsetX > target.clientWidth || originalEvent.offsetY > target.clientHeight) {
            event.preventDefault();
          }
        }"
      >
        <slot />

        <DialogClose
          class="yesabsolute yestop-3 yesright-3 yesp-0.5 yestransition-colors yesrounded-md hover:yesbg-secondary"
        >
          <X class="yesw-4 yesh-4" />
          <span class="yessr-only">Close</span>
        </DialogClose>
      </DialogContent>
    </DialogOverlay>
  </DialogPortal>
</template>
