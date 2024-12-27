<script setup>
import { Form as VeeForm, Field, ErrorMessage } from 'vee-validate';

defineProps({
  // Add any props you need
});
</script>

<template>
  <VeeForm v-bind="$attrs">
    <slot></slot>
  </VeeForm>
</template>