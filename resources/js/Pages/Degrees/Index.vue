
<template>
  <AppLayout name="Degrees">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 mt-20">
      <div class="flex flex-col gap-6">
        <!-- Filter section -->
        <div class="w-full">
          <!-- Desktop Filters -->
          <div class="hidden sm:block bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
            <div class="flex gap-4">
              <div class="flex-1">
                <label for="search-desktop" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('degrees.search') }}</label>
                <input id="search-desktop" v-model="searchQuery" type="text" :placeholder="__('degrees.search_degrees')"
                  class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @input="debouncedSearch" />
              </div>

              <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('degrees.sort_by') }}</label>
                <select v-model="selectedSort"
                  class="w-full px-4 py-2.5 text-gray-900 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @change="applyFilters">
                  <option selected value="">{{ __('degrees.select_sorting_option') }}</option>
                  <option value="salary_desc">{{ __('degrees.highest_salary') }}</option>
                  <option value="satisfaction_desc">{{ __('degrees.highest_satisfaction') }}</option>
                </select>
              </div>

              <div class="flex items-end">
                <button @click="resetFilters"
                  class="px-6 py-2.5 bg-[#db492b] text-white font-semibold rounded-xl hover:bg-[#db492b]/90 focus:ring-2 focus:ring-[#db492b]/50 focus:ring-offset-2 transition duration-200">
                  {{ __('degrees.reset_filters') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Mobile Filters -->
          <div class="sm:hidden mt-4">
            <Sheet>
              <SheetTrigger asChild>
                <Button variant="outline" class="w-full flex justify-between items-center">
                  <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('degrees.filters') }}
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
                  <SheetTitle class="text-lg font-semibold">{{ __('degrees.filters') }}</SheetTitle>
                </SheetHeader>
                <div class="mt-6 space-y-6">
                  <!-- Search -->
                  <div>
                    <label for="search-mobile" class="block text-sm font-medium text-gray-700 mb-2">
                      {{ __('degrees.search') }}
                    </label>
                    <input
                      id="search-mobile"
                      v-model="searchQuery"
                      type="text"
                      :placeholder="__('degrees.search_degrees')"
                      class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                      @input="debouncedSearch"
                    />
                  </div>

                  <!-- Sort By -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ __('degrees.sort_by') }}
                    </label>
                    <select
                      v-model="selectedSort"
                      class="w-full px-4 py-2.5 text-gray-900 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                      @change="applyFilters"
                    >
                      <option selected value="">{{ __('degrees.select_sorting_option') }}</option>
                      <option value="salary_desc">{{ __('degrees.highest_salary') }}</option>
                      <option value="satisfaction_desc">{{ __('degrees.highest_satisfaction') }}</option>
                    </select>
                  </div>

                  <!-- Apply/Reset Buttons -->
                  <div class="flex gap-3 mt-8">
                    <Button 
                      variant="outline" 
                      class="flex-1 rounded-lg border-2 border-black/60 text-black"
                      @click="resetFilters"
                    >
                      {{ __('degrees.reset_filters') }}
                    </Button>
                    <Button 
                      class="flex-1 bg-black/90 rounded-lg text-white px-4 py-2"
                      @click="applyFilters"
                    >
                      {{ __('degrees.search') }}
                    </Button>
                  </div>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full">
          <div class="Listings__Results min-h-[800px]" role="feed" aria-busy="false" aria-live="assertive" aria-atomic="true">
            <div class="">
              <div v-if="degrees.data.length > 0" class="degrees-grid">
                <article v-for="(degree, index) in degrees.data" :key="degree.id" 
                  :aria-setsize="degrees.meta.total" :aria-posinset="index + 1"
                  class="degree-card">
                  <div class="degree-card__content">
                    <div class="degree-card__header">
                      <img :src="degree.image" :alt="`image for ${degree.name}`"
                        class="degree-card__image" />
                      <div class="degree-card__title-section">
                        <h3 class="degree-card__title">{{ degree.name }}</h3>
                      </div>
                    </div>
            
                    <div>
                      <p class="text-sm text-gray-600 line-clamp-2">{{ degree.description }}</p>
                    </div>

                    <Link :href="`/degree/${degree.slug}`" class="degree-card__link">
                      <span class="sr-only">{{ degree.name }}</span>
                    </Link>
                  </div>
                </article>
              </div>
              <div v-else class="text-center py-8">
                <p
                  class="text-xl font-bold text-gray-900 mb-4 tracking-tight font-['aktiv-grotesk','Helvetica Neue',Helvetica,Arial,sans-serif]">
                  {{ __("degrees.no_degrees_found") }}
                </p>
                <button @click="resetFilters"
                  class="px-6 py-2.5 bg-black text-white font-black rounded-xl hover:bg-gray-800 transition-all duration-300 shadow-sm hover:shadow-md text-sm uppercase">
                  {{ __("degrees.reset_filters") }}
                </button>
              </div>
            </div>
          </div>

          <WhenVisible v-if="hasMorePages" :params="{
            data: {
              page: page + 1,
              ...props.filters,
            },
            only: ['degrees'],
            preserveUrl: true,
          }">
            <div class="mt-6 text-center">
            </div>
          </WhenVisible>
          <BackToTop />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from "vue";
import { router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import debounce from "lodash/debounce";
import { usePage } from "@inertiajs/vue3";
import { WhenVisible } from "@inertiajs/vue3";
import BackToTop from "@/Components/helpers/BackToTop.vue";
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/Components/ui/sheet"
import { Button } from "@/Components/ui/button"

const props = defineProps({
  degrees: Object,
  filters: Object,
  language: String,
});

const searchQuery = ref(props.filters.q || "");
const selectedDegreeLevels = ref(
  props.filters.level
    ? props.filters.level.map((level) =>
      degreeLevelOptions.find((option) => option.value === level)
    )
    : []
);
const selectedSort = ref(props.filters.sort || "");

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

watch(
  () => props.language,
  (newLanguage, oldLanguage) => {
    if (newLanguage !== oldLanguage) {
      // Reset filters and reload data
      searchQuery.value = "";
      selectedDegreeLevels.value = [];
      selectedSort.value = "";
      page.value = 1;
      hasMorePages.value = true;

      router.visit(window.location.pathname, {
        preserveState: false,
        preserveScroll: false,
        replace: true,
      });
    }
  }
);

const degrees = ref(props.degrees);
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
    only: ["degrees"],
    onSuccess: (page) => {
      degrees.value.data = [
        ...degrees.value.data,
        ...page.props.degrees.data,
      ];
      hasMorePages.value =
        page.props.degrees.meta.current_page <
        page.props.degrees.meta.last_page;
      isLoading.value = false;
    },
  });
};

const applyFilters = () => {
  const params = {};
  if (searchQuery.value) params.q = searchQuery.value;
  if (selectedDegreeLevels.value.length > 0)
    params.level = selectedDegreeLevels.value.map((level) => level.value);
  if (selectedSort.value) params.sort = selectedSort.value;

  router.visit(window.location.pathname, {
    data: params,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ["degrees"],
    onSuccess: () => {
      degrees.value = usePage().props.degrees;
      page.value = 1;
      hasMorePages.value = true;
    },
  });
};

const resetFilters = () => {
  searchQuery.value = "";
  selectedDegreeLevels.value = [];
  selectedSort.value = "";
  router.visit(window.location.pathname, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ["degrees"],
  });
};

onMounted(() => {
  searchQuery.value = props.filters.q || "";
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});

watch(
  [selectedDegreeLevels, selectedSort],
  () => {
    applyFilters();
  },
  { deep: true }
);

const handleScroll = () => {
  const scrollPosition = window.innerHeight + window.pageYOffset;
  const triggerPosition = document.documentElement.offsetHeight - 400;

  if (scrollPosition >= triggerPosition) {
    loadMore();
  }

  // Show the back to top button when scrolled down
  showBackToTop.value = window.pageYOffset > 300;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const showBackToTop = ref(false);
const showFilters = ref(false);

const activeFiltersCount = computed(() => {
  let count = 0;
  if (searchQuery.value) count++;
  if (selectedSort.value) count++;
  return count;
});
</script>
<style scoped>
@import '/public/css/listing_page.css';
</style>

