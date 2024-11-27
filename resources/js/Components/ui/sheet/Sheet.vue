<script setup>
import { DialogRoot } from 'radix-vue'

defineOptions({
  name: 'Sheet'
})
</script>

<template>
  <DialogRoot v-bind="$attrs">
    <slot />
  </DialogRoot>
</template>
