<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-stone-800 border border-transparent rounded-md font-semibold text-xs text-stone-300 uppercase tracking-widest hover:bg-stone-700 focus:bg-stone-700 active:bg-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
