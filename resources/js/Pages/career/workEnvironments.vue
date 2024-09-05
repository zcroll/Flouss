<template>
  <appLayout>
    <StickySidebar :slug="occupation.slug" :title="occupation.name" :image="occupation.image">

      <div class="w-full lg:w-4/4 space-y-1">
        <div class="p-8 max-w-5xl mx-auto space-y-8">
          <h1 class="text-3xl font-extrabold text-gray-900">What is a {{ occupation.name }}</h1>

          <!-- Display work environment details here -->
          <h2>Typical Work Environments:</h2>
          <ul>
            <li v-for="(environment, index) in workEnvironments" :key="index">{{ environment }}</li>
          </ul>
        </div>
      </div>
    </StickySidebar>
  </appLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import { usePage } from '@inertiajs/vue3';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  occupation: {
    type: Object,
    required: true,
  },
  workEnvironments: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

h2 {
  font-size: 1.75rem;
  margin-bottom: 0.5rem;
}

p {
  line-height: 1.6;
}

.text-gray-900 {
  color: #1a202c;
}

.text-gray-700 {
  color: #4a5568;
}
</style>
