<script setup>
import { ref, onMounted, provide } from 'vue'

const theme = ref('light')

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
  document.documentElement.classList.toggle('dark')
  localStorage.setItem('theme', theme.value)
}

onMounted(() => {
  // Check for saved theme preference or system preference
  const savedTheme = localStorage.getItem('theme')
  const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
  
  theme.value = savedTheme || systemTheme
  
  if (theme.value === 'dark') {
    document.documentElement.classList.add('dark')
  }
})

// Provide theme state and toggle function to child components
provide('theme', {
  current: theme,
  toggle: toggleTheme
})
</script>

<template>
  <div :class="[
    'theme-provider',
    theme === 'dark' ? 'dark' : '',
  ]">
    <slot :theme="theme" :toggleTheme="toggleTheme" />
  </div>
</template>

<style>
.theme-provider {
  /* Light theme variables - Clean white theme */
  --background: 0 0% 100%; /* Pure white */
  --foreground: 222 47% 11%; /* Dark gray for text */
  --muted: 210 40% 98%; /* Very light gray */
  --muted-foreground: 215 16% 47%; /* Medium gray */
  --popover: 0 0% 100%;
  --popover-foreground: 222 47% 11%;
  --border: 214 32% 91%;
  --input: 214 32% 91%;
  --card: 0 0% 100%;
  --card-foreground: 222 47% 11%;
  --primary: 221 83% 53%; /* Vibrant blue */
  --primary-foreground: 210 40% 98%;
  --secondary: 210 40% 96%;
  --secondary-foreground: 222 47% 11%;
  --accent: 210 40% 96%;
  --accent-foreground: 222 47% 11%;
  --destructive: 0 84% 60%; /* Bright red */
  --destructive-foreground: 210 40% 98%;
  --ring: 221 83% 53%;
  --radius: 0.5rem;
}

.theme-provider.dark {
  /* Dark theme variables - Deep dark theme */
  --background: 222 47% 11%; /* Deep dark background */
  --foreground: 210 40% 98%; /* Light text */
  --muted: 217 33% 17%; /* Darker gray */
  --muted-foreground: 215 20% 65%;
  --accent: 217 33% 17%;
  --accent-foreground: 210 40% 98%;
  --popover: 222 47% 11%;
  --popover-foreground: 215 20% 65%;
  --border: 217 33% 17%;
  --input: 217 33% 17%;
  --card: 222 47% 11%;
  --card-foreground: 210 40% 98%;
  --primary: 210 40% 98%; /* White primary */
  --primary-foreground: 222 47% 11%;
  --secondary: 217 33% 17%;
  --secondary-foreground: 210 40% 98%;
  --destructive: 0 63% 31%;
  --destructive-foreground: 210 40% 98%;
  --ring: 217 33% 17%;
  --radius: 0.5rem;
}

/* Base styles that use CSS variables */
.theme-provider {
  background-color: hsl(var(--background));
  color: hsl(var(--foreground));
  min-height: 100vh;
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* Utility classes for colors */
.bg-background { background-color: hsl(var(--background)); }
.bg-foreground { background-color: hsl(var(--foreground)); }
.bg-muted { background-color: hsl(var(--muted)); }
.bg-accent { background-color: hsl(var(--accent)); }
.bg-primary { background-color: hsl(var(--primary)); }
.bg-secondary { background-color: hsl(var(--secondary)); }

.text-foreground { color: hsl(var(--foreground)); }
.text-muted { color: hsl(var(--muted-foreground)); }
.text-accent { color: hsl(var(--accent-foreground)); }
.text-primary { color: hsl(var(--primary-foreground)); }
.text-secondary { color: hsl(var(--secondary-foreground)); }

.border-border { border-color: hsl(var(--border)); }
</style> 