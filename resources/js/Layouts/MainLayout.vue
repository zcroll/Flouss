<template>
  <div :class="[classes.gradient, 'h-screen p-4 overflow-hidden']">
    <div 
      class="w-full h-full max-w-[120rem] rounded-[48px] p-12 shadow-2xl relative overflow-hidden backdrop-blur-lg"
      :style="{
        backgroundPosition: '80% center',
        backgroundSize: 'contain',
        backgroundRepeat: 'no-repeat',
        backgroundColor: 'rgba(255, 255, 255, 0.4)',
        backdropFilter: 'blur(20px)',
      }"
    >
      <!-- Decorative circles - Now using dynamic colors -->
      <div 
        :class="[`bg-${themeColors.primary}-100`, 'absolute top-0 right-0 w-1/2 h-1/2 rounded-full blur-3xl -z-10 opacity-60']" 
      />
      <div 
        :class="[`bg-${themeColors.primary}-50`, 'absolute bottom-0 left-0 w-1/2 h-1/2 rounded-full blur-3xl -z-10 opacity-60']" 
      />
      
      <!-- Header -->
      <header class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-japanese tracking-wide">アニマンガヘブン</h1>
        <div class="flex items-center gap-6">
          <div class="relative group">
            <Search :class="[classes.icon, 'absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 group-hover:scale-110 transition-all duration-300']" />
            <input
              type="search"
              placeholder="Search"
              :class="[
                classes.focus,
                'pl-12 pr-4 py-2.5 rounded-full bg-white/90 w-56 text-base backdrop-blur-md transition-all duration-300'
              ]"
            />
          </div>
          <button class="relative group">
            <Bell :class="[classes.icon, 'h-6 w-6 group-hover:scale-110 transition-all duration-300']" />
            <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full" />
          </button>
          <div class="flex items-center gap-3">
            <span :class="[classes.text, 'text-base']">Hi, {{ $page.props.auth.user.name }}</span>
            <Sheet v-model:open="isOpen">
              <SheetTrigger>
                <div class="relative group cursor-pointer">
                  <div 
                    :class="[`bg-${themeColors.primary}-400/20`, 'absolute inset-0 rounded-full blur-md transition-opacity opacity-0 group-hover:opacity-100']"
                  ></div>
                  <img
                    :src="$page.props.auth.user.profile_photo_url"
                    :alt="$page.props.auth.user.name"
                    :class="[
                      classes.border,
                      'w-10 h-10 rounded-full border-2 transition-all duration-300 group-hover:scale-105 relative z-10'
                    ]"
                  />
                </div>
              </SheetTrigger>

              <SheetContent 
                side="right" 
                class="w-[280px] bg-white/95 backdrop-blur-2xl rounded-2xl border-none p-0"
                :class="[`shadow-[0_0_40px_rgba(var(--${themeColors.primary}-rgb),0.1)]`]"
              >
                <div class="p-6">
                  <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                      <div 
                        :class="[`bg-${themeColors.primary}-400/20`, 'absolute inset-0 rounded-full blur-lg']"
                      ></div>
                      <img
                        :src="$page.props.auth.user.profile_photo_url"
                        :alt="$page.props.auth.user.name"
                        :class="[
                          classes.border,
                          'w-12 h-12 rounded-full border-2 relative z-10'
                        ]"
                      />
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-900">{{ $page.props.auth.user.name }}</h3>
                      <p :class="[classes.text, 'text-sm']">{{ archetype }}</p>
                    </div>
                  </div>
                  
                  <div class="space-y-1">
                    <Link 
                      :href="route('profile.show')"
                      :class="[
                        `hover:bg-${themeColors.primary}-50`,
                        'flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors'
                      ]"
                      @click="isOpen = false"
                    >
                      <UserIcon :class="[classes.icon, 'w-5 h-5']" />
                      <span :class="[classes.text, 'text-sm font-medium']">Profile Settings</span>
                    </Link>
                    
                    <form @submit.prevent="logout" class="mt-4">
                      <button 
                        type="submit"
                        class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-colors"
                        @click="isOpen = false"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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

  <!-- Add this somewhere visible in your layout for debugging -->
  <div class="fixed bottom-4 right-4 bg-white/80 backdrop-blur-sm rounded-lg p-3 shadow-lg z-50 text-sm">
    <div class="font-medium">Theme Debug:</div>
    <div :class="[classes.text]">Archetype: {{ archetype }}</div>
    <div :class="[classes.text]">Theme: {{ themeColors.primary }}</div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Head, usePage, Link } from '@inertiajs/vue3'
import { Search, Bell } from 'lucide-vue-next'
import { UserIcon } from '@heroicons/vue/24/outline'
import { Sheet, SheetContent, SheetHeader, SheetTrigger } from "@/Components/ui/sheet"
import Navbar from '@/Components/Navbar.vue'
import { useArchetypeTheme } from '@/composables/useArchetypeTheme'

const isOpen = ref(false)
const archetype = computed(() => {
  const userArchetype = usePage().props.auth.user.archetype || 'Creator'
  console.log('Current Archetype:', userArchetype)
  return userArchetype
})

const { classes, themeColors } = useArchetypeTheme(archetype)

// Add watchers to monitor theme changes
watch(themeColors, (newColors) => {
  console.log('Theme Colors Changed:', newColors)
}, { deep: true })

watch(classes, (newClasses) => {
  console.log('Theme Classes Changed:', newClasses)
}, { deep: true })

// Log initial theme setup
onMounted(() => {
  console.log('Initial Theme Setup:', {
    archetype: archetype.value,
    colors: themeColors.value,
    classes: classes.value
  })
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

<style scoped>
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