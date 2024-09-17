<template>
  <AppLayout :head-title="'Jobs'" :show-search="true" name="Jobs">
    <!-- Changed this line to increase the width -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-6 trait-type">Explore Jobs</h1>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filter sidebar -->
        <div class="w-full lg:w-1/4">
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

            <button
              @click="resetFilters"
              class="mt-3 w-full px-4 py-2 bg-black text-gray-100 rounded-md hover:bg-gray-800 hover:text-white transition-colors duration-300"
            >
              Reset Filters
            </button>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-2/4">
          <div v-if="jobs.data.length > 0" class="space-y-3">
            <div v-for="job in jobs.data" :key="job.id" class="bg-white shadow-sm rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200">
                <Link :href="`/career/${job.slug}`">
              <div class="flex flex-row items-center pl-6 ">
                <img :src="job.image" :alt="job.name" class="w-24 h-24 object-cover rounded-full">
                <div class="p-6 flex-grow p-6">
                  <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ job.name }}</h2>
                  <div class="flex items-center mt-2 mb-2">
                                        <template v-for="star in 5" :key="star">
                                            <svg
                                                :class="star <= job.rating ? 'text-yellow-400' : 'text-gray-300'"
                                                class="h-6 w-6 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </template>
                                    </div>
                  <div class="flex items-center justify-between">
                    <p class="text-sm"><span class="pr-3 text-sm text-sky-800 font-medium blur-sm">hidden</span>MAD</p>
                    <button class="px-3 py-1.5 text-sm bg-emerald-800 font-black text-white rounded-md hover:bg-emerald-900 transition-colors duration-300">
                      Learn More
                    </button>
                  </div>
                </div>
              </div>
                </Link>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <p class="text-lg text-gray-600 mb-4">No jobs found matching your criteria.</p>
            <button
              @click="resetFilters"
              class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-700 transition-colors duration-300"
            >
              Reset Filters
            </button>
          </div>

          <div v-if="jobs.data.length > 0" class="mt-6">
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
import { router,Link } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  jobs: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.q || '');
const selectedEducationLevels = ref(props.filters.education ? props.filters.education.map(level => educationLevelOptions.find(option => option.value === level)) : []);
const selectedSort = ref(props.filters.sort || '');

const educationLevelOptions = [
  { value: "High School", label: "High School" },
  { value: "Associate", label: "Associate" },
  { value: "Bachelor's", label: "Bachelor's" },
  { value: "Doctorate", label: "Doctorate" },
  { value: "Master's", label: "Master's" },
  { value: "No Education", label: "No Education" }
];

const debouncedSearch = debounce(() => {
  applyFilters();
}, 300);

function formatSalary(salary) {
     return Math.floor(salary).toFixed(2);
   }

watch(searchQuery, (value) => {
  debouncedSearch();
});

const applyFilters = () => {
  const params = {};
  if (searchQuery.value) params.q = searchQuery.value;
  if (selectedEducationLevels.value.length > 0) params.education = selectedEducationLevels.value.map(level => level.value);
  if (selectedSort.value) params.sort = selectedSort.value;

  router.get('/jobs', params, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedEducationLevels.value = [];
  selectedSort.value = '';
  applyFilters();
};

onMounted(() => {
  searchQuery.value = props.filters.q || '';
});

watch([selectedEducationLevels, selectedSort], () => {
  applyFilters();
}, { deep: true });
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
.custom-multiselect {
  --ms-tag-bg: #4f46e5;
  --ms-tag-color: #ffffff;
  --ms-option-bg-selected: #4f46e5;
}

.blur-sm {
  filter: blur(4px);
}
.blur-sm:hover {
  filter: blur(0);
}
.trait-type{
    box-sizing: border-box;
    background-color: transparent;
    text-decoration: none;
    transition:
        color 0.2s ease-in-out, border-bottom 0.2s ease-in-out;
    border-bottom: 0px;
    color: rgb(36, 36, 36);
    font-weight: 300;
    font-family:
        aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
