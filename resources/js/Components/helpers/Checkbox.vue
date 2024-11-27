<script setup>
import { computed } from 'vue';
import { Switch } from '@headlessui/vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        type: String,
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <Switch
        v-model="proxyChecked"
        class="group relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none"
    >
        <span class="sr-only">Use setting</span>
        <span aria-hidden="true" class="pointer-events-none absolute h-full w-full rounded-md " />
        <span aria-hidden="true" :class="[proxyChecked ? 'bg-black' : 'bg-gray-200', 'pointer-events-none absolute mx-auto h-4 w-9 rounded-full transition-colors duration-200 ease-in-out']" />
        <span aria-hidden="true" :class="[proxyChecked ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out']" />
    </Switch>
</template>
