<script setup>
import { Dialog, DialogContent, DialogOverlay } from '@/components/ui/dialog'
import { computed, defineEmits, defineProps } from 'vue'

const props = defineProps({
  open: {
    type: Boolean,
    required: true
  },
  side: {
    type: String,
    default: 'right',
    validator: (value) => ['top', 'right', 'bottom', 'left'].includes(value)
  }
})

const emit = defineEmits(['update:open'])

const sideClasses = computed(() => ({
  'top': 'inset-x-0 top-0 border-b',
  'right': 'inset-y-0 right-0 border-l',
  'bottom': 'inset-x-0 bottom-0 border-t',
  'left': 'inset-y-0 left-0 border-r'
})[props.side])
</script>

<template>
  <Dialog :open="open" @update:open="(value) => emit('update:open', value)">
    <DialogOverlay class="fixed inset-0 z-[90] bg-black/50 backdrop-blur-sm" />
    <DialogContent
      :class="[
        'fixed z-[100] bg-white shadow-lg transition ease-in-out data-[state=open]:animate-in data-[state=closed]:animate-out',
        sideClasses
      ]"
    >
      <slot />
    </DialogContent>
  </Dialog>
</template> 