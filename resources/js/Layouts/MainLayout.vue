<template>
  <div class="h-screen bg-gradient-to-br from-yellow-100 via-white to-yellow-50 p-4 overflow-hidden">
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
      <!-- Decorative circles -->
      <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-yellow-100 rounded-full blur-3xl -z-10 opacity-60" />
      <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-yellow-50 rounded-full blur-3xl -z-10 opacity-60" />
      
      <!-- Header -->
      <header class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-japanese tracking-wide">アニマンガヘブン</h1>
        <div class="flex items-center gap-6">
          <div class="relative">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
            <input
              type="search"
              placeholder="Search"
              class="pl-12 pr-4 py-2.5 rounded-full bg-white/90 w-56 text-base backdrop-blur-md"
            />
          </div>
          <button class="relative">
            <Bell class="h-6 w-6 text-gray-700" />
            <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full" />
          </button>
          <div class="flex items-center gap-3">
            <span class="text-base">Hi, {{ $page.props.auth.user.name }}</span>
            <img
              :src="$page.props.auth.user.profile_photo_url"
              :alt="$page.props.auth.user.name"
              class="w-10 h-10 rounded-full border-2 border-yellow-400"
            />
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <div class="flex gap-8 h-[calc(100%-theme(spacing.8))] justify-center">
        <!-- Sidebar -->
        <div class="shrink-0">
          <Navbar />
        </div>

        <!-- Page Content -->
        <div class="flex-1 relative flex flex-col justify-between overflow-y-auto custom-scrollbar">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { Search, Bell } from 'lucide-vue-next'
import Navbar from '@/Components/Navbar.vue'

defineProps({
  title: {
    type: String,
    required: true
  }
})
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
  scrollbar-color: rgba(250, 204, 21, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(250, 204, 21, 0.3);
  border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(250, 204, 21, 0.5);
}
</style> 