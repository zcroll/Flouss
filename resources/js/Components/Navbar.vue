<template>
  <nav class="flex flex-col items-center gap-5">
    <Link
      v-for="(item, index) in navigationItems" 
      :key="item.route"
      :href="route(item.route)"
      class="nav-link group relative"
      :class="[
        'h-14 w-14 rounded-full flex items-center justify-center transition-all duration-300',
        { 'delay-100': index > 0 },
        checkActive(item.route)
          ? 'bg-yellow-400 hover:bg-yellow-500 nav-active shadow-lg'
          : 'bg-white/90 hover:bg-white/100 hover:scale-105 backdrop-blur-xl shadow-md'
      ]"
    >
      <component 
        :is="getIcon(item.name)" 
        class="nav-icon transition-all duration-300 ease-in-out"
        :class="[
          'h-7 w-7',
          checkActive(item.route) 
            ? 'text-white transform scale-110' 
            : 'text-gray-600 group-hover:text-gray-800'
        ]" 
      />
      
      <!-- Tooltip -->
      <span class="nav-tooltip">
        {{ item.name }}
      </span>

      <!-- Glass effect overlay -->
      <div 
        class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
        style="background: linear-gradient(145deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.1) 100%); backdrop-filter: blur(8px);"
      ></div>
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

const checkActive = (routeName) => {
  const currentRoute = route().current();
  
  // Handle index routes (jobs.index, degrees.index, etc)
  if (routeName.endsWith('.index')) {
    const baseRoute = routeName.split('.')[0];
    return currentRoute === routeName || currentRoute.startsWith(`${baseRoute}.`);
  }
  
  // Handle other routes
  return currentRoute === routeName || currentRoute.startsWith(`${routeName}.`);
};
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
  background: linear-gradient(45deg, rgba(250, 204, 21, 0.4), rgba(250, 204, 21, 0.2));
  border-radius: 9999px;
  z-index: -1;
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.1);
  }
}

/* Hover effect for icons */
.nav-link:hover .nav-icon {
  transform: scale(1.1);
  z-index: 2;
}

.nav-active .nav-icon {
  filter: drop-shadow(0 0 8px rgba(250, 204, 21, 0.3));
  z-index: 2;
}

/* Ensure icon stays above glass effect */
.nav-icon {
  position: relative;
  z-index: 2;
}
</style>
