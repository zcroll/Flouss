<template>
  <Head title="Dashboard" />

  <div class="flex-1 flex items-center justify-center relative" :class="{ 'md:mt-[-10rem]': true }">
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative flex md:flex-row flex-col items-center md:gap-12 gap-6 px-4 md:px-0">
        <!-- Avatar/Mystery Box Container -->
        <GlowContainer 
          :theme="themeStore.currentTheme"
          :should-pulse="hasResult ? !isAvatarLoading : true"
          @show-details="showDetails = true"
          @hide-details="showDetails = false"
        >
          <template v-if="hasResult">
            <ArchetypeAvatar 
              v-if="archetypeSlug" 
              :key="avatarKey" 
              :archetype="archetypeSlug"
              class="w-full h-full transform scale-110 relative z-10" 
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
        <DetailPanel :show-details="showDetails">
          <template v-if="hasResult">
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
  <div v-if="hasResult" class="relative mt-8 md:mt-0">
    <BottomCards 
      :favorite-jobs="favoriteJobs" 
      :favorite-degrees="favoriteDegrees" 
      :archetype="archetype.slug" 
    />
    <AIChat 
      :predefined-questions="predefinedQuestions" 
      :initial-messages="chatHistory" 
      class="z-50" 
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick, inject } from 'vue'
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

// Methods
const handleAvatarLoaded = () => {
  if (!props.hasResult) return
  isAvatarLoading.value = false
  showDetails.value = true
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
}

// Lifecycle
onMounted(() => {
  isComponentMounted.value = true
  if (isLayoutInitialized.value) {
    initializeComponent()
  }
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
</script>
