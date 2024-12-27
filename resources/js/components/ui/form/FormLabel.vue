<script setup>
defineProps({
  for: {
    type: String,
    default: ''
  }
});
</script>

<template>
  <label :for="for" class="form-label text-sm font-medium">
    <slot />
  </label>
</template>