<template>
  <AppLayout title="Results">
    <div class="DashboardPage">
      <section class="section">
        <Folder :archetype="Archetype" :archetype-jobs="ArchetypeJobs" :archetype-discovery="archetypeDiscovery"
          :user-id="userId" />
      </section>

      <section class="section">
        <TopCareersHeader />
        <CareersList 
          :displayed-jobs="displayedJobs"
          :show-all-jobs="showAllJobs"
          :has-more-jobs="jobs && jobs.length > displayedJobs.length"
          @select-job="selectedJob = $event"
          @toggle-show-more="showAllJobs = true"
        />
      </section>

      <section class="section">
        <DataShare />
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Folder from '@/Components/Result/Folder.vue';
import DataShare from '@/Components/Result/DataShare.vue';
import TopCareersHeader from '@/Components/Result/TopCareersHeader.vue';
import CareersList from '@/Components/Result/CareersList.vue';

const props = defineProps({
  userId: {
    type: Number,
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
    required: true
  },
  archetypeDiscovery: {
    type: Object,
    required: true
  }
});

const selectedJob = ref(null);
const showAllJobs = ref(false);

const displayedJobs = computed(() => {
  if (!props.jobs) return [];
  if (showAllJobs.value) return props.jobs;
  return props.jobs.slice(0, Math.ceil(props.jobs.length / 2));
});
</script>
