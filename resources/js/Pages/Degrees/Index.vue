<template>
  <AppLayout :head-title="'Degrees'" :show-search="true" name="Degrees">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Explore Degrees</h1>
      
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
                placeholder="Search degrees"
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

            <button
              @click="resetFilters"
              class="mt-3 w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-300"
            >
              Reset Filters
            </button>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-3/4">
          <div v-if="degrees.data.length > 0" class="space-y-3">
            <div v-for="degree in degrees.data" :key="degree.id" class="bg-white shadow-sm rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200">
              <div class="flex flex-row items-center">
                <img :src="degree.image" :alt="degree.name" class="w-24 h-24 object-cover">
                <div class="p-3 flex-grow">
                  <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ degree.name }}</h2>
                  <p class="text-sm text-gray-600 mb-2">{{ degree.slug }}</p>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-600 font-medium">Salary: ${{ degree.salary }}</span>
                    <button class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-300">
                      Learn More
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="bg-white shadow-sm rounded-lg p-6 text-center">
            <p class="text-lg text-gray-600">No degrees found matching your criteria.</p>
            <button @click="resetFilters" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-300">
              Reset Filters
            </button>
          </div>

          <div class="mt-6">
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
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  degrees: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.q || '');
const selectedEducationLevels = ref(props.filters.education ? props.filters.education.map(level => educationLevelOptions.find(option => option.value == level)) : []);
const selectedSort = ref(props.filters.sort || '');
const selectedIndustries = ref(props.filters.industries || []);

const debouncedSearch = debounce(() => {
  applyFilters();
}, 300);

watch(searchQuery, (value) => {
  debouncedSearch();
});

const applyFilters = () => {
  const params = {};
  if (searchQuery.value) params.q = searchQuery.value;
  if (selectedEducationLevels.value.length > 0) params.education = selectedEducationLevels.value.map(level => level.value);
  if (selectedSort.value) params.sort = selectedSort.value;
  if (selectedIndustries.value.length > 0) params.industries = selectedIndustries.value;

  router.get('/degrees', params, { 
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedEducationLevels.value = [];
  selectedSort.value = '';
  selectedIndustries.value = [];
  applyFilters();
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

watch([selectedEducationLevels, selectedSort, selectedIndustries], () => {
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