<template>
  <div class="absolute bottom-8 left-32 right-8 flex gap-6">
    <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow flex-1">
      <div class="flex items-center justify-between">
        <span class="text-lg font-medium text-gray-700">Complete</span>
        <div class="h-8 w-8 bg-yellow-400 rounded-full flex items-center justify-center">
          <ArrowRight class="h-5 w-5 text-white" />
        </div>
      </div>
      <div class="mt-2">
        <div class="text-gray-600 text-sm">Episodes</div>
        <div class="text-2xl font-bold text-gray-800">120</div>
      </div>
    </div>
    <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow flex-1">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-gray-600 text-sm">Chapters Read</div>
          <div class="text-2xl font-bold text-gray-800">350</div>
        </div>
        <div class="h-8 w-8 bg-yellow-400 rounded-full flex items-center justify-center">
          <ArrowRight class="h-5 w-5 text-white" />
        </div>
      </div>
    </div>
    <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow flex-1">
      <div class="flex items-center justify-between">
        <span class="text-lg font-medium text-gray-700">Watch</span>
        <div class="h-8 w-8 bg-yellow-400 rounded-full flex items-center justify-center">
          <ArrowRight class="h-5 w-5 text-white" />
        </div>
      </div>
      <div class="mt-2">
        <span class="text-gray-600 text-sm">Start Streaming</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ArrowRight } from 'lucide-vue-next'
</script>

<style scoped>
.font-display {
  font-family: "Montserrat", sans-serif;
}
</style> 