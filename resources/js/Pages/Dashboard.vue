<template>
  <Head title="Dashboard" />

  <div class="flex-1 flex items-center justify-center relative" 
       :class="{ 
         'md:mt-[-2rem]': !isMobile,
         'mt-0': isMobile,
         'px-4': isMobile
       }">
    <div class="relative w-full flex items-center justify-center min-h-[60vh]">
      <div class="relative flex md:flex-row flex-col items-center md:gap-12 gap-6 w-full md:w-auto">
        <!-- Avatar/Mystery Box Container -->
        <GlowContainer 
          :theme="themeStore.currentTheme"
          :should-pulse="hasResult ? !isAvatarLoading : true"
          @show-details="handleShowDetails"
          @hide-details="handleHideDetails"
          :class="[
            'w-full md:w-auto',
            isMobile ? 'max-w-[280px] mx-auto mt-24' : 'mt-[-4rem]'
          ]"
        >
          <template v-if="!hasResult">
            <ArchetypeAvatar 
              v-if="archetypeSlug" 
              :key="avatarKey" 
              :archetype="archetypeSlug"
              :class="[
                'w-full h-full transform relative z-10',
                isMobile ? 'scale-125' : 'scale-110'
              ]"
              @loaded="handleAvatarLoaded" 
            />
          </template>
          <template v-else>
            <MysteryBox 
              class="w-full h-full relative z-10"
              :is-hovering="showDetails"
            />
          </template>
        </GlowContainer>

        <!-- Details Panel -->
        <DetailPanel 
          :show-details="shouldShowDetails"
          :class="[
            isMobile ? 'w-full max-w-[340px] mx-auto mt-4' : 'w-[340px] mt-[-4rem]',
            'z-20'
          ]"
        >
          <template v-if="!hasResult">
            <ArchetypePanel 
              :archetype="archetype"
              :top-traits="topTraits"
              :theme="{
                primary: themeStore.currentTheme.primary,
                isDarkMode: themeStore.currentTheme.isDarkMode
              }"
            />
          </template>
          <template v-else>
            <MysteryPanel 
              :theme="themeStore.currentTheme"
              :show-details="showDetails"
            />
          </template>
        </DetailPanel>
      </div>
    </div>
  </div>

  <!-- Bottom section -->
  <div v-if="hasResult" 
       class="relative px-4 md:px-0"
       :class="{ 
         'pb-20': isMobile,
         'mt-8': isMobile,
         'md:mt-[-4rem]': !isMobile
       }"
  >
    <BottomCards 
      :favorite-jobs="favoriteJobs" 
      :favorite-degrees="favoriteDegrees" 
      :archetype="archetype.slug"
      :is-mobile="isMobile"
      class="relative z-10 max-w-5xl mx-auto md:translate-y-0"
    />
    <AIChat 
      :predefined-questions="predefinedQuestions" 
      :initial-messages="chatHistory"
      :is-mobile="isMobile"
      class="z-30" 
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick, inject, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import { useThemeStore } from '@/stores/theme/themeStore'
import { useNavigationStore } from '@/stores/navigation/navigationStore'
import MainLayout from '@/Layouts/MainLayout.vue'

// Import components with correct paths
import GlowContainer from '../Components/Dashboard/shared/GlowContainer.vue'
import DetailPanel from '../Components/Dashboard/shared/DetailPanel.vue'
import ArchetypePanel from '../Components/Dashboard/ArchetypePanel/index.vue'
import MysteryPanel from '../Components/Dashboard/MysteryPanel/index.vue'
import ArchetypeAvatar from '../Components/Dashboard/ArchetypeAvatar.vue'
import MysteryBox from '../Components/Dashboard/MysteryBox.vue'
import BottomCards from '../Components/Dashboard/BottomCards.vue'
import AIChat from '../Components/Dashboard/AIChat.vue'

defineOptions({
  layout: MainLayout
})

const props = defineProps({
  hasResult: {
    type: Boolean,
    default: false
  },
  favoriteJobs: {
    type: Array,
    default: () => []
  },
  favoriteDegrees: {
    type: Array,
    default: () => []
  },
  archetype: {
    type: Object,
    default: () => ({})
  },
  topTraits: {
    type: Object,
    default: () => ({})
  },
  chatHistory: {
    type: Array,
    default: () => []
  },
  predefinedQuestions: {
    type: Array,
    default: () => []
  }
})

// Core state
const showDetails = ref(false)
const avatarKey = ref(0)
const isAvatarLoading = ref(false)
const isComponentMounted = ref(false)

// Store and injected values
const themeStore = useThemeStore()
const navigationStore = useNavigationStore()
const isLayoutInitialized = inject('isLayoutInitialized')

// Computed
const archetypeSlug = computed(() => props.hasResult ? props.archetype?.slug : null)

// Add mobile detection
const isMobile = ref(false)

// Add computed property for controlling details visibility
const shouldShowDetails = computed(() => {
  if (isMobile.value && props.hasResult) {
    return true // Always show on mobile if there's a result
  }
  return showDetails.value
})

// Update the show/hide detail handlers
const handleShowDetails = () => {
  if (!isMobile.value || !props.hasResult) {
    showDetails.value = true
  }
}

const handleHideDetails = () => {
  if (!isMobile.value || !props.hasResult) {
    showDetails.value = false
  }
}

// Update avatar loaded handler
const handleAvatarLoaded = () => {
  if (!props.hasResult) return
  isAvatarLoading.value = false
  if (!isMobile.value) {
    showDetails.value = true
  }
}

// Update checkMobile function
const checkMobile = () => {
  isMobile.value = window.innerWidth < 768
}

// Add resize handler
const handleResize = () => {
  checkMobile()
}

// Initialization
const initializeComponent = async () => {
  if (!isComponentMounted.value || !isLayoutInitialized.value) return
  
  if (props.hasResult) {
    isAvatarLoading.value = true
    await nextTick()
    if (props.archetype?.slug) {
      avatarKey.value++
    }
  }
  
  // Update mobile-specific initialization
  if (isMobile.value) {
    if (props.hasResult) {
      showDetails.value = true // Always show details on mobile with result
    } else {
      showDetails.value = false
    }
  }
}

// Lifecycle
onMounted(() => {
  isComponentMounted.value = true
  checkMobile() // Check initial mobile state
  window.addEventListener('resize', handleResize)
  
  if (isLayoutInitialized.value) {
    initializeComponent()
  }
})

// Clean up event listeners
onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

// Watchers
watch([isLayoutInitialized, () => isComponentMounted.value], 
  ([layoutInit, componentInit]) => {
    if (layoutInit && componentInit) {
      initializeComponent()
    }
  }, 
  { immediate: true }
)

if (props.hasResult) {
  watch(() => props.archetype, async () => {
    if (!isComponentMounted.value || !isLayoutInitialized.value) return
    isAvatarLoading.value = true
    await nextTick()
    avatarKey.value++
  }, { deep: true })
}

// Update mobile watcher
watch(isMobile, (newValue) => {
  if (newValue) {
    // Show details on mobile only if there's a result
    showDetails.value = props.hasResult
  }
})
</script>

<style scoped>
/* Add mobile-specific styles */
@media (max-width: 768px) {
  /* Optimize transitions for mobile */
  .transition-all {
    transition-duration: 200ms;
  }

  /* Improve touch targets */
  .cursor-pointer {
    @apply active:scale-95;
  }

  /* Add viewport-based positioning */
  .mt-[45vh] {
    margin-top: 45vh;
  }

  .mt-[10vh] {
    margin-top: 10vh;
  }
}

/* Add desktop-specific styles */
@media (min-width: 769px) {
  .min-h-[60vh] {
    min-height: 60vh;
  }
}
</style>
