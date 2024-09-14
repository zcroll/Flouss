<template>
  <AppLayout :head-title="'Jobs'" :show-search="true" name="Jobs">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Explore Jobs</h1>
      
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filter sidebar -->
        <div class="w-full lg:w-1/3">
          <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Filters</h2>
            
            <div class="mb-3">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <input
                id="search"
                v-model="searchQuery"
                type="text"
                placeholder="Search jobs"
                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                @input="debouncedSearch"
              />
            </div>

            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-700 mb-1">Education Levels</label>
              <VueMultiselect
                v-model="selectedEducationLevels"
                :options="educationLevelOptions"
                :multiple="true"
                :close-on-select="false"
                placeholder="Select education levels"
                label="label"
                track-by="value"
                class="custom-multiselect"
              />
            </div>

            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
              <select
                v-model="selectedSort"
                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                @change="applyFilters"
              >
                <option value="">Default</option>
                <option value="salary_desc">Highest Salary</option>
                <option value="satisfaction_desc">Highest Satisfaction</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-3/4">
          <div class="space-y-3">
            <div v-for="job in jobs.data" :key="job.id" class="bg-white shadow-sm rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200">
              <div class="flex flex-row items-center">
                <img :src="job.image" :alt="job.name" class="w-24 h-24 object-cover">
                <div class="p-3 flex-grow">
                  <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ job.name }}</h2>
                  <p class="text-sm text-gray-600 mb-2">{{ job.slug }}</p>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-600 font-medium">Salary: ${{ job.salary }}</span>
                    <button class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-300">
                      Learn More
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <Pagination 
              :links="jobs.links" 
              :meta="{
                current_page: jobs.meta.current_page,
                from: jobs.meta.from,
                to: jobs.meta.to,
                total: jobs.meta.total
              }" 
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  jobs: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.q || '');
const selectedEducationLevels = ref(props.filters.education || []);
const selectedSort = ref(props.filters.sort || '');

const debouncedSearch = debounce(() => {
  applyFilters();
}, 300);

watch(searchQuery, (value) => {
  debouncedSearch();
});

const applyFilters = () => {
  router.get('/jobs', 
    { 
      q: searchQuery.value,
      education: selectedEducationLevels.value.map(level => level.value),
      sort: selectedSort.value,
    }, 
    { 
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};

const educationLevelOptions = [
  { value: 2, label: 'High School' },
  { value: 5, label: 'Associate' },
  { value: 7, label: 'Bachelor' },
  { value: 8, label: 'Master' },
  { value: 9, label: 'Doctorate' },
  { value: 3, label: 'Other' }
];

onMounted(() => {
  searchQuery.value = props.filters.q || '';
});

watch([selectedEducationLevels, selectedSort], () => {
  applyFilters();
}, { deep: true });
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style>
.custom-multiselect {
  --ms-tag-bg: #4f46e5;
  --ms-tag-color: #ffffff;
  --ms-option-bg-selected: #4f46e5;
}
</style>