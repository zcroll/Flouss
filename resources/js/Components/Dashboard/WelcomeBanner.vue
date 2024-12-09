<template>
  <div class="relative bg-gradient-to-br from-violet-50 via-white to-violet-50 rounded-xl p-8 mb-4 border border-gray-200 overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-[#E05B42]/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-violet-100/80 rounded-full blur-3xl -ml-32 -mb-32"></div>
    
    <div class="relative flex flex-col md:flex-row justify-between items-center gap-8">
      <div class="flex-1 space-y-6">
        <div class="space-y-2">
          <div class="flex items-center gap-3">
            <div class="w-2 h-2 bg-[#E05B42] rounded-full animate-pulse"></div>
            <p class="text-[#E05B42] font-medium">{{ __('dashboard.dashboard') }}</p>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold">
            <span class="bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
              {{ __('dashboard.welcome') }} 
            </span>
            &nbsp;
            <span class="bg-gradient-to-r from-[#E05B42] to-[#F07B66] bg-clip-text text-transparent">
               {{ username }}!
            </span>
          </h1>
        </div>
        
        <p class="text-lg text-gray-600 max-w-xl leading-relaxed">
          {{ __('dashboard.orientation_process') }}
        </p>

        <div class="flex items-center gap-4">
          <Link v-if="!hasResult" :href="route('holland-codes.index')"
            class="group relative inline-flex items-center px-6 py-3 bg-[#E05B42] hover:bg-[#E05B42]/90 text-white font-medium rounded-lg transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-[#E05B42]/20">
            {{ __('dashboard.begin_assessment') }}
            <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </Link>
          <div class="hidden md:flex items-center gap-2 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="text-sm">Secure Assessment</span>
          </div>
        </div>
      </div>

      <div class="hidden md:block">
        <div class="relative w-48 h-48 bg-gradient-to-br from-[#E05B42]/20 to-[#F07B66]/10 rounded-2xl shadow-xl">
          <div class="absolute inset-1 bg-white rounded-xl flex items-center justify-center border border-[#E05B42]/20">
            <svg class="w-24 h-24 text-[#E05B42]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  username: {
    type: String,
    required: true
  },
  hasResult: {
    type: Boolean,
    default: false
  }
});
</script>
