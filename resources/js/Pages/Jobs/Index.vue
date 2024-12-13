
<template>
  <AppLayout
    name="Jobs"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col gap-6">
        <!-- Filter section -->
        <div class="w-full">
          <!-- Desktop Filters -->
          <div class="hidden sm:block bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
            <div class="flex gap-4">
              <div class="flex-1">
                <label for="search-desktop" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('jobs.search') }}</label>
                <input id="search-desktop" v-model="searchQuery" type="text" :placeholder="__('jobs.search_jobs')"
                  class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @input="debouncedSearch" />
              </div>

              <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('jobs.education_levels') }}</label>
                <CustomMultiSelect v-model="selectedEducationLevels" :options="educationLevelOptions"
                  :placeholder="__('jobs.select_education_levels')" />
              </div>

              <div class="flex items-end mb-1">
                <button @click="resetFilters"
                  class="px-6 py-2.5 bg-[#db492b] text-white font-semibold rounded-xl hover:bg-[#db492b]/90 focus:ring-2 focus:ring-[#db492b]/50 focus:ring-offset-2 transition duration-200">
                  {{ __('jobs.reset_filters') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Mobile Filters -->
          <div class="sm:hidden mt-4">
            <Sheet>
              <SheetTrigger asChild>
                <Button variant="outline" class="w-full flex justify-between items-center ">
                  <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('jobs.filters') }}
                  </span>
                  <div class="flex items-center gap-1">
                    <span v-if="activeFiltersCount" class="bg-[#db492b] text-white text-xs font-medium px-2 py-0.5 rounded-full">
                      {{ activeFiltersCount }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </Button>
              </SheetTrigger>
              <SheetContent side="top" class="h-[90vh] rounded-b-xl">
                <SheetHeader>
                  <SheetTitle class="text-lg font-semibold">{{ __('jobs.filters') }}</SheetTitle>
                </SheetHeader>
                <div class="mt-6 space-y-6">
                  <!-- Search -->
                  <div>
                    <label for="search-mobile" class="block text-sm font-medium text-gray-700 mb-2">
                      {{ __('jobs.search') }}
                    </label>
                    <input
                      id="search-mobile"
                      v-model="searchQuery"
                      type="text"
                      :placeholder="__('jobs.search_jobs')"
                      class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                      @input="debouncedSearch"
                    />
                  </div>

                  <!-- Education Levels -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ __('jobs.education_levels') }}
                    </label>
                    <CustomMultiSelect
                      v-model="selectedEducationLevels"
                      :options="educationLevelOptions"
                      :placeholder="__('jobs.select_education_levels')"
                    />
                  </div>

                  <!-- Apply/Reset Buttons -->
                  <div class="flex gap-3 mt-8">
                    <Button 
                      variant="outline" 
                      class="flex-1 rounded-lg border-2 border-black/60 text-black"
                      @click="resetFilters"
                    >
                      {{ __('jobs.reset_filters') }}
                    </Button>
                    <Button 
                      class="flex-1 bg-black/90 rounded-lg text-white px-4 py-2"
                      @click="applyFilters"
                    >
                      {{ __('jobs.search') }}
                    </Button>
                  </div>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full lg:w-4/4">
          <div class="Listings__Results min-h-[800px]" role="feed" aria-busy="false" aria-live="assertive" aria-atomic="true">
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

          <WhenVisible v-if="hasMorePages" :params="{
            data: {
              page: page + 1,
              ...props.filters,
            },
            only: ['jobs'],
            preserveUrl: true,
          }">
            <template #default>
              <div></div>
            </template>
          </WhenVisible>
          <BackToTop />
        </div>
      </div>
    </div>
  </AppLayout
    >
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import debounce from 'lodash/debounce';
import { WhenVisible } from '@inertiajs/vue3';
import BackToTop from '@/Components/helpers/BackToTop.vue';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/Components/ui/sheet"
import { Button } from "@/Components/ui/button"

const props = defineProps({
  jobs: Object,
  filters: Object,
  language: String
});

// Add showFilters ref
const showFilters = ref(false);
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

const jobs = ref(props.jobs);
const page = ref(1);
const isLoading = ref(false);
const hasMorePages = ref(true);

// Watch for language changes
watch(() => props.language, (newLanguage, oldLanguage) => {
  if (newLanguage !== oldLanguage) {
    searchQuery.value = "";
    selectedEducationLevels.value = [];
    selectedSort.value = "";
    page.value = 1;
    hasMorePages.value = true;
    showFilters.value = false; // Reset filter visibility

    router.visit(window.location.pathname, {
      preserveState: false,
      preserveScroll: false,
      replace: true
    });
  }
});

// Watch for search query changes
watch(searchQuery, (value) => {
  debouncedSearch();
});

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
      // Optionally close filters on mobile after applying
      if (window.innerWidth < 640) {
        showFilters.value = false;
      }
    },
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedEducationLevels.value = [];
  selectedSort.value = '';
  showFilters.value = false; // Close filters panel after reset

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
  const triggerPosition = document.documentElement.offsetHeight - 400;

  if (scrollPosition >= triggerPosition) {
    loadMore();
  }
};

// Watch for education levels changes
watch([selectedEducationLevels], () => {
  applyFilters();
}, { deep: true });

const activeFiltersCount = computed(() => {
  let count = 0;
  if (searchQuery.value) count++;
  if (selectedEducationLevels.value.length) count++;
  return count;
});
</script>
<style scoped>
@import '/public/css/listing_page.css';
</style>

