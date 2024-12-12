<template>
  <AppLayout title="Results">
    <div class="min-h-screen">
      <!-- Hero Section with Archetype -->
      <Deferred :data="['Archetype', 'archetypeDiscovery']">
        <template #fallback>
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-gray-100 h-64 rounded-2xl"></div>
          </div>
        </template>
        
        <div class="relative overflow-hidden">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Folder 
              :archetype="Archetype" 
              :archetype-jobs="ArchetypeJobs" 
              :archetype-discovery="archetypeDiscovery"
              :user-id="userId" 
              class="shadow-xl rounded-2xl"
            />
          </div>
        </div>
      </Deferred>

      <!-- Careers Section -->
      <Deferred data="jobs">
        <template #fallback>
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-6">
              <!-- Header Skeleton -->
              <div class="h-8 bg-gray-100 rounded w-48"></div>
              <SkeletonLoader />
            </div>
          </div>
        </template>

        <!-- Actual content -->
        <div class="relative">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
              <TopCareersHeader />
              <CareersList 
                :displayed-jobs="displayedJobs" 
                :show-all-jobs="showAllJobs"
                :has-more-jobs="jobs && jobs.length > displayedJobs.length" 
                @select-job="selectedJob = $event"
                @toggle-show-more="showAllJobs = true" 
              />
            </div>
          </div>
        </div>
      </Deferred>

      <!-- Degrees Section -->
      <Deferred data="degrees">
        <template #fallback>
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-6">
              <!-- Header Skeleton -->
              <div class="h-8 bg-gray-100 rounded w-48"></div>
              <SkeletonLoader />
            </div>
          </div>
        </template>

        <!-- Actual content -->
        <div class="relative">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
              <TopCareersHeader />
              <DegreeList 
                :displayed-degrees="displayedDegrees" 
                :show-all-degrees="showAllDegrees"
                :has-more-degrees="degrees && degrees.length > displayedDegrees.length" 
                @select-degree="selectedDegree = $event"
                @toggle-show-more="showAllDegrees = true" 
              />
            </div>
          </div>
        </div>
      </Deferred>

      <!-- DataShare section -->
      <div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          <DataShare class="rounded-3xl shadow-sm p-8" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, Deferred } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Folder from '@/Components/Result/Folder.vue';
import DataShare from '@/Components/Result/DataShare.vue';
import TopCareersHeader from '@/Components/Result/TopCareersHeader.vue';
import CareersList from '@/Components/Result/CareersList.vue';
import DegreeList from '@/Components/Result/DegreeList.vue';
import Feedback from '@/Components/Result/Feedback.vue';
import SkeletonLoader from '@/Components/Result/SkeletonLoader.vue';
import { useForm } from '@inertiajs/vue3';

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
  }
});

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
</script>
