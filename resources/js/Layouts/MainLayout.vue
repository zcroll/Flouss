<template>
  <div :class="[
    themeStore.getThemeClasses('base'),
    'h-screen p-4 overflow-hidden transition-colors duration-300',
    themeStore.isDarkMode ? 'dark bg-gray-900' : 'bg-white'
  ]">
    <div class="w-full h-full max-w-[120rem] rounded-[48px] p-12 shadow-2xl relative overflow-hidden backdrop-blur-lg"
      :class="[themeStore.isDarkMode ? 'bg-gray-800/40' : 'bg-white/40']" :style="{
        backgroundPosition: '80% center',
        backgroundSize: 'contain',
        backgroundRepeat: 'no-repeat',
        backdropFilter: 'blur(20px)',
      }">
      <!-- Decorative circles using theme colors with dark mode variants -->
      <div :class="[
        themeStore.isDarkMode
          ? `bg-${themeStore.currentTheme.primary}-900/30`
          : `bg-${themeStore.currentTheme.primary}-100`,
        'absolute top-0 right-0 w-1/2 h-1/2 rounded-full blur-3xl -z-10 opacity-60 transition-colors duration-300'
      ]" />
      <div :class="[
        themeStore.isDarkMode
          ? `bg-${themeStore.currentTheme.primary}-800/20`
          : `bg-${themeStore.currentTheme.primary}-50`,
        'absolute bottom-0 left-0 w-1/2 h-1/2 rounded-full blur-3xl -z-10 opacity-60 transition-colors duration-300'
      ]" />

      <!-- Header with dark mode styles -->
      <header class="flex items-center justify-between mb-8">
        <h1 :class="[
          'text-3xl font-japanese tracking-wide transition-colors duration-300',
          themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
        ]">
          アニマンガヘブン
        </h1>

        <!-- Rest of the header content with dark mode classes -->
        <div class="flex items-center gap-6">
          <div class="relative group">
            <Search :class="[
              'absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 group-hover:scale-110 transition-all duration-300',
              themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
            ]" />
            <input type="search" placeholder="Search" :class="[
              `focus:ring-${themeStore.currentTheme.ring}`,
              'pl-12 pr-4 py-2.5 rounded-full w-56 text-base transition-all duration-300',
              themeStore.isDarkMode
                ? 'bg-gray-800/90 text-white placeholder-gray-400'
                : 'bg-white/90 text-gray-900 placeholder-gray-500'
            ]" />
          </div>
          <button class="relative group">
            <Bell class="h-6 w-6 group-hover:scale-110 transition-all duration-300 text-gray-500" />
            <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full" />
          </button>
          <div class="flex items-center gap-3">
            <span class="text-base text-gray-700">Hi, {{ $page.props.auth.user.name }}</span>
            <Sheet v-model:open="isOpen">
              <SheetTrigger>
                <div class="relative group cursor-pointer">
                  <div
                    :class="[`bg-${themeStore.currentTheme.button}-400/20`, 'absolute inset-0 rounded-full blur-md transition-opacity opacity-0 group-hover:opacity-100']">
                  </div>
                  <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" :class="[
                    `border-${themeStore.currentTheme.border}`,
                    'w-10 h-10 rounded-full border-2 transition-all duration-300 group-hover:scale-105 relative z-10'
                  ]" />
                </div>
              </SheetTrigger>

              <SheetContent side="right" :class="[
                'w-[280px] backdrop-blur-2xl rounded-2xl border-none p-0',
                themeStore.isDarkMode 
                  ? `bg-gray-900/95 ${themeStore.getThemeClasses('background').light}`
                  : `bg-white/95 ${themeStore.getThemeClasses('background').light}`
              ]">
                <div class="p-6">
                  <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                      <div
                        :class="[`bg-${themeStore.currentTheme.button}-400/20`, 'absolute inset-0 rounded-full blur-lg']">
                      </div>
                      <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" :class="[
                        `border-${themeStore.currentTheme.border}`,
                        'w-12 h-12 rounded-full border-2 relative z-10'
                      ]" />
                    </div>
                    <div>
                      <h3 :class="[
                        'font-medium',
                        themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
                      ]">{{ $page.props.auth.user.name }}</h3>
                      <p :class="[
                        'text-sm',
                        themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
                      ]">{{ themeStore.currentArchetype }}</p>
                    </div>
                  </div>

                  <div class="my-4" :class="[
                    'border-t',
                    themeStore.isDarkMode ? 'border-gray-700' : 'border-gray-200'
                  ]"></div>

                  <div class="space-y-1">
                    <Link :href="route('profile.show')" :class="[
                      `hover:bg-${themeStore.currentTheme.button}/10`,
                      'flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors'
                    ]" @click="isOpen = false">
                    <UserIcon :class="[
                      'w-5 h-5',
                      themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
                    ]" />
                    <span :class="[
                      'text-sm font-medium',
                      themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700'
                    ]">Profile Settings</span>
                    </Link>

                    <button @click="themeStore.toggleDarkMode" :class="[
                      `hover:bg-${themeStore.currentTheme.button}/10`,
                      'flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors'
                    ]">
                      <svg xmlns="http://www.w3.org/2000/svg" :class="[
                        'h-5 w-5',
                        themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
                      ]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          :d="themeStore.isDarkMode ? 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'" />
                      </svg>
                      <span :class="[
                        'text-sm font-medium',
                        themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700'
                      ]">
                        {{ themeStore.isDarkMode ? 'Light Mode' : 'Dark Mode' }}
                      </span>
                    </button>

                    <form @submit.prevent="logout" class="mt-4">
                      <button type="submit" :class="[
                        'flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors',
                        themeStore.isDarkMode 
                          ? 'text-red-400 hover:bg-red-900/20'
                          : 'text-red-600 hover:bg-red-50'
                      ]" @click="isOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm font-medium">Sign Out</span>
                      </button>
                    </form>
                  </div>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <div class="flex gap-8 h-[calc(100%-theme(spacing.8))] justify-center">
        <div class="shrink-0">
          <Navbar />
        </div>
        <div class="flex-1 relative flex flex-col justify-between overflow-y-auto custom-scrollbar">
          <slot />
        </div>
      </div>
    </div>
  </div>

  <!-- Theme Debug Panel with dark mode support -->
  <div :class="[
    'fixed bottom-4 right-4 rounded-lg p-3 shadow-lg z-50 text-sm transition-colors duration-300',
    themeStore.isDarkMode
      ? 'bg-gray-800/80 text-white'
      : 'bg-white/80 text-gray-900'
  ]">
    <div class="font-medium">Theme Debug:</div>
    <div :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'">
      Archetype: {{ themeStore.currentArchetype }}
    </div>
    <div :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'">
      Theme: {{ themeStore.currentTheme.primary }}
    </div>
    <div :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'">
      Dark Mode: {{ themeStore.isDarkMode ? 'On' : 'Off' }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, usePage, Link } from '@inertiajs/vue3'
import { Search, Bell } from 'lucide-vue-next'
import { UserIcon } from '@heroicons/vue/24/outline'
import { Sheet, SheetContent, SheetHeader, SheetTrigger } from "@/Components/ui/sheet"
import Navbar from '@/Components/Navbar.vue'
import { useThemeStore } from '@/stores/theme/themeStore'

const isOpen = ref(false)
const themeStore = useThemeStore()

onMounted(() => {
  themeStore.initializeTheme()
})

defineProps({
  title: {
    type: String,
    required: true
  }
})

const logout = () => {
  router.post(route('logout'));
};
</script>

<style>
/* Add dark mode variables */
:root {
  --scrollbar-color: rgba(156, 163, 175, 0.5);
  --scrollbar-hover-color: rgba(156, 163, 175, 0.7);
}

:root.dark {
  --scrollbar-color: rgba(75, 85, 99, 0.5);
  --scrollbar-hover-color: rgba(75, 85, 99, 0.7);
}

/* Rest of your styles */
.font-japanese {
  font-family: "Noto Sans JP", sans-serif;
}

.font-display {
  font-family: "Montserrat", sans-serif;
}

/* Custom Scrollbar Styles */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: var(--scrollbar-color) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: var(--scrollbar-color);
  border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: var(--scrollbar-hover-color);
}
</style>