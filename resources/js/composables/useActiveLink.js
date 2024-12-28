import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useActiveLink() {
  const page = usePage()

  const isActive = (routeName) => {
    const currentRoute = route().current()

    // Handle special cases for nested routes
    const routeMappings = {
      'dashboard': ['dashboard'],
      'jobs.index': ['jobs.index'],
      'degrees.index': ['degrees.index'],
      'formations.index': ['formations.index', 'formation.show'],
    }

    // Check if current route matches any of the mapped routes
    return routeMappings[routeName]?.includes(currentRoute) || currentRoute === routeName
  }

  const currentPath = computed(() => page.url)
  const currentComponent = computed(() => page.component)

  return {
    isActive,
    currentPath,
    currentComponent
  }
}