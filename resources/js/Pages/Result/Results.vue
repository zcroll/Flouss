<template>
  <div class="flex-1 flex flex-col container mx-auto max-w-8xl">

  <Head title="Results" />

  <div class="space-y-12">
    <!-- Hero Section with Archetype -->
    <Deferred :data="['Archetype', 'archetypeDiscovery']">
      <template #fallback>
        <div class="w-full h-screen rounded-3xl animate-pulse" :class="themeStore.getThemeClasses('background').light">
        </div>
      </template>

      <!-- Archetype Section -->
          <Folder 
            :archetype="Archetype" 
            :archetype-jobs="ArchetypeJobs" 
            :archetype-discovery="archetypeDiscovery"
            :user-id="userId" 
            :top-traits="topTraits" 
            class="w-full h-[450.438px]"
          />
   
       
          

      
    </Deferred>

    <!-- Careers Section -->
    <Deferred data="jobs">
      <template #fallback>
        <div class="space-y-6">
          <div class="h-8 rounded-xl w-48 animate-pulse" >
          </div>
          <SkeletonLoader />
        </div>
      </template>

      <div class="space-y-4">
        <RecommendationHeader :headerConfig="{
          title: __('results.top_careers'),
          viewAllLink: '/jobs/',
          viewAllText: __('results.View_All_careers')
        }" class="rounded-2xl p-4 border" :class="[
      
        ]" />
        <div class="" :class="[
       
        ]">
          <CareersList :displayed-jobs="displayedJobs" :show-all-jobs="showAllJobs"
            :has-more-jobs="jobs && jobs.length > displayedJobs.length" @select-job="selectedJob = $event"
            @toggle-show-more="showAllJobs = true" />
        </div>
      </div>
    </Deferred>

    <!-- Degrees Section -->
    <Deferred data="degrees">
      <template #fallback>
        <div class="space-y-6">
          <div class="h-8 rounded-xl w-48 animate-pulse" :class="themeStore.getThemeClasses('background').light">
          </div>
          <SkeletonLoader />
        </div>
      </template>

      <div class="space-y-4">
        <RecommendationHeader :headerConfig="{
          title: __('results.top_degrees'),
          viewAllLink: '/degrees/',
          viewAllText: __('results.View_All_degrees')
        }" class="rounded-2xl p-4 border" :class="[
  
        ]" />
        <div class="rounded-2xl " :class="[
  
        ]">
          <DegreeList :displayed-degrees="displayedDegrees" :show-all-degrees="showAllDegrees"
            :has-more-degrees="degrees && degrees.length > displayedDegrees.length"
            @select-degree="selectedDegree = $event" @toggle-show-more="showAllDegrees = true" />
        </div>
      </div>
    </Deferred>

    <!-- DataShare section -->
    <div class="bg-white/40 backdrop-blur-sm rounded-3xl border border-white/20 shadow-xl p-8">
      <DataShare />
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, Deferred } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import Folder from '@/Components/Result/Folder.vue';
import DataShare from '@/Components/Result/DataShare.vue';
import RecommendationHeader from '@/Components/Result/RecommendationHeader.vue';
import CareersList from '@/Components/Result/CareersList.vue';
import DegreeList from '@/Components/Result/DegreeList.vue';
import Feedback from '@/Components/Result/Feedback.vue';
import SkeletonLoader from '@/Components/Result/SkeletonLoader.vue';
import { useForm } from '@inertiajs/vue3';
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
  userId: {
    type: String,
    required: true
  },
  jobs: {
    type: Object,
    required: true
  },
  Archetype: {
    type: Object,
    required: true
  },
  ArchetypeJobs: {
    type: Array,
    required: false
  },
  archetypeDiscovery: {
    type: Object,
    required: true
  },
  hasGivenFeedback: {
    type: Boolean,
    required: true
  },
  degrees: {
    type: Array,
    required: true
  },
  topTraits: {
    type: Object,
    required: true,
    default: () => ({})
  }
});

defineOptions({
  layout: MainLayout,
})

const selectedJob = ref(null);
const showAllJobs = ref(false);
const selectedDegree = ref(null);
const showAllDegrees = ref(false);

const displayedJobs = computed(() => {
  if (!props.jobs) return [];
  if (showAllJobs.value) return props.jobs;
  return props.jobs.slice(0, Math.ceil(props.jobs.length / 2));
});

const displayedDegrees = computed(() => {
  if (!props.degrees) return [];
  if (showAllDegrees.value) return props.degrees;
  return props.degrees.slice(0, Math.ceil(props.degrees.length / 2));
});

const form = useForm({
  rating: 0,
  feedback: '',
});

const submitFeedback = () => {
  form.post(route('feedback.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

const themeStore = useThemeStore();

onMounted(() => {
  themeStore.initializeTheme();
});
</script>

<style scoped>
/* Glass morphism effects */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Loading animation */
@keyframes pulse {

  0%,
  100% {
    opacity: 0.5;
  }

  50% {
    opacity: 0.7;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Add any additional styles needed */
.min-h-[100px] {
  min-height: 100px;
}

.result-folder {
  height: 422.438px;
  width: 100%;
}

.min-h-[60px] {
  min-height: 60px;
}

/* Add smooth transitions */
.result-folder :deep(.avatar-transition) {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Add any additional styling specific to the results page */

/* Remove hover transitions since we're not using them anymore */
.min-h-[100px] {
  min-height: 100px;
}

/* Add smooth loading transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
