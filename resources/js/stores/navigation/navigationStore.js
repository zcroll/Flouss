import { defineStore } from 'pinia'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export const useNavigationStore = defineStore('navigation', () => {
  const page = usePage()

  // Get current URL and component from Inertia page object
  const currentUrl = computed(() => page.url)
  const currentComponent = computed(() => page.component)

  // Navigation items with their paths and components
  const navigationMappings = {
    'dashboard': {
      path: '/dashboard',
      component: 'Dashboard'
    },
    'jobs.index': {
      path: '/jobs',
      component: 'Jobs'
    },
    'degrees.index': {
      path: '/degrees',
      component: 'Degrees'
    },
    'formations.index': {
      path: '/formations',
      component: 'Formations'
    },
    'results': {
      path: '/results',
      component: 'Results'
    }
  }

  // Check if route is active using Inertia's page object
  function isRouteActive(routeName) {
    if (!routeName || !navigationMappings[routeName]) return false

    const mapping = navigationMappings[routeName]
    const path = mapping.path
    const component = mapping.component

    // Check if current URL starts with the path
    const isUrlMatch = currentUrl.value.startsWith(path)

    // Check if current component starts with the component name
    const isComponentMatch = currentComponent.value.startsWith(component)

    return isUrlMatch || isComponentMatch
  }

  return {
    currentUrl,
    currentComponent,
    isRouteActive
  }
}) 