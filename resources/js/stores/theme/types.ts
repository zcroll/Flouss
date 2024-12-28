export interface ThemeConfig {
  primary: string;
  accent: string;
  base: {
    from: string;
    via: string;
    to: string;
  };
  button: string;
  hover: string;
  border: string;
  ring: string;
  background: {
    light: string;
    medium: string;
    dark: string;
  };
}

export interface ThemeStore {
  isDarkMode: boolean;
  currentArchetype: string | null;
  currentTheme: ThemeConfig;
  getThemeClasses: (element: string) => string;
} 