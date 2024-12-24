<template>
    <div class="relative" ref="multiSelectRef">
        <div
            class="relative w-full cursor-default rounded-xl bg-white py-2 pl-3 pr-10 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 sm:text-sm shadow-sm"
            :style="{
                borderWidth: '1px',
                borderColor: isOpen 
                    ? `rgb(var(--${themeColors?.primary || 'yellow'}-rgb))` 
                    : `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.2)`,
                '--tw-ring-color': `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.2)`
            }"
            :class="{ 
                'ring-2': isOpen,
                [classes?.focus]: true 
            }"
            @click="toggleDropdown"
        >
            <div class="flex flex-wrap gap-1 min-h-[1.5rem]">
                <!-- Selected items -->
                <span
                    v-for="item in modelValue"
                    :key="item.value"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-sm"
                    :style="{
                        backgroundColor: `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.1)`,
                        color: `rgb(var(--${themeColors?.primary || 'yellow'}-rgb))`
                    }"
                >
                    {{ item.label }}
                    <button
                        type="button"
                        @click.stop="removeItem(item)"
                        class="group relative rounded-sm p-0.5 transition duration-200"
                        :style="{
                            '&:hover': {
                                backgroundColor: `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.2)`
                            }
                        }"
                    >
                        <XMarkIcon class="h-3 w-3" />
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
                <ChevronUpDownIcon
                    class="h-5 w-5 transition-transform duration-200"
                    :class="[classes?.icon, { 'transform rotate-180': isOpen }]"
                />
            </span>
        </div>

        <!-- Options dropdown -->
        <div
            v-show="isOpen"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-xl bg-white py-1 text-base shadow-lg focus:outline-none sm:text-sm"
            :style="{
                borderWidth: '1px',
                borderColor: `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.2)`
            }"
        >
            <div
                v-for="option in filteredOptions"
                :key="option.value"
                class="relative cursor-pointer select-none py-2 pl-3 pr-9 transition duration-200"
                :style="{
                    backgroundColor: highlightedOption === option 
                        ? `rgb(var(--${themeColors?.primary || 'yellow'}-rgb))` 
                        : 'transparent',
                    color: highlightedOption === option ? 'white' : 'inherit',
                    '&:hover': {
                        backgroundColor: highlightedOption !== option 
                            ? `rgb(var(--${themeColors?.primary || 'yellow'}-rgb), 0.1)` 
                            : ''
                    }
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
                    :style="{
                        color: highlightedOption === option 
                            ? 'white' 
                            : `rgb(var(--${themeColors?.primary || 'yellow'}-rgb))`
                    }"
                >
                    <CheckIcon class="h-5 w-5" />
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
import { XMarkIcon, ChevronUpDownIcon, CheckIcon } from '@heroicons/vue/20/solid';

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
    },
    themeColors: {
        type: Object,
        default: () => ({ primary: 'yellow' })
    },
    classes: {
        type: Object,
        default: () => ({})
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
