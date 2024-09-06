<template>
  <AppLayout>
    <StickySidebar :slug="occupation.slug" :title="occupation.name" :image="occupation.image">
      <div class="w-full lg:w-4/4 space-y-1">
        <div class="p-8 max-w-5xl mx-auto space-y-8">
          <h1 class="text-3xl font-extrabold text-gray-900">What is a {{ occupation.name }}</h1>

          <!-- Display personality traits and details here -->
          <h2>Personality Traits:</h2>
          <ul>
            <li v-for="(trait, index) in personalityTraits" :key="index">{{ trait }}</li>
          </ul>

          <h2>Personality Details:</h2>
          <ul>
            <li v-for="(detail, index) in personalityDetails" :key="index">{{ detail }}</li>
          </ul>
        </div>
      </div>
    </StickySidebar>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  occupation: {
    type: Object,
    required: true,
  },
  personalityTraits: {
    type: Array,
    required: true,
  },
  personalityDetails: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
/* add your styles here */
</style>
