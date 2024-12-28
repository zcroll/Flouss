import type { ThemeStore } from '@/stores/theme/types'
import { useThemeStore } from '@/stores/theme/themeStore'

export function useToastConfig() {
  const themeStore: ThemeStore = useThemeStore()

  return {
    position: "top-right" as const,
    duration: 3000,
    class: `z-[100] backdrop-blur-md border shadow-lg ${themeStore.isDarkMode
        ? `bg-${themeStore.currentTheme.background.dark}/10 border-${themeStore.currentTheme.border}/20`
        : `bg-${themeStore.currentTheme.background.light}/20 border-${themeStore.currentTheme.border}/10`
      }`,
    style: {
      color: themeStore.isDarkMode
        ? 'rgba(255, 255, 255, 0.95)'
        : 'rgba(0, 0, 0, 0.9)',
    }
  } as const
}