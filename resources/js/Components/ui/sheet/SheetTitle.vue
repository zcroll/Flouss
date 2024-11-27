<script setup>
import { DialogTitle } from 'radix-vue'

defineOptions({
  name: 'SheetTitle'
})
</script>

<template>
  <DialogTitle class="text-lg font-semibold text-stone-900 dark:text-stone-50">
    <slot />
  </DialogTitle>
</template>
