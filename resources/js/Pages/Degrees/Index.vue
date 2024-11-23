<template>
  <AppLayout name="Degrees">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col gap-6">
        <!-- Filter sidebar -->
        <div class="w-full">
          <div class="bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
            <div class="flex gap-4">
              <div class="flex-1">
                <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">{{ __("degrees.search") }}</label>
                <input id="search" v-model="searchQuery" type="text" :placeholder="__('degrees.search_degrees')"
                  class="w-full px-4 py-2.5 text-gray-900 placeholder-gray-500 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @input="debouncedSearch" />
              </div>

              <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __("degrees.sort_by") }}</label>
                <select v-model="selectedSort"
                  class="w-full px-4 py-2.5 text-gray-900 bg-white border border-[#db492b]/20 rounded-xl shadow-sm focus:ring-2 focus:ring-[#db492b]/20 focus:border-[#db492b] transition duration-200"
                  @change="applyFilters">
                  <option selected value="">{{ __("degrees.select_sorting_option") }}</option>
                  <option value="salary_desc">{{ __("degrees.highest_salary") }}</option>
                  <option value="satisfaction_desc">{{ __("degrees.highest_satisfaction") }}</option>
                </select>
              </div>

              <div class="flex items-end">
                <button @click="resetFilters"
                  class="px-6 py-2.5 bg-[#db492b] text-white font-semibold rounded-xl hover:bg-[#db492b]/90 focus:ring-2 focus:ring-[#db492b]/50 focus:ring-offset-2 transition duration-200">
                  {{ __("degrees.reset_filters") }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <div class="w-full">
          <div class="Listings__Results" role="feed" aria-busy="false" aria-live="assertive" aria-atomic="true">
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

          <WhenVisible v-if="hasMorePages" :once="false" :params="{
            data: {
              page: page + 1,
              ...props.filters,
            },
            only: ['degrees'],
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
  </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from "vue";
import { router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import debounce from "lodash/debounce";
import { usePage } from "@inertiajs/vue3";
import { WhenVisible } from "@inertiajs/vue3";
import BackToTop from "@/Components/helpers/BackToTop.vue";

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
  const triggerPosition = document.documentElement.offsetHeight - 200;

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
</script>
<style scoped>
@import '/public/css/listing_page.css';
</style>
