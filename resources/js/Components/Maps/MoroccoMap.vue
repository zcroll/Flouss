<template>
  <div class="morocco-map-container relative">
    <svg 
      viewBox="0 0 1000 1000"
      class="w-full h-96"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        v-for="region in regions"
        :key="region.id"
        :d="region.path"
        :class="[
          'region-path',
          selectedRegion?.id === region.id ? 'active' : '',
          hoveredRegion?.id === region.id ? 'hovered' : '',
          'transition-all duration-200'
        ]"
        @click="selectRegion(region)"
        @mouseenter="showTooltip(region)"
        @mouseleave="hideTooltip"
      />
    </svg>

    <!-- Region Name Display -->
    <div
      v-if="displayedRegion"
      class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-950/90 px-4 py-2 rounded-lg shadow-lg text-center"
    >
      <h3 class="font-semibold text-sm text-gray-50">
        {{ displayedRegion.name }}
      </h3>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { regions } from './moroccoRegions';

const props = defineProps({
  modelValue: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['update:modelValue']);

const hoveredRegion = ref(null);

const selectedRegion = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

// Show either hovered region name or selected region name
const displayedRegion = computed(() => 
  hoveredRegion.value || selectedRegion.value
);

const selectRegion = (region) => {
  selectedRegion.value = region;
};

const showTooltip = (region) => {
  hoveredRegion.value = region;
};

const hideTooltip = () => {
  hoveredRegion.value = null;
};
</script>

<style scoped>
.region-path {
  fill: #f8f0e7;
  stroke: #d1d5db;
  stroke-width: 2;
  transition: all 0.2s ease-in-out;
  cursor: pointer;
}

.region-path:hover {
  fill: #db492b20;
  filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07)) 
         drop-shadow(0 2px 2px rgb(0 0 0 / 0.06));
  stroke-width: 3;
  stroke: #db492b;
}

.region-path.hovered {
  fill: #db492b30;
  stroke: #db492b;
  stroke-width: 3;
}

.region-path.active {
  fill: #db492b;
  stroke: #db492b;
  filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.04)) 
         drop-shadow(0 4px 3px rgb(0 0 0 / 0.1));
}

/* Add a subtle pulse animation to hovered regions */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.005); }
  100% { transform: scale(1); }
}

.region-path.hovered {
  animation: pulse 2s infinite;
}
</style> 
