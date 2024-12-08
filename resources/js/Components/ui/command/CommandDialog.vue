<script setup lang="ts">
import type { DialogRootEmits, DialogRootProps } from 'radix-vue'
import { Dialog, DialogContent } from '@/components/ui/dialog'
import { useForwardPropsEmits } from 'radix-vue'
import Command from './Command.vue'

const props = defineProps<DialogRootProps>()
const emits = defineEmits<DialogRootEmits>()

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <Dialog v-bind="forwarded">
    <DialogContent class="yesoverflow-hidden yesp-0 yesshadow-lg">
      <Command class="[&_[cmdk-group-heading]]:yespx-2 [&_[cmdk-group-heading]]:yesfont-medium [&_[cmdk-group-heading]]:yestext-muted-foreground [&_[cmdk-group]:not([hidden])_~[cmdk-group]]:yespt-0 [&_[cmdk-group]]:yespx-2 [&_[cmdk-input-wrapper]_svg]:yesh-5 [&_[cmdk-input-wrapper]_svg]:yesw-5 [&_[cmdk-input]]:yesh-12 [&_[cmdk-item]]:yespx-2 [&_[cmdk-item]]:yespy-3 [&_[cmdk-item]_svg]:yesh-5 [&_[cmdk-item]_svg]:yesw-5">
        <slot />
      </Command>
    </DialogContent>
  </Dialog>
</template>
