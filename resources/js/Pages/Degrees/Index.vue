<template>
  <AppLayout :head-title="'Degrees'" :show-search="true" name="Degrees">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Explore Degrees</h1>
      
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filter sidebar -->
        <div class="w-full lg:w-1/4">
          <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Filters</h2>
            
            <div class="mb-4">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <input
                id="search"
                v-model="searchQuery"
                type="text"
                placeholder="Search degrees"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                @input="debouncedSearch"
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Industries</label>
              <VueMultiselect
                v-model="selectedIndustries"
                :options="industries"
                :multiple="true"
                :close-on-select="false"
                placeholder="Select industries"
                label="name"
                track-by="id"
                class="custom-multiselect"
              />
            </div>

            <div class="space-y-2">
              <button
                v-for="filter in ['highPaying', 'attainable', 'partTime', 'noDegree']"
                :key="filter"
                @click="applyFilter(filter)"
                class="w-full px-4 py-2 text-sm font-medium rounded-md"
                :class="activeFilter === filter ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'"
              >
                {{ getFilterLabel(filter) }}
              </button>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-3/4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="degree in degrees.data" :key="degree.id" class="bg-white shadow-md rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">
              <img :src="degree.image" :alt="degree.name" class="w-full h-48 object-cover">
              <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ degree.name }}</h2>
                <p class="text-gray-600 mb-4">{{ degree.slug }}</p>
                <div class="flex items-center justify-between">
                  <span class="text-indigo-600 font-medium">Salary: ${{ degree.salary }}</span>
                  <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-300">
                    Learn More
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8">
            <Pagination 
              :links="degrees.links" 
              :meta="{
                current_page: degrees.meta.current_page,
                from: degrees.meta.from,
                to: degrees.meta.to,
                total: degrees.meta.total
              }" 
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  degrees: Object,
  filters: Object,
});

const search = ref(props.filters.search);
const selectedIndustries = ref([]);
const activeFilter = ref('');
const searchQuery = ref('');

const debouncedSearch = debounce(() => {
  router.get('/degrees', 
    { search: searchQuery.value }, 
    { 
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
}, 300);

watch(searchQuery, (value) => {
  debouncedSearch();
});

const applyFilter = (filter) => {
  activeFilter.value = filter;
  // Implement filter logic here
  router.get('/degrees', 
    { 
      search: searchQuery.value,
      filter: filter
    }, 
    { 
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};

const getFilterLabel = (filter) => {
  const labels = {
    highPaying: 'High Paying',
    attainable: 'Most Attainable',
    partTime: 'Part-Time Options',
    noDegree: 'No Degree Required'
  };
  return labels[filter] || filter;
};

const industries = [
  { id: 1, name: 'Technology' },
  { id: 2, name: 'Healthcare' },
  { id: 3, name: 'Finance' },
  // Add more industries as needed
];

onMounted(() => {
  searchQuery.value = search.value;
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style>
.custom-multiselect {
  --ms-tag-bg: #4f46e5;
  --ms-tag-color: #ffffff;
  --ms-option-bg-selected: #4f46e5;
}
</style>