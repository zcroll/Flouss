import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export const useThemeStore = defineStore('theme', () => {
  // State
  const isDarkMode = ref(false)
  const currentArchetype = ref(null)

  // Watch for system dark mode changes
  const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)')

  // Theme configurations with reactive dark mode
  const themeConfig = computed(() => ({
    'blue-theme': {
      primary: 'blue',
      accent: 'sky',
      base: isDarkMode.value ? {
        from: 'dark:from-blue-900', // Darker navy blue
        via: 'dark:via-blue-800',
        to: 'dark:to-blue-700'
      } : {
        from: 'from-blue-200', // Lighter baby blue
        via: 'via-blue-100',
        to: 'to-blue-50'
      },
      button: isDarkMode.value ? 'blue-800' : 'blue-400', // Adjusted for new colors
      hover: isDarkMode.value ? 'blue-900' : 'blue-500',
      border: isDarkMode.value ? 'blue-950' : 'blue-300',
      ring: isDarkMode.value ? 'blue-900' : 'blue-200',
      background: isDarkMode.value ? {
        light: 'dark:bg-blue-800',
        medium: 'dark:bg-blue-900',
        dark: 'dark:bg-blue-950'
      } : {
        light: 'bg-blue-100',
        medium: 'bg-blue-200',
        dark: 'bg-blue-400'
      }
    },
    // Success theme - Fresh green  
    'green-theme': {
      primary: 'emerald',
      accent: 'teal',
      base: isDarkMode.value ? {
        from: 'dark:from-emerald-950',
        via: 'dark:via-gray-900',
        to: 'dark:to-emerald-900'
      } : {
        from: 'from-emerald-50',
        via: 'via-white',
        to: 'to-emerald-100'
      },
      button: isDarkMode.value ? 'emerald-600' : 'emerald-500',
      hover: isDarkMode.value ? 'emerald-700' : 'emerald-600',
      border: isDarkMode.value ? 'emerald-800' : 'emerald-200',
      ring: isDarkMode.value ? 'emerald-900' : 'emerald-100',
      background: isDarkMode.value ? {
        light: 'dark:bg-emerald-900',
        medium: 'dark:bg-emerald-800',
        dark: 'dark:bg-emerald-700'
      } : {
        light: 'bg-emerald-50',
        medium: 'bg-emerald-100',
        dark: 'bg-emerald-600'
      }
    },
    // Creative theme - Rich purple
    'purple-theme': {
      primary: 'violet',
      accent: 'purple',
      base: isDarkMode.value ? {
        from: 'dark:from-violet-950',
        via: 'dark:via-gray-900',
        to: 'dark:to-violet-900'
      } : {
        from: 'from-violet-50',
        via: 'via-white',
        to: 'to-violet-100'
      },
      button: isDarkMode.value ? 'violet-600' : 'violet-500',
      hover: isDarkMode.value ? 'violet-700' : 'violet-600',
      border: isDarkMode.value ? 'violet-800' : 'violet-200',
      ring: isDarkMode.value ? 'violet-900' : 'violet-100',
      background: isDarkMode.value ? {
        light: 'dark:bg-violet-900',
        medium: 'dark:bg-violet-800',
        dark: 'dark:bg-violet-700'
      } : {
        light: 'bg-violet-50',
        medium: 'bg-violet-100',
        dark: 'bg-violet-600'
      }
    },
    // Energy theme - Vibrant amber
    'amber-theme': {
      primary: 'amber',
      accent: 'orange',
      base: isDarkMode.value ? {
        from: 'dark:from-amber-950',
        via: 'dark:via-gray-900',
        to: 'dark:to-amber-900'
      } : {
        from: 'from-amber-50',
        via: 'via-white',
        to: 'to-amber-100'
      },
      button: isDarkMode.value ? 'amber-600' : 'amber-500',
      hover: isDarkMode.value ? 'amber-700' : 'amber-600',
      border: isDarkMode.value ? 'amber-800' : 'amber-200',
      ring: isDarkMode.value ? 'amber-900' : 'amber-100',
      background: isDarkMode.value ? {
        light: 'dark:bg-amber-900',
        medium: 'dark:bg-amber-800',
        dark: 'dark:bg-amber-700'
      } : {
        light: 'bg-amber-50',
        medium: 'bg-amber-100',
        dark: 'bg-amber-600'
      }
    }
  }))

  // Archetype to theme mapping
  const archetypeThemeMap = {
    // Blue theme archetypes - Professional/Analytical
    'Caregiver': 'blue-theme',
    'Designer': 'blue-theme',
    'Guardian': 'blue-theme',
    'Mentor': 'blue-theme',
    'Producer': 'blue-theme',
    'Protector': 'blue-theme',
    'Scholar': 'blue-theme',
    'Inventor': 'blue-theme',

    // Green theme archetypes - Growth/Support
    'Advocat': 'green-theme',
    'Anchor': 'green-theme',
    'Captain': 'green-theme',
    'Composer': 'green-theme',
    'Humanitarian': 'green-theme',
    'Kingpin': 'green-theme',
    'Philosopher': 'green-theme',
    'Supporter': 'green-theme',
    'Explorer': 'green-theme',

    // Purple theme archetypes - Innovation/Vision
    'Architect': 'purple-theme',
    'Groundbreaker': 'purple-theme',
    'Researcher': 'purple-theme',
    'Strategist': 'purple-theme',
    'Mastermind': 'purple-theme',
    'Maverick': 'purple-theme',
    'Visionary': 'purple-theme',
    'Creator': 'blue-theme',

    // Amber theme archetypes - Energy/Creation
    'Builder': 'amber-theme',
    'Enthusiast': 'amber-theme',
    'Innovator': 'amber-theme',
    'Luminary': 'amber-theme',
    'Technician': 'amber-theme'
  }

  // Getters
  const currentTheme = computed(() => {
    // Get theme based on current archetype, fallback to blue-theme
    const themeKey = archetypeThemeMap[currentArchetype.value] || 'blue-theme'
    return themeConfig.value[themeKey]
  })

  // Theme utility functions
  function getThemeClasses(element) {
    const theme = currentTheme.value

    switch (element) {
      case 'base':
        return `${theme.base.from} ${theme.base.via} ${theme.base.to}`
      case 'button':
        return `bg-${theme.button}`
      case 'background':
        return theme.background
      case 'hover':
        return `hover:bg-${theme.hover}`
      case 'border':
        return `border-${theme.border}`
      case 'ring':
        return `ring-${theme.ring}`
      default:
        return ''
    }
  }

  // Add available themes array
  const availableThemes = [
    {
      id: 'blue-theme',
      name: 'Modern Blue',
      icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' // Home icon path
    },
    {
      id: 'green-theme',
      name: 'Fresh Green',
      icon: 'M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495' // Sun icon path
    },
    {
      id: 'purple-theme',
      name: 'Rich Purple',
      icon: 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z' // Light bulb icon path
    },
    {
      id: 'amber-theme',
      name: 'Vibrant Amber',
      icon: 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z' // Sparkles icon path
    }
  ]

  // Add current theme ID ref
  const currentThemeId = ref('blue-theme')

  // Add computed for current theme object
  const currentThemeObject = computed(() => {
    return availableThemes.find(theme => theme.id === currentThemeId.value)
  })

  // Add function to switch themes
  function switchTheme(themeId) {
    currentThemeId.value = themeId
    localStorage.setItem('theme', themeId)
  }

  // Actions
  function initializeTheme() {
    // Get archetype from Inertia shared data
    const page = usePage()
    const userArchetype = page.props.auth?.user?.archetype

    console.log('User archetype from Inertia:', userArchetype)

    // Set current archetype from user data
    currentArchetype.value = userArchetype || 'Creator'

    // Set theme ID based on archetype
    const themeId = archetypeThemeMap[currentArchetype.value] || 'blue-theme'
    console.log('Selected theme based on archetype:', {
      archetype: currentArchetype.value,
      mappedTheme: themeId,
      mapping: archetypeThemeMap[currentArchetype.value]
    })

    currentThemeId.value = themeId

    // Initialize dark mode
    const savedDarkMode = localStorage.getItem('darkMode')
    isDarkMode.value = savedDarkMode !== null
      ? savedDarkMode === 'true'
      : darkModeMediaQuery.matches

    updateDarkMode()

    // Listen for system dark mode changes
    darkModeMediaQuery.addEventListener('change', (e) => {
      if (localStorage.getItem('darkMode') === null) {
        isDarkMode.value = e.matches
        updateDarkMode()
      }
    })

    console.log('Initialized theme:', {
      archetype: currentArchetype.value,
      themeId: currentThemeId.value,
      isDarkMode: isDarkMode.value
    }) // Debug log
  }

  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value
    localStorage.setItem('darkMode', isDarkMode.value)
    updateDarkMode()
  }

  function updateDarkMode() {
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }

  function setArchetype(archetype) {
    currentArchetype.value = archetype
  }

  return {
    // State
    isDarkMode,
    currentArchetype,
    // Getters
    currentTheme,
    // Actions
    initializeTheme,
    toggleDarkMode,
    setArchetype,
    // Utilities
    getThemeClasses,
    availableThemes,
    currentThemeId,
    currentThemeObject,
    switchTheme
  }
})
