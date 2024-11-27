<script setup>
import { DialogTrigger } from 'radix-vue'

defineOptions({
  name: 'SheetTrigger'
})
</script>

<template>
  <DialogTrigger v-bind="$attrs">
    <slot />
  </DialogTrigger>
</template>
