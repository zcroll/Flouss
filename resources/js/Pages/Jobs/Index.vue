<template>
  <AppLayout
    name="Jobs"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col gap-6">
        <!-- Filter sidebar -->
        <div class="w-full">
          <div class="bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
            <div class="flex gap-4">
              <div class="flex-1">
                <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('jobs.search') }}</label>
                <input
                  id="search"
                  v-model="searchQuery"
                  type="text"
                  :placeholder="__('jobs.search_jobs')"
                  class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @input="debouncedSearch"
                />
              </div>

              <div class="flex-1 ">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('jobs.education_levels') }}</label>
                <CustomMultiSelect
                  v-model="selectedEducationLevels"
                  :options="educationLevelOptions"
                  :placeholder="__('jobs.select_education_levels')"
  
                />
              </div>
              <div class="flex items-end mb-1">
                <button
                  @click="resetFilters"
                  class="px-6 py-2.5 bg-[#db492b] text-white font-semibold rounded-xl hover:bg-[#db492b]/90 focus:ring-2 focus:ring-[#db492b]/50 focus:ring-offset-2 transition duration-200">
                  {{ __('jobs.reset_filters') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-4/4">
          <div class="Listings__Results" role="feed" aria-busy="false" aria-live="assertive" aria-atomic="true">
            <div class="">
              <div v-if="jobs.data.length > 0" class="degrees-grid">
                <article v-for="(job, index) in jobs.data" :key="job.id" 
                  :aria-setsize="jobs.meta.total" :aria-posinset="index + 1"
                  class="degree-card">
                  <div class="degree-card__content">
                    <div class="degree-card__header">
                      <img :src="job.image" :alt="`image for ${job.name}`"
                        class="degree-card__image" />
                      <div class="degree-card__title-section">
                        <h3 class="degree-card__title">{{ job.name }}</h3>
                      </div>
                    </div>
            
                    <div>
                      <p class="text-sm text-gray-600 line-clamp-2">{{ job.description }}</p>
                    </div>

                    <Link :href="`/career/${job.slug}`" class="degree-card__link">
                      <span class="sr-only">{{ job.name }}</span>
                    </Link>
                  </div>
                </article>
              </div>
              <div v-else class="text-center py-8">
                <p class="text-xl font-bold text-gray-900 mb-4 tracking-tight font-['aktiv-grotesk','Helvetica Neue',Helvetica,Arial,sans-serif]">
                  {{ __("jobs.no_jobs_found") }}
                </p>
                <button @click="resetFilters"
                  class="px-6 py-2.5 bg-black text-white font-black rounded-xl hover:bg-gray-800 transition-all duration-300 shadow-sm hover:shadow-md text-sm uppercase">
                  {{ __("jobs.reset_filters") }}
                </button>
              </div>
            </div>
          </div>

          <WhenVisible v-if="hasMorePages" :once="false" :params="{
            data: {
              page: page + 1,
              ...props.filters,
            },
            only: ['jobs'],
            preserveUrl: true,
          }">
            <div class="mt-6 text-center">
              <div class="loading-dots">
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
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import debounce from 'lodash/debounce';
// import BackToTop from '@/Components/BackToTop.vue';
import { WhenVisible } from '@inertiajs/vue3';

const props = defineProps({
  jobs: Object,
  filters: Object,
  language: String
});

const searchQuery = ref(props.filters.q || '');
const selectedEducationLevels = ref(props.filters.education ? props.filters.education.map(level => ({
  value: level,
  label: level
})) : []);
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
<style scoped>
@import '/public/css/listing_page.css';
</style>
