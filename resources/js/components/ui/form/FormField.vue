<script setup>
import { useField } from 'vee-validate';

const props = defineProps({
  name: {
    type: String,
    required: true
  }
});

const { value, errorMessage, handleBlur, handleChange } = useField(props.name);
</script>

<template>
  <div class="form-field">
    <slot :value="value" :error="errorMessage" :blur="handleBlur" :change="handleChange" />
  </div>
</template>