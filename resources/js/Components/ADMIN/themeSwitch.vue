<script setup>
import { inject } from 'vue'
import { Button } from '@/Components/ui/button'

const { current: theme, toggle: toggleTheme } = inject('theme')
</script>

<template>
  <Button 
    variant="ghost" 
    size="icon"
    class="h-9 w-9 rounded-md relative"
    @click="toggleTheme"
  >
    <div class="relative w-5 h-5">
      <img 
        src="/Icons/light/sun.svg"
        class="h-5 w-5 absolute transition-all duration-300"
        :class="theme === 'light' ? 'opacity-100 rotate-0' : 'opacity-0 -rotate-90'"
        alt="Light mode"
      />
      <img
        src="/Icons/light/moon.svg" 
        class="h-5 w-5 absolute transition-all duration-300"
        :class="theme === 'dark' ? 'opacity-100 rotate-0' : 'opacity-0 rotate-90'"
        alt="Dark mode"
      />
    </div>
    <span class="sr-only">Toggle theme</span>
  </Button>
</template>
