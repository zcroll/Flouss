<template>
  <Link :href="link.url" :class="[
    isMobile ? mobileClasses : desktopClasses,
    isActive ? activeClasses : inactiveClasses
  ]">
  <component :is="getLinkIcon(link.text)" :class="[
    'w-4 h-4',
    isMobile ? 'mb-1' : '',
    isActive ? 'text-white' : `text-${themeStore.currentTheme.primary}-500 group-hover:text-gray-600`
  ]" />
  <span :class="[
    'text-sm',
    isMobile ? 'text-[11px] text-center leading-tight' : ''
  ]">
    {{ isMobile ? getShortLabel(link.text) : getLabel(link.text) }}
  </span>
  </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useThemeStore } from '@/stores/theme/themeStore';
import __ from '@/lang';
import {
  HomeIcon,
  BriefcaseIcon,
  UserIcon,
  AcademicCapIcon,
  BuildingOfficeIcon,
  DocumentTextIcon,
  ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline';

const themeStore = useThemeStore();
const page = usePage();

const props = defineProps({
  link: {
    type: Object,
    required: true
  },
  isMobile: {
    type: Boolean,
    default: false
  }
});

const isActive = computed(() => page.url === props.link.url);

const mobileClasses = "flex flex-col items-center justify-center px-2.5 py-2 rounded-lg text-sm font-medium transition-all";
const desktopClasses = "group w-full flex items-center gap-2 py-2 px-3 text-sm font-medium rounded-lg transition-all duration-300";

const activeClasses = computed(() => `bg-${themeStore.currentTheme.button} text-white`);
const inactiveClasses = computed(() => props.isMobile
  ? 'bg-white/50 text-gray-600 hover:bg-white/80'
  : 'text-gray-600 hover:bg-white/80'
);

const getLinkIcon = (text) => {
  const iconMap = {
    'overview': HomeIcon,
    'work_environments': BuildingOfficeIcon,
    'personality': UserIcon,
    'how_to_become': AcademicCapIcon,
    'how_to_obtain': AcademicCapIcon,
    'requirements': ClipboardDocumentListIcon,
    'similar_jobs': BriefcaseIcon
  };

  return iconMap[text.toLowerCase().replace(" ", "_")] || DocumentTextIcon;
};

const getLabel = (text) => {
  return __(`stickybar.${text.toLowerCase().replace(" ", "_")}`);
};

const getShortLabel = (text) => {
  return getLabel(text).split(' ')[0];
};
</script>