<script setup>
import { useField } from 'vee-validate';

const props = defineProps({
  name: {
    type: String,
    default: ''
  }
});

const { errorMessage } = props.name ? useField(props.name) : { errorMessage: '' };
</script>

<template>
  <p v-if="errorMessage" class="form-message text-sm text-red-500">
    {{ errorMessage }}
  </p>
</template>