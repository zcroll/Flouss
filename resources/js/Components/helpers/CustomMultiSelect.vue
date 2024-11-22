<template>
    <div class="relative" ref="multiSelectRef">
        <div
            class="relative w-full cursor-default rounded-xl bg-white py-2 pl-3 pr-10 text-left border border-[#db492b]/20 focus:outline-none focus-visible:border-[#db492b] focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-[#db492b]/20 sm:text-sm shadow-sm"
            :class="{ 'ring-2 ring-[#db492b]/20 border-[#db492b]': isOpen }"
            @click="toggleDropdown"
        >
            <div class="flex flex-wrap gap-1 min-h-[1.5rem]">
                <!-- Selected items -->
                <span
                    v-for="item in modelValue"
                    :key="item.value"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#db492b]/10 text-[#db492b] text-sm"
                >
                    {{ item.label }}
                    <button
                        type="button"
                        @click.stop="removeItem(item)"
                        class="group relative rounded-sm hover:bg-[#db492b]/20 p-0.5 transition duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>

                <!-- Search input -->
                <input
                    ref="searchInput"
                    type="text"
                    v-model="search"
                    :placeholder="modelValue.length ? '' : placeholder"
                    class="appearance-none border-none p-0 focus:ring-0 focus:outline-none text-sm placeholder:text-gray-500 flex-1 min-w-[80px] bg-transparent m-0"
                    style="padding: 0 !important; border: none !important; --tw-shadow: none !important;"
                    @input="onSearch"
                    @keydown.backspace="handleBackspace"
                    @keydown.down.prevent="highlightNext"
                    @keydown.up.prevent="highlightPrevious"
                    @keydown.enter.prevent="selectHighlighted"
                    @keydown.esc="closeDropdown"
                />
            </div>

            <!-- Dropdown indicator -->
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                <svg
                    class="h-5 w-5 text-gray-400"
                    :class="{ 'transform rotate-180 transition-transform duration-200': isOpen }"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </span>
        </div>

        <!-- Options dropdown -->
        <div
            v-show="isOpen"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-xl bg-white py-1 text-base shadow-lg border border-[#db492b]/20 focus:outline-none sm:text-sm"
        >
            <div
                v-for="option in filteredOptions"
                :key="option.value"
                class="relative cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 transition duration-200"
                :class="{
                    'bg-[#db492b] text-white': highlightedOption === option,
                    'hover:bg-[#db492b]/10': highlightedOption !== option
                }"
                @click="toggleOption(option)"
            >
                <span class="block truncate" :class="{ 'font-semibold': isSelected(option) }">
                    {{ option.label }}
                </span>

                <!-- Check mark for selected items -->
                <span
                    v-if="isSelected(option)"
                    class="absolute inset-y-0 right-0 flex items-center pr-4"
                    :class="{ 'text-white': highlightedOption === option, 'text-[#db492b]': highlightedOption !== option }"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>

            <!-- No results message -->
            <div
                v-if="filteredOptions.length === 0"
                class="relative cursor-default select-none py-2 px-4 text-gray-500"
            >
                No results found
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { onClickOutside } from '@vueuse/core';
import '../../../../public/css/vueMultiselect.css';

const props = defineProps({
    modelValue: {
        type: Array,
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: 'Select options'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const search = ref('');
const searchInput = ref(null);
const highlightedOption = ref(null);

// Add ref for the root element
const multiSelectRef = ref(null);

// Filtered options based on search
const filteredOptions = computed(() => {
    return props.options.filter(option =>
        option.label.toLowerCase().includes(search.value.toLowerCase())
    );
});

// Toggle dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchInput.value?.focus();
    }
};

const closeDropdown = () => {
    isOpen.value = false;
    search.value = '';
};

// Check if option is selected
const isSelected = (option) => {
    return props.modelValue.some(item => item.value === option.value);
};

// Toggle option selection
const toggleOption = (option) => {
    const newValue = [...props.modelValue];
    const index = newValue.findIndex(item => item.value === option.value);

    if (index === -1) {
        newValue.push(option);
    } else {
        newValue.splice(index, 1);
    }

    emit('update:modelValue', newValue);
};

// Remove selected item
const removeItem = (item) => {
    const newValue = props.modelValue.filter(i => i.value !== item.value);
    emit('update:modelValue', newValue);
};

// Handle backspace key
const handleBackspace = (event) => {
    if (search.value === '' && props.modelValue.length > 0) {
        const newValue = [...props.modelValue];
        newValue.pop();
        emit('update:modelValue', newValue);
    }
};

// Search functionality
const onSearch = () => {
    if (!isOpen.value) {
        isOpen.value = true;
    }
};

// Reset search when dropdown closes
watch(isOpen, (newValue) => {
    if (!newValue) {
        search.value = '';
    }
});

// Use onClickOutside composable
onClickOutside(multiSelectRef, () => {
    closeDropdown();
});

// Add these new methods for keyboard navigation
const highlightNext = () => {
    if (!isOpen.value) {
        isOpen.value = true;
        return;
    }

    const options = filteredOptions.value;
    if (options.length === 0) return;

    const currentIndex = options.indexOf(highlightedOption.value);
    if (currentIndex === -1 || currentIndex === options.length - 1) {
        highlightedOption.value = options[0];
    } else {
        highlightedOption.value = options[currentIndex + 1];
    }
};

const highlightPrevious = () => {
    const options = filteredOptions.value;
    if (options.length === 0) return;

    const currentIndex = options.indexOf(highlightedOption.value);
    if (currentIndex === -1 || currentIndex === 0) {
        highlightedOption.value = options[options.length - 1];
    } else {
        highlightedOption.value = options[currentIndex - 1];
    }
};

const selectHighlighted = () => {
    if (highlightedOption.value) {
        toggleOption(highlightedOption.value);
    }
};

// Reset highlighted option when filtered options change
watch(filteredOptions, () => {
    highlightedOption.value = filteredOptions.value[0] || null;
});
</script>

<style scoped>
/* Empty style block can be removed since we're using external CSS */
</style>
