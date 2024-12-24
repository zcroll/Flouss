<template>
  <nav class="flex flex-col gap-5">
    <Link
      :href="route('dashboard')"
      :class="[
        'h-14 w-14 rounded-full flex items-center justify-center shadow-lg transition-colors',
        route().current('dashboard') 
          ? 'bg-yellow-400 hover:bg-yellow-500' 
          : 'bg-white/90 hover:bg-white backdrop-blur-md'
      ]"
    >
      <HomeIcon :class="[
        'h-7 w-7',
        route().current('dashboard') ? 'text-white' : 'text-gray-600'
      ]" />
    </Link>
    <Link
      v-for="item in navigationItems.slice(1)" 
      :key="item.route"
      :href="route(item.route)"
      :class="[
        'h-14 w-14 rounded-full flex items-center justify-center shadow-md transition-colors',
        route().current(item.route)
          ? 'bg-yellow-400 hover:bg-yellow-500'
          : 'bg-white/90 hover:bg-white backdrop-blur-md'
      ]"
    >
      <component :is="getIcon(item.name)" :class="[
        'h-7 w-7',
        route().current(item.route) ? 'text-white' : 'text-gray-600'
      ]" />
    </Link>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { 
  HomeIcon,
  ClipboardDocumentListIcon,
  BriefcaseIcon,
  AcademicCapIcon,
  BookOpenIcon
} from '@heroicons/vue/24/outline';

const navigationItems = [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'Results', route: 'results' },
    { name: 'Jobs', route: 'jobs.index' },
    { name: 'Degrees', route: 'degrees.index' },
    { name: 'Formations', route: 'formations.index' }
];

const getIcon = (name) => {
  switch(name) {
    case 'Results': return ClipboardDocumentListIcon;
    case 'Jobs': return BriefcaseIcon;
    case 'Degrees': return AcademicCapIcon;
    case 'Formations': return BookOpenIcon;
    default: return HomeIcon;
  }
};
</script>
