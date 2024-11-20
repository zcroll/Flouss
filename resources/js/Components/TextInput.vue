<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-stone-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-700 sm:text-sm sm:leading-6"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
