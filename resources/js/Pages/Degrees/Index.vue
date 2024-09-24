<style src="public/css/vueMultiselect.css"></style>
<template>
  <AppLayout :head-title="'Explore Degrees'" head-sub-title="Find the academic paths that align with your unique talents and aspirations." :show-div="false" name="Degrees">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold text-gray-100 mb-6 trait-type">Explore Degrees</h1>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filter sidebar -->
        <div class="w-full lg:w-1/4">
          <div class="bg-transparent shadow-sm p-4">
            <h2 class="text-lg font-semibold text-gray-100 mb-3">Filters</h2>

            <div class="mb-3">
              <label for="search" class="block text-sm font-medium text-gray-100 mb-1">Search</label>
              <input
                id="search"
                v-model="searchQuery"
                class="w-full px-3 py-1.5 text-gray-400 text-sm border bg-gray-800 rounded-md shadow-sm focus:ring-[#4db554] focus:border-[#4db554]"
                type="text"
                placeholder="Search degrees"
                @input="debouncedSearch"
              />
            </div>

            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-100 mb-1">Degree Levels</label>
              <VueMultiselect
                v-model="selectedDegreeLevels"
                :options="degreeLevelOptions"
                :multiple="true"
                :close-on-select="false"
                placeholder="Select degree levels"
                label="label"
                track-by="value"
                class="multiselect"
              />
            </div>
            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-100 mb-1">Sort By</label>
              <select
                v-model="selectedSort"
                class="w-full px-3 py-1.5 text-gray-500 text-sm bg-gray-800 rounded-md shadow-sm focus:ring-[#4db554] focus:border-[#4db554]"
                @change="applyFilters"
              >
                <option value="" disabled selected>Select sorting option</option>

                <option value="salary_desc">Highest Salary</option>
                <option value="satisfaction_desc">Highest Satisfaction</option>
              </select>
            </div>

            <button
              @click="resetFilters"
              class="mt-3 w-full px-4 py-2 bg-[#4db554] font-black text-gray-100 rounded-md hover:bg-gray-800 hover:text-white transition-colors duration-300"
            >
              Reset Filters
            </button>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-4/4">
          <div v-if="degrees.data.length > 0" class="space-y-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <div v-for="degree in degrees.data" :key="degree.id" class="bg-white shadow-sm rounded-md overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200">
                  <Link :href="`/degree/${degree.slug}`" class="block">
                    <div class="flex flex-col h-full">
                      <div class="flex justify-center pt-4">
                        <img :src="degree.image" :alt="degree.name" class="w-20 h-20 object-cover rounded-full">
                      </div>
                      <div class="p-4 flex-grow">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2 text-center">{{ degree.name }}</h2>
                        <div class="flex justify-center mb-2">
                          <template v-for="star in 5" :key="star">
                            <svg
                              :class="star <= degree.rating ? 'text-yellow-400' : 'text-gray-300'"
                              class="h-5 w-5 flex-shrink-0"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 20 20"
                              fill="currentColor"
                            >
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </template>
                        </div>
                        <p class="text-sm text-center">
                          <span class="pr-3 text-sm text-sky-800 font-medium blur-sm">hidden</span>MAD
                        </p>
                      </div>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <p class="text-lg text-gray-600 mb-4">No degrees found matching your criteria</p>
            <button
              @click="resetFilters"
              class="px-4 py-2 bg-[#4db554] text-white rounded-md hover:bg-gray-700 transition-colors duration-300"
            >
              Reset Filters
            </button>
          </div>

          <div v-if="degrees.data.length > 0" class="mt-6">
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
import { router, Link } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  degrees: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.q || '');
const selectedDegreeLevels = ref(props.filters.level ? props.filters.level.map(level => degreeLevelOptions.find(option => option.value === level)) : []);
const selectedSort = ref(props.filters.sort || '');

const degreeLevelOptions = [
  { value: "Associate", label: "Associate" },
  { value: "Bachelor's", label: "Bachelor's" },
  { value: "Master's", label: "Master's" },
  { value: "Doctorate", label: "Doctorate" },
];

const debouncedSearch = debounce(() => {
  applyFilters();
}, 300);

watch(searchQuery, (value) => {
  debouncedSearch();
});

const applyFilters = () => {
  const params = {};
  if (searchQuery.value) params.q = searchQuery.value;
  if (selectedDegreeLevels.value.length > 0) params.level = selectedDegreeLevels.value.map(level => level.value);
  if (selectedSort.value) params.sort = selectedSort.value;

  router.get('/degrees', params, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedDegreeLevels.value = [];
  selectedSort.value = '';
  applyFilters();
};

onMounted(() => {
  searchQuery.value = props.filters.q || '';
});

watch([selectedDegreeLevels, selectedSort], () => {
  applyFilters();
}, { deep: true });
</script>

<style scoped>
.blur-sm {
  filter: blur(4px);
}

.trait-type {
  box-sizing: border-box;
  background-color: transparent;
  text-decoration: none;
  transition:
    color 0.2s ease-in-out,
    border-bottom 0.2s ease-in-out;
  border-bottom: 0px;
  font-weight: 300;
  font-family:
    aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
