<template>
  <Header :can-login="false" :can-register="false" />
  <div class="landinghero wf-section">
    <HeroSection />
    <WorldClass />
  </div>
  <Benefits />
  <Experience />
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import Header from '@/Components/Home/Navbar/Header.vue';
import WorldClass from '@/Components/Home/ForOrganizations/WorldClass.vue';
import Benefits from '@/Components/Home/ForOrganizations/Benefits.vue';
import Experience from '@/Components/Home/ForOrganizations/Experience.vue';
import HeroSection from '@/Components/Home/ForOrganizations/HeroSection.vue';

const handleOrbs = () => {
  const w = document.getElementById("welcome");
  const orbs = document.getElementById("orbs");
  if (w && orbs) {
    if (window.scrollY >= w.getBoundingClientRect().height) {
      orbs.style.opacity = 0.4;
    } else {
      orbs.style.opacity = 0.6;
    }
  }
}

defineProps({
  canLogin: {
    type: Boolean,
    required: true
  },
  canRegister: {
    type: Boolean,
    required: true
  }
});

onMounted(() => {
  window.addEventListener("scroll", handleOrbs);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleOrbs);
});
</script>

<style scoped>
@import '/public/css/ForOrganizations.css';
</style>
