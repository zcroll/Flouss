<script setup lang="ts">
interface Props {
  status: number;
  message: string;
}

defineProps<Props>();
</script>

<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-gray-800 mb-4">
        Error {{ status }}
      </h1>
      <p class="text-xl text-gray-600">{{ message }}</p>
      <inertia-link href="/"
        class="mt-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
        Back to Home
      </inertia-link>
    </div>
  </div>
</template>