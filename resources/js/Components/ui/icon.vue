<template>
  <component :is="iconComponent" v-bind="$attrs" />
</template>

<script setup>
import { computed } from 'vue';
import {
  BuildingOfficeIcon,
  MapIcon,
  BeakerIcon,
  ComputerDesktopIcon,
  BuildingOffice2Icon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  name: {
    type: String,
    required: true
  }
});

const iconMap = {
  'building-office': BuildingOfficeIcon,
  'map': MapIcon,
  'beaker': BeakerIcon,
  'computer-desktop': ComputerDesktopIcon,
  'office-building': BuildingOffice2Icon
};

const iconComponent = computed(() => {
  return iconMap[props.name] || BuildingOffice2Icon;
});
</script>