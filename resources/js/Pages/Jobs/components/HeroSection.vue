<template>
  <div class="relative rounded-3xl p-8 overflow-hidden min-h-[200px] flex items-center"
    :class="[themeStore.getThemeClasses('base')]">
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0 animate-wave">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
          <path fill="currentColor" d="M0,50 Q25,45 50,50 T100,50 L100,100 L0,100 Z" />
        </svg>
      </div>
    </div>

    <div class="relative z-10">
      <h1
        class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
        {{ title }}
      </h1>
      <p class="text-lg max-w-2xl text-gray-600">
        {{ description }}
      </p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  description: String,
  themeStore: Object
});
</script>