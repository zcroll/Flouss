<template>
  <div class="w-full h-full border rounded-lg p-10 bg-white/5 relative">
    <!-- Avatar -->
    <ResultArchetypeAvatar 
      :archetype="archetypeDiscovery?.slug || 'default'"
      :key="archetypeDiscovery?.slug"
      class="w-full h-full" 
    />
    <!-- Modified button container positioning -->
    <Link 
        :href="`/results/${userId}/personality`"
        class="block w-48 mx-auto text-sm font-medium px-4 py-2 text-center transition-all duration-200 rounded-lg border"
        :class="[
          `bg-${themeStore.currentTheme.button}`,
          `hover:bg-${themeStore.currentTheme.hover}`,
          `border-${themeStore.currentTheme.border}`,
          `ring-${themeStore.currentTheme.ring}`,
          'text-white'
        ]"
      >
        {{ __('results.View_Personality_Details') }}
      </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import ResultArchetypeAvatar from './ResultArchetypeAvatar.vue';

const themeStore = useThemeStore();

const props = defineProps({
  userId: {
    type: String,
    required: true
  },
  archetypeDiscovery: {
    type: Object,
    required: true
  },
  topTraits: {
    type: Object,
    required: true,
    default: () => ({})
  }
});
</script>

<style scoped>
/* Glass Effects */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.transition-all {
  transition: all 0.2s ease;
}
</style>
