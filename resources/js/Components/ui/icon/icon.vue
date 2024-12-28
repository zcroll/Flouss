<template>
  <component :is="iconComponent" :class="cn(iconVariants({ variant, size }), className)" aria-hidden="true" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { cn } from "@/lib/utils"
import { iconVariants, type IconProps } from "./types"
import {
  BuildingOfficeIcon,
  MapIcon,
  BeakerIcon,
  ComputerDesktopIcon,
  BuildingOffice2Icon,
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from '@heroicons/vue/24/outline'

type IconComponent = typeof BuildingOfficeIcon

const iconMap: Record<string, IconComponent> = {
  'building-office': BuildingOfficeIcon,
  'map': MapIcon,
  'beaker': BeakerIcon,
  'computer-desktop': ComputerDesktopIcon,
  'office-building': BuildingOffice2Icon,
  'chevron-up': ChevronUpIcon,
  'chevron-down': ChevronDownIcon,
  'chevron-left': ChevronLeftIcon,
  'chevron-right': ChevronRightIcon,
}

const props = withDefaults(defineProps<IconProps>(), {
  variant: 'default',
  size: 'default',
  className: '',
})

const iconComponent = computed<IconComponent>(() => {
  return iconMap[props.name] || BuildingOffice2Icon
})

defineOptions({
  name: 'Icon'
})
</script>