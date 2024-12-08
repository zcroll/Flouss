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
      class="yesfixed yesinset-0 yesz-50 yesbg-black/80 yes data-[state=open]:yesanimate-in data-[state=closed]:yesanimate-out data-[state=closed]:yesfade-out-0 data-[state=open]:yesfade-in-0"
    />
    <DialogContent
      v-bind="forwarded"
      :class="
        cn(
          'yesfixed yesleft-1/2 yestop-1/2 yesz-50 yesgrid yesw-full yesmax-w-lg yes-translate-x-1/2 yes-translate-y-1/2 yesgap-4 yesborder yesbg-background yesp-6 yesshadow-lg yesduration-200 data-[state=open]:yesanimate-in data-[state=closed]:yesanimate-out data-[state=closed]:yesfade-out-0 data-[state=open]:yesfade-in-0 data-[state=closed]:yeszoom-out-95 data-[state=open]:yeszoom-in-95 data-[state=closed]:yesslide-out-to-left-1/2 data-[state=closed]:yesslide-out-to-top-[48%] data-[state=open]:yesslide-in-from-left-1/2 data-[state=open]:yesslide-in-from-top-[48%] sm:yesrounded-lg',
          props.class,
        )"
    >
      <slot />

      <DialogClose
        class="yesabsolute yesright-4 yestop-4 yesrounded-sm yesopacity-70 yesring-offset-background yestransition-opacity hover:yesopacity-100 focus:yesoutline-none focus:yesring-2 focus:yesring-ring focus:yesring-offset-2 disabled:yespointer-events-none data-[state=open]:yesbg-accent data-[state=open]:yestext-muted-foreground"
      >
        <X class="yesw-4 yesh-4" />
        <span class="yessr-only">Close</span>
      </DialogClose>
    </DialogContent>
  </DialogPortal>
</template>
