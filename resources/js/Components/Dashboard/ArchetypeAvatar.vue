<template>
  <div class="relative w-full h-full">
    <!-- Lottie Animation Container -->
    <div ref="lottieContainer" class="w-full h-full"></div>
    
    <!-- Fallback Avatar if animation fails -->
    <div v-if="showFallback" class="absolute inset-0 flex items-center justify-center">
      <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
        <span class="text-4xl font-bold text-white">{{ getInitials(archetype) }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount, computed } from 'vue'
import lottie from 'lottie-web'
import { useToast } from 'vue-toastification'

const props = defineProps({
  archetype: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['loaded'])
const toast = useToast()
const lottieContainer = ref(null)
const showFallback = ref(false)
let animation = null

const animationMapping = {
  // Analysts
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

  // Sentinels
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
}

const getInitials = (str) => {
  return str
    .split(/[\s-_]+/)
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const getAnimationPath = (archetype) => {
  const mappedType = animationMapping[archetype.toLowerCase()]
  return mappedType ? `/personality_animations/${mappedType}_animation.json` : null
}

const loadAnimation = async () => {
  if (animation) {
    animation.destroy()
  }

  showFallback.value = false

  try {
    const animationPath = getAnimationPath(props.archetype)
    if (!animationPath) {
      throw new Error('No animation mapping found')
    }

    const response = await fetch(animationPath)
    if (!response.ok) {
      throw new Error('Animation file not found')
    }
    
    const animationData = await response.json()

    animation = lottie.loadAnimation({
      container: lottieContainer.value,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      animationData
    })

    animation.addEventListener('DOMLoaded', () => {
      emit('loaded')
    })
  } catch (error) {
    console.error('Error loading animation:', error)
    showFallback.value = true
    emit('loaded')
    
    toast.info("Using fallback avatar display", {
      timeout: 2000,
      position: "bottom-right"
    })
  }
}

// Watch for archetype changes
watch(() => props.archetype, () => {
  loadAnimation()
}, { immediate: false })

onMounted(() => {
  loadAnimation()
})

onBeforeUnmount(() => {
  if (animation) {
    animation.destroy()
  }
})
</script>

<style scoped>
.fallback-avatar {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style> 