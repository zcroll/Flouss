<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 transition duration-150 ease-in-out';
});

// Define text classes that will change based on the `active` prop
const textClasses = computed(() => {
    return props.active ? ' text-[#db492b] font-bold' : 'font-bold text-[#0a1e2e] hover:text-[#db492b] transition duration-150 ease-in-out' ;
});
</script>

<template>
    <Link :href="href" :class="classes">
        <!-- Apply the text classes dynamically to the slot content -->
        <span :class="textClasses">
      <slot />
    </span>
    </Link>
</template>
