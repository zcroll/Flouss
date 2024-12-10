<script setup>
import { ref, provide, onMounted } from 'vue';

const props = defineProps({
  as: {
    type: String,
    default: 'nav'
  }
});

const ready = ref(false);
const size = ref('0px');
const position = ref('0px');
const duration = ref('300ms');

// Add padding to make pill larger than text
const PADDING = 16; // Adjust this value to control the pill size

provide('navGroup', {
  setActiveItem: (el) => {
    if (!el) return;
    // Add padding to the width
    size.value = `${el.offsetWidth + PADDING}px`;
    // Center the position by subtracting half the padding
    position.value = `${el.offsetLeft - (PADDING / 2)}px`;
  }
});

onMounted(() => {
  ready.value = true;
});
</script>

<template>
  <component
    :is="as"
    class="relative"
  >
    <slot
      :ready="ready"
      :size="size"
      :position="position"
      :duration="duration"
    />
  </component>
</template>
