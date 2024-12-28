<template>
  <nav :class="[
    isMobile
      ? 'flex-row justify-around py-4 px-2 w-full'
      : 'flex-col items-center gap-5'
  ]" class="flex">
    <Link v-for="(item, index) in navigationItems" :key="item.route" :href="route(item.route)"
      class="nav-link group relative transition-all duration-300" :class="[
        isMobile
          ? 'flex flex-col items-center gap-1 py-1 px-3 rounded-xl hover:bg-white/10'
          : 'h-14 w-14 rounded-full flex items-center justify-center',
        { 'delay-100': index > 0 },
        navigationStore.isRouteActive(item.route)
          ? isMobile
            ? `text-${themeStore.currentTheme.primary}-500 bg-white/10 dark:bg-gray-800/40`
            : [`bg-${themeStore.currentTheme.primary}-500`, 'shadow-lg']
          : isMobile
            ? 'text-gray-400'
            : ['bg-white/90 hover:bg-white/100 hover:scale-105 backdrop-blur-xl shadow-md']
      ]">
    <div class="relative z-10">
      <component :is="item.icon" class="nav-icon transition-all duration-300 ease-in-out" :class="[
        isMobile ? 'h-6 w-6' : 'h-7 w-7',
        navigationStore.isRouteActive(item.route)
          ? isMobile
            ? `text-${themeStore.currentTheme.primary}-500`
            : 'text-white transform scale-110'
          : isMobile
            ? 'text-gray-400 group-hover:text-gray-300'
            : [`text-${themeStore.currentTheme.primary}-500`, `group-hover:text-${themeStore.currentTheme.primary}-600`]
      ]" />

      <!-- Navigation Label -->
      <span class="text-xs font-medium" v-if="isMobile" :class="[
        navigationStore.isRouteActive(item.route)
          ? `text-${themeStore.currentTheme.primary}-500`
          : 'text-gray-400 group-hover:text-gray-300'
      ]">
        {{ item.name }}
      </span>
      <span v-if="!isMobile" class="nav-tooltip" :class="[`text-${themeStore.currentTheme.primary}-500`]">
        {{ item.name }}
      </span>
    </div>

    <!-- Glass effect overlay -->
    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" :class="[
      `bg-${themeStore.currentTheme.primary}-50/10`,
      isMobile ? 'rounded-xl' : 'rounded-full'
    ]" style="backdrop-filter: blur(8px);">
    </div>
    </Link>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
  HomeIcon,
  ClipboardDocumentListIcon,
  BriefcaseIcon,
  AcademicCapIcon,
  BookOpenIcon
} from '@heroicons/vue/24/outline';
import { useThemeStore } from '@/stores/theme/themeStore';
import { useNavigationStore } from '@/stores/navigation/navigationStore';

defineProps({
  isMobile: {
    type: Boolean,
    default: false
  }
});

const navigationItems = [
  { name: 'Dashboard', route: 'dashboard', icon: HomeIcon },
  { name: 'Results', route: 'results', icon: ClipboardDocumentListIcon },
  { name: 'Jobs', route: 'jobs.index', icon: BriefcaseIcon },
  { name: 'Degrees', route: 'degrees.index', icon: AcademicCapIcon },
  { name: 'Formations', route: 'formations.index', icon: BookOpenIcon }
];

const themeStore = useThemeStore();
const navigationStore = useNavigationStore();
</script>

<style scoped>
.nav-link {
  position: relative;
  isolation: isolate;
  z-index: 1;
}

.nav-active {
  position: relative;
}

.nav-active::before {
  content: '';
  position: absolute;
  inset: -3px;
  border-radius: 9999px;
  z-index: -1;
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  background: linear-gradient(45deg,
      rgba(var(--color-primary), 0.4),
      rgba(var(--color-primary), 0.2));
}

.nav-tooltip {
  position: absolute;
  left: calc(100% + 1rem);
  top: 50%;
  transform: translateY(-50%) translateX(-1rem);
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
  z-index: 50;
}

.nav-link:hover .nav-tooltip {
  opacity: 1;
  transform: translateY(-50%) translateX(0);
}

@keyframes pulse {

  0%,
  100% {
    opacity: 1;
    transform: scale(1);
  }

  50% {
    opacity: 0.5;
    transform: scale(1.1);
  }
}

/* Hover effect for icons */
.nav-link:hover {
  z-index: 10;
}

.nav-link:hover .nav-icon {
  transform: scale(1.1);
}

.nav-active .nav-icon {
  filter: drop-shadow(0 0 8px rgba(var(--color-primary), 0.3));
}

/* Ensure icon stays above glass effect */
.nav-icon {
  position: relative;
  z-index: 2;
}
</style>
