import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

// Create a singleton state outside the function
const activeTab = ref('overview')
const activeSidebarItem = ref('/adminn/dashboard')

export function useAdminTabs(defaultTab = 'overview') {
  const page = usePage()

  // Initialize the active tab and sidebar item based on the current URL
  const initializeState = () => {
    const path = window.location.pathname
    activeSidebarItem.value = path
    
    // Set active tab based on URL or page prop
    if (path === '/adminn/dashboard') {
      activeTab.value = page.props.page || defaultTab
    } else {
      // Extract the last segment of the URL for other dashboard pages
      const segment = path.split('/').pop()
      activeTab.value = segment || defaultTab
    }
  }

  // Initialize on mount
  initializeState()

  // Watch for page prop changes
  watch(() => page.props.page, (newPage) => {
    if (newPage && newPage !== activeTab.value) {
      activeTab.value = newPage
    }
  })

  // Watch for URL changes
  watch(() => window.location.pathname, (newPath) => {
    if (newPath !== activeSidebarItem.value) {
      initializeState()
    }
  })

  const handleTabChange = (tab) => {
    if (tab === activeTab.value) return
    activeTab.value = tab
    
    // Update sidebar state when tab changes
    activeSidebarItem.value = tab === 'overview' 
      ? '/adminn/dashboard'
      : `/adminn/dashboard/${tab}`
  }

  const handleSidebarNavigation = (path) => {
    if (path === activeSidebarItem.value) return
    activeSidebarItem.value = path
    
    // Update tab state when sidebar changes
    if (path.startsWith('/adminn/dashboard')) {
      const segment = path.split('/').pop()
      activeTab.value = segment === 'dashboard' ? 'overview' : segment
    } else {
      activeTab.value = 'overview'
    }
  }

  return {
    activeTab,
    activeSidebarItem,
    handleTabChange,
    handleSidebarNavigation
  }
}