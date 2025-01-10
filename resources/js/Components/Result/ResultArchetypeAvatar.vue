<template>
  <div class="w-full h-full relative">
    <!-- Main Animation Container -->
    <div class="w-full h-full relative">
      <lottie-player
        :key="animationKey"
        :src="animationPath"
        :autoplay="true"
        :loop="true"
        class="w-full h-full object-cover"
        @ready="handleAnimationReady"
      />
    </div>

    <!-- Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none">
      <!-- Glow Effect -->
      <div class="absolute inset-0 rounded-full opacity-75 blur-2xl"
           :class="`bg-${themeColor}-400/30`">
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import '@lottiefiles/lottie-player';

// Animation mapping object
const animationMapping = {
    // Sentinels
    'caregiver': 'defender',
    'designer': 'executive',
    'guardian': 'consul',
    'mentor': 'consul',
    'producer': 'logistician',
    'protector': 'defender',
    'scholar': 'logistician',

    // Diplomats
    'advocat': 'advocate',
    'anchor': 'protagonist',
    'captain': 'protagonist',
    'composer': 'advocate',
    'humanitarian': 'mediator',
    'kingpin': 'protagonist',
    'philosopher': 'advocate',
    'supporter': 'campaigner',

    // Analysts
    'architect': 'architect',
    'builder': 'virtuoso',
    'explorer': 'campaigner',
    'groundbreaker': 'commander',
    'researcher': 'logician',
    'strategist': 'architect',

    // Explorers
    'creator': 'adventurer',
    'enthusiast': 'entrepreneur',
    'innovator': 'entrepreneur',
    'inventor': 'logistician',
    'luminary': 'entertainer',
    'mastermind': 'architect',
    'maverick': 'debater',
    'technician': 'virtuoso',
    'visionary': 'debater'
};

const props = defineProps({
  archetype: {
    type: String,
    required: true
  }
});

const themeStore = useThemeStore();
const animationKey = ref(0);
const isAnimationReady = ref(false);

const themeColor = computed(() => {
  return themeStore.currentTheme.button.primary;
});

const animationPath = computed(() => {
  const archetypeName = props.archetype.toLowerCase();
  // Get the mapped animation name or use the original if no mapping exists
  const mappedName = animationMapping[archetypeName] || archetypeName;
  return `/results_folder/${mappedName}.json`;
});

const handleAnimationReady = () => {
  isAnimationReady.value = true;
};

const getParticleStyle = (index) => {
  return {
    '--delay': `${index * 2}s`,
    '--size': `${Math.random() * 4 + 2}px`,
    left: `${Math.random() * 100}%`,
    top: `${Math.random() * 100}%`
  };``
};

onMounted(() => {
  // Refresh animation when component mounts
  animationKey.value++;
});
</script>

<style scoped>
.particles-container {
  z-index: 1;
}

.particle {
  width: var(--size);
  height: var(--size);
  background: currentColor;
  border-radius: 50%;
  animation: float 6s ease-in-out infinite;
  animation-delay: var(--delay);
  opacity: 0.5;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) scale(1);
    opacity: 0.5;
  }
  50% {
    transform: translateY(-20px) scale(1.1);
    opacity: 0.8;
  }
}

/* Smooth transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Animation container styles */
:deep(.lottie-player) {
  width: 100%;
  height: 100%;
}

/* Add scale animation on hover */
.result-avatar-container {
  transition: transform 0.3s ease;
}

.result-avatar-container:hover {
  transform: scale(1.05);
}
</style> 