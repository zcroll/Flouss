<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'block w-full px-3 py-2 text-amber-500 bg-stone-700/50 rounded-lg font-medium text-base focus:outline-none transition-all duration-200'
        : 'block w-full px-3 py-2 text-stone-300 hover:text-amber-500 hover:bg-stone-700/50 rounded-lg font-medium text-base focus:outline-none transition-all duration-200';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
