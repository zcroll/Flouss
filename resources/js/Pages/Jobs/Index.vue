<style src="vue-multiselect/dist/vue-multiselect.css">

</style>


<template>
      <link rel="stylesheet" href="/css/listing_page.css">
  <AppLayout

    name="Jobs"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filter sidebar -->
        <div class="w-full lg:w-2/4">
          <div class=" border-[4px] rounded-3xl p-5 shadow-sm sticky-filter">
            <div class="mb-3">
              <label for="search" class="block text-sm font-medium text-gray-000 mb-1">{{ __('jobs.search') }}</label>
              <input
                id="search"
                v-model="searchQuery"
                type="text"
                :placeholder="__('jobs.search_jobs')"
                class="w-full px-3 py-1.5 text-gray-900 text-sm bg-gray-50 rounded-md shadow-sm focus:ring-[#fb6303] focus:border-[#fb6303]"
                @input="debouncedSearch"
              />
            </div>

            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-900 mb-1">{{ __('jobs.education_levels') }}</label>
              <VueMultiselect
                v-model="selectedEducationLevels"
                :options="educationLevelOptions"
                :multiple="true"
                :close-on-select="false"
                :placeholder="__('jobs.select_education_levels')"
                label="label"
                track-by="value"
                class="multiselect"
              />
            </div>

            <div class="mb-3">
              <label class="block text-sm font-medium text-gray-900 mb-1">{{ __('jobs.sort_by') }}</label>
              <select
                v-model="selectedSort"
                class="w-full px-3 py-1.5 text-gray-400 text-sm border bg-gray-50 rounded-md shadow-sm ring-[#fb6303] border-[#fb6303]"
                @change="applyFilters"
              >
                <option value="">{{ __('jobs.select_sorting_option') }}</option>
                <option value="salary_desc">{{ __('jobs.highest_salary') }}</option>
                <option value="satisfaction_desc">{{ __('jobs.highest_satisfaction') }}</option>
              </select>
            </div>

            <button
              @click="resetFilters"
              class="mt-3 w-full px-4 py-2 bg-[#db492b] font-black text-gray-100 rounded-md hover:bg-gray-800 hover:text-white transition-colors duration-300"
            >
              {{ __('jobs.reset_filters') }}
            </button>


          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-4/4">
          <div class="Listings__Results" role="feed" aria-busy="false" aria-live="assertive" aria-atomic="true">
            <div class="Listings__Results__section">
              <div v-if="jobs.data.length > 0">
                <article v-for="(job, index) in jobs.data" :key="job.id" :aria-setsize="jobs.meta.total" :aria-posinset="index + 1">
                  <div class="Box Listings__ResultItem Listings__ResultItem--has-thumbnail Listings__ResultItemCareer">
                    <img :src="job.image" :alt="`image for ${job.name}`" class="w-20 h-20 object-cover rounded-full">
                    <div class="Listings__ResultItem__main">
                      <h3>{{ job.name }}</h3>
                      <div class="stars">
                        <div class="Stars" :aria-label="`${job.rating} out of 5`">
                          <template v-for="star in 5" :key="star">
                            <svg
                              :class="star <= job.rating ? 'text-yellow-400' : 'text-gray-300'"
                              class="h-5 w-5 flex-shrink-0"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 20 20"
                              fill="currentColor"
                            >
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </template>
                        </div>
                      </div>
                    </div>
                    <div class="Listings__ResultItem__stat">
                      <h4>Salary:</h4>
                      <p>
                        <span class="pr-3 text-sm text-sky-800 font-medium blur-sm">hidden</span>MAD
                      </p>
                    </div>
                    <div class="Listings__ResultItem__icon">
                      <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path fill="currentColor" d="M85.14 475.8c-3.438-3.141-5.156-7.438-5.156-11.75c0-3.891 1.406-7.781 4.25-10.86l181.1-197.1L84.23 58.86c-6-6.5-5.625-16.64 .9062-22.61c6.5-6 16.59-5.594 22.59 .8906l192 208c5.688 6.156 5.688 15.56 0 21.72l-192 208C101.7 481.3 91.64 481.8 85.14 475.8z"></path>
                      </svg>
                    </div>
                    <Link :href="`/career/${job.slug}`" class="Listings__ResultItem__link-overlay">
                      <span class="sr-only">{{ job.name }}</span>
                    </Link>
                  </div>
                </article>
              </div>
              <div v-else class="text-center py-8">
                <p class="text-lg text-gray-600 mb-4">{{ __('jobs.no_jobs_found') }}</p>
                <button
                  @click="resetFilters"
                  class="px-4 py-2 bg-[#4db554] text-white rounded-md hover:bg-gray-700 transition-colors duration-300"
                >
                  {{ __('jobs.reset_filters') }}
                </button>




              </div>
            </div>
          </div>

          <WhenVisible
            :once="false"
            :params="{
              data: {
                page: page + 1,
                ...props.filters,
              },
              only: ['jobs'],
              preserveUrl: true,
            }"
          >
            <div class="mt-6 text-center">
              <div class="loading-dots">
                <span class="text-gray-500 mr-2">{{ __('jobs.loading') }}</span>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
              </div>
            </div>
          </WhenVisible>
          <BackToTop />
        </div>
      </div>
    </div>
  </AppLayout
    >
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';
// import BackToTop from '@/Components/BackToTop.vue';
import { WhenVisible } from '@inertiajs/vue3';

const props = defineProps({
  jobs: Object,
  filters: Object,
  language: String
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

watch(() => props.language, (newLanguage, oldLanguage) => {
  if (newLanguage !== oldLanguage) {
    // Reset filters and reload data
    searchQuery.value = "";
    selectedEducationLevels.value = [];
    selectedSort.value = "";
    page.value = 1;
    hasMorePages.value = true;

    router.visit(window.location.pathname, {
      preserveState: false,
      preserveScroll: false,
      replace: true
    });
  }
});

const jobs = ref(props.jobs);
const page = ref(1);
const isLoading = ref(false);
const hasMorePages = ref(true);

const loadMore = () => {
  if (isLoading.value || !hasMorePages.value) return;

  isLoading.value = true;
  page.value++;

  router.reload({
    data: {
      page: page.value,
      ...props.filters,
    },
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['jobs'],
    onSuccess: (page) => {
      jobs.value.data = [...jobs.value.data, ...page.props.jobs.data];
      hasMorePages.value = page.props.jobs.meta.current_page < page.props.jobs.meta.last_page;
      isLoading.value = false;
    },
  });
};

const applyFilters = () => {
  const params = {};
  if (searchQuery.value) params.q = searchQuery.value;
  if (selectedEducationLevels.value.length > 0) params.education = selectedEducationLevels.value.map(level => level.value);
  if (selectedSort.value) params.sort = selectedSort.value;

  router.visit(window.location.pathname, {
    data: params,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['jobs'],
    onSuccess: () => {
      jobs.value = usePage().props.jobs;
      page.value = 1;
      hasMorePages.value = true;
    },
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedEducationLevels.value = [];
  selectedSort.value = '';

  router.visit(window.location.pathname, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['jobs']
  });
};

onMounted(() => {
  searchQuery.value = props.filters.q || '';
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const handleScroll = () => {
  const scrollPosition = window.innerHeight + window.pageYOffset;
  const triggerPosition = document.documentElement.offsetHeight - 200;

  if (scrollPosition >= triggerPosition) {
    loadMore();
  }
};

watch([selectedEducationLevels, selectedSort], () => {
  applyFilters();
}, { deep: true });
</script>
