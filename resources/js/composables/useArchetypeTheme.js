import { computed, watch } from 'vue'

export function useArchetypeTheme(archetype) {
  const themeColors = computed(() => {
    const themes = {
      // Blue theme
      'blue-theme': {
        primary: 'blue',
        accent: 'sky',
        base: {
          from: 'from-blue-100',
          via: 'via-white',
          to: 'to-blue-50'
        },
        button: 'blue-400',
        hover: 'blue-500',
        border: 'blue-400/20',
        ring: 'blue-400/10',
        background: {
          light: 'bg-blue-50',
          medium: 'bg-blue-100',
          dark: 'bg-blue-500'
        }
      },
      // Green theme
      'green-theme': {
        primary: 'emerald',
        accent: 'green',
        base: {
          from: 'from-emerald-100',
          via: 'via-white',
          to: 'to-emerald-50'
        },
        button: 'emerald-400',
        hover: 'emerald-500',
        border: 'emerald-400/20',
        ring: 'emerald-400/10',
        background: {
          light: 'bg-emerald-50',
          medium: 'bg-emerald-100',
          dark: 'bg-emerald-200'
        }
      },
      // Purple theme
      'purple-theme': {
        primary: 'purple',
        accent: 'violet',
        base: {
          from: 'from-purple-100',
          via: 'via-white',
          to: 'to-purple-50'
        },
        button: 'purple-400',
        hover: 'purple-500',
        border: 'purple-400/20',
        ring: 'purple-400/10',
        background: {
          light: 'bg-purple-50',
          medium: 'bg-purple-100',
          dark: 'bg-purple-200'
        }
      },
      // Yellow theme (default)
      'yellow-theme': {
        primary: 'yellow',
        accent: 'amber',
        base: {
          from: 'from-yellow-100',
          via: 'via-white',
          to: 'to-yellow-50'
        },
        button: 'yellow-400',
        hover: 'yellow-500',
        border: 'yellow-400/20',
        ring: 'yellow-400/10',
        background: {
          light: 'bg-yellow-50',
          medium: 'bg-yellow-100',
          dark: 'bg-yellow-200'
        }
      }
    }

    const archetypeThemeMap = {
      // Blue theme archetypes
      'Caregiver': 'blue-theme',
      'Designer': 'blue-theme',
      'Guardian': 'blue-theme',
      'Mentor': 'blue-theme',
      'Producer': 'blue-theme',
      'Protector': 'blue-theme',
      'Scholar': 'blue-theme',
      'Inventor': 'blue-theme',

      // Green theme archetypes
      'Advocat': 'green-theme',
      'Anchor': 'green-theme',
      'Captain': 'green-theme',
      'Composer': 'green-theme',
      'Humanitarian': 'green-theme',
      'Kingpin': 'green-theme',
      'Philosopher': 'green-theme',
      'Supporter': 'green-theme',
      'Explorer': 'green-theme',

      // Purple theme archetypes
      'Architect': 'purple-theme',
      'Groundbreaker': 'purple-theme',
      'Researcher': 'purple-theme',
      'Strategist': 'purple-theme',
      'Mastermind': 'purple-theme',
      'Maverick': 'purple-theme',
      'Visionary': 'purple-theme',

      // Yellow theme archetypes
      'Creator': 'blue-theme',
      'Builder': 'yellow-theme',
      'Enthusiast': 'yellow-theme',
      'Innovator': 'yellow-theme',
      'Luminary': 'yellow-theme',
      'Technician': 'yellow-theme'
    }

    const themeKey = archetypeThemeMap[archetype.value] || 'yellow-theme'
    console.log('Theme Selection:', {
      archetype: archetype.value,
      selectedTheme: themeKey
    })

    const selectedTheme = themes[themeKey]
    console.log('Theme Colors:', selectedTheme)
    return selectedTheme
  })

  const classes = computed(() => {
    const computedClasses = {
      gradient: `bg-gradient-to-br ${themeColors.value.base.from} ${themeColors.value.base.via} ${themeColors.value.base.to}`,
      button: `bg-${themeColors.value.button} hover:bg-${themeColors.value.hover}`,
      border: `border-${themeColors.value.border}`,
      ring: `ring-${themeColors.value.ring}`,
      text: `text-${themeColors.value.primary}-600`,
      icon: `text-${themeColors.value.primary}-400`,
      hover: `hover:text-${themeColors.value.primary}-500`,
      active: `bg-${themeColors.value.primary}-400 text-white`,
      focus: `focus:ring-${themeColors.value.primary}-400/20 focus:border-${themeColors.value.primary}-400`,
      background: {
        light: themeColors.value.background.light,
        medium: themeColors.value.background.medium,
        dark: themeColors.value.background.dark
      }
    }

    console.log('Generated Classes:', computedClasses)
    return computedClasses
  })

  // Add watcher for archetype changes
  watch(archetype, (newArchetype) => {
    console.log('Archetype Changed:', {
      from: archetype.value,
      to: newArchetype
    })
  })

  return {
    themeColors,
    classes
  }
} 