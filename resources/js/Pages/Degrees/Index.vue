<template>
  <Head title="Degrees" />
  <div class="flex-1 flex flex-col space-y-8">
    <!-- Hero Section -->
    <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl p-8 overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0"
          style="background-image: url('data:image/svg+xml,...'); background-size: 20px 20px;"></div>
      </div>

      <!-- Content -->
      <div class="relative">
        <h1 class="text-3xl md:text-4xl font-bold mb-4" :class="`text-${themeStore.currentTheme.primary}-900`">
          {{ __('degrees.explore_degrees') }}
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl">
          {{ __('degrees.discover_education') }}
        </p>
      </div>
    </div>

    <!-- Filter section -->
    <div class="sticky top-4 z-30">
      <!-- Desktop Filters -->
      <div class="hidden sm:block bg-white/80 backdrop-blur-xl rounded-3xl p-6 shadow-sm border border-white/20">
        <div class="flex gap-6">
          <div class="flex-1">
            <label for="search-desktop" class="block text-sm font-medium text-gray-700 mb-2">
              {{ __('degrees.search') }}
            </label>
            <div class="relative group">
              <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5 transition-colors"
                :class="`hover:text-${themeStore.currentTheme.primary}-600`" />
              <input id="search-desktop" v-model="searchQuery" type="text" :placeholder="__('degrees.search_degrees')"
                class="w-full pl-12 pr-6 py-3 text-gray-900 placeholder-gray-500 bg-white/80 backdrop-blur-md rounded-full border border-white/20 transition duration-200"
                :class="`focus:ring-${themeStore.currentTheme.primary}-500 focus:border-${themeStore.currentTheme.primary}-500`"
                @input="debouncedSearch" />
            </div>
          </div>

          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              {{ __('degrees.degree_levels') }}
            </label>
            <CustomMultiSelect v-model="selectedDegreeTypes" :options="degreeTypeOptions"
              :placeholder="__('degrees.select_degree_types')" class="backdrop-blur-md" />
          </div>

          <div class="flex items-end">
            <button @click="resetFilters"
              class="group px-6 py-3 bg-white/80 border border-white/20 backdrop-blur-xl text-gray-700 font-medium rounded-full focus:ring-2 focus:ring-offset-2 transition duration-200"
              :class="`hover:text-${themeStore.currentTheme.primary}-600 focus:ring-${themeStore.currentTheme.primary}-500`">
              <span class="flex items-center gap-2">
                <RefreshCw class="w-4 h-4 transition-transform group-hover:rotate-180" />
                {{ __('degrees.reset_filters') }}
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Filters -->
      <Sheet>
        <SheetTrigger asChild>
          <Button variant="outline"
            class="w-full sm:hidden flex justify-between items-center bg-white/80 backdrop-blur-xl rounded-full border border-white/20">
            <span class="flex items-center gap-2">
              <Search class="w-5 h-5" />
              {{ __('degrees.filters') }}
            </span>
            <div class="flex items-center gap-1">
              <span v-if="activeFiltersCount" class="text-white text-xs font-medium px-2 py-0.5 rounded-full"
                :class="`bg-${themeStore.currentTheme.primary}-500`">
                {{ activeFiltersCount }}
              </span>
              <ChevronDown class="w-4 h-4" />
            </div>
          </Button>
        </SheetTrigger>
        <SheetContent side="bottom" class="rounded-t-3xl bg-white/95 backdrop-blur-xl">
          <!-- Mobile filter content -->
          <div class="space-y-6 p-4">
            <!-- Search -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('degrees.search') }}
              </label>
              <div class="relative">
                <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                <input v-model="searchQuery" type="text" :placeholder="__('degrees.search_degrees')"
                  class="w-full pl-12 pr-6 py-3 bg-white/80 rounded-full border border-gray-200"
                  :class="`focus:ring-${themeStore.currentTheme.primary}-500 focus:border-${themeStore.currentTheme.primary}-500`" />
              </div>
            </div>

            <!-- Degree Types -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('degrees.degree_levels') }}
              </label>
              <CustomMultiSelect v-model="selectedDegreeTypes" :options="degreeTypeOptions"
                :placeholder="__('degrees.select_degree_types')" />
            </div>

            <!-- Reset Button -->
            <button @click="resetFilters"
              class="w-full px-6 py-3 font-medium rounded-full transition-colors duration-200"
              :class="`bg-${themeStore.currentTheme.primary}-500 text-white hover:bg-${themeStore.currentTheme.primary}-600`">
              {{ __('degrees.reset_filters') }}
            </button>
          </div>
        </SheetContent>
      </Sheet>
    </div>

    <!-- Degrees Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="(degree, index) in degrees.data" :key="degree.id"
        class="group relative bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300"
        :style="{ animationDelay: `${index * 100}ms` }">
        <!-- Header with Image and Title -->
        <div class="flex items-center gap-4 mb-6">
          <div
            class="w-16 h-16 rounded-2xl overflow-hidden bg-white/50 p-3 backdrop-blur-sm border border-white/20 transition-colors duration-300"
            :class="`hover:border-${themeStore.currentTheme.primary}-500`">
            <img :src="degree.image" :alt="`image for ${degree.name}`"
              class="w-full h-full object-contain filter contrast-125 transition-transform duration-300 group-hover:scale-110" />
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">{{ degree.name }}</h3>
            <p class="text-sm" :class="`text-${themeStore.currentTheme.primary}-600`">{{ degree.type }}</p>
          </div>
        </div>

        <!-- Degree Details -->
        <div class="space-y-4 mb-6">
          <!-- Description -->
          <div>
            <p class="text-sm text-gray-600 line-clamp-2">{{ degree.description }}</p>
          </div>

          <!-- Key Details -->
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</h4>
              <p class="text-sm text-gray-700">{{ degree.duration }} Years</p>
            </div>
            <div class="space-y-1">
              <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Level</h4>
              <p class="text-sm text-gray-700">{{ degree.level }}</p>
            </div>
          </div>

          <!-- Skills -->
          <div v-if="degree.skills?.length" class="space-y-2">
            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Key Skills</h4>
            <div class="flex flex-wrap gap-2">
              <span v-for="skill in degree.skills" :key="skill"
                class="px-3 py-1 bg-white/50 text-xs rounded-full border border-white/20 transition-colors duration-300"
                :class="`hover:border-${themeStore.currentTheme.primary}-500`">
                {{ skill }}
              </span>
            </div>
          </div>
        </div>

        <!-- Action Button -->
        <Link :href="`/degree/${degree.slug}`"
          class="inline-flex items-center justify-between w-full px-6 py-3 bg-white/60 backdrop-blur-sm font-medium rounded-full border border-white/20 transition-all duration-300"
          :class="`hover:bg-${themeStore.currentTheme.primary}-500 hover:text-white`">
        <span>Learn More</span>
        <ArrowRight class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" />
        </Link>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="degrees.data.length === 0"
      class="flex flex-col items-center justify-center py-12 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20">
      <div class="text-center">
        <div class="mb-4">
          <SearchX class="w-12 h-12 text-gray-400 mx-auto" />
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">
          {{ __("degrees.no_degrees_found") }}
        </h3>
        <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
        <button @click="resetFilters"
          class="px-6 py-3 font-medium rounded-full transition-colors duration-300 flex items-center gap-2 mx-auto"
          :class="`bg-${themeStore.currentTheme.primary}-500 text-white hover:bg-${themeStore.currentTheme.primary}-600`">
          <RefreshCw class="w-4 h-4" />
          {{ __("degrees.reset_filters") }}
        </button>
      </div>
    </div>

    <!-- Load More -->
    <WhenVisible v-if="hasMorePages">
      <div class="flex justify-center mt-8">
        <button @click="loadMore"
          class="group px-8 py-3 bg-white/60 backdrop-blur-xl text-gray-700 font-medium rounded-full hover:bg-white/80 transition-all duration-300 flex items-center gap-2"
          :disabled="isLoading">
          <span>Load More</span>
          <ArrowDown class="w-4 h-4 transform group-hover:translate-y-1 transition-transform" />
        </button>
      </div>
    </WhenVisible>

    <BackToTop />
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from "vue";
import { router, Link, usePage } from "@inertiajs/vue3";
import CustomMultiSelect from "@/Components/helpers/CustomMultiSelect.vue";
import debounce from "lodash/debounce";
import { WhenVisible } from "@inertiajs/vue3";
import BackToTop from "@/Components/helpers/BackToTop.vue";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet"
import { Button } from "@/Components/ui/button"
import MainLayout from "@/Layouts/MainLayout.vue";
import { Search, ArrowRight, ChevronDown, RefreshCw, ArrowDown, SearchX } from 'lucide-vue-next';
import { useThemeStore } from "@/stores/theme/themeStore";

defineOptions({
  layout: MainLayout,
})

const props = defineProps({
  degrees: Object,
  filters: Object,
  language: String,
});

// Initialize theme store
const themeStore = useThemeStore();

// State
const searchQuery = ref(props.filters.q || "");
const selectedDegreeTypes = ref(props.filters.type ? props.filters.type.map(type => ({
  value: type,
  label: type
})) : []);

const degreeTypeOptions = [
  { value: "Associate", label: "Associate" },
  { value: "Bachelor's", label: "Bachelor's" },
  { value: "Master's", label: "Master's" },
  { value: "Doctorate", label: "Doctorate" }
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
      selectedDegreeTypes.value = [];
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
const pageNum = ref(1);
const isLoading = ref(false);
const hasMorePages = ref(true);

const loadMore = () => {
  if (isLoading.value || !hasMorePages.value) return;

  isLoading.value = true;
  pageNum.value++;

  router.reload({
    data: {
      page: pageNum.value,
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
  if (selectedDegreeTypes.value.length > 0)
    params.type = selectedDegreeTypes.value.map((type) => type.value);

  router.visit(window.location.pathname, {
    data: params,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ["degrees"],
    onSuccess: () => {
      degrees.value = usePage().props.degrees;
      pageNum.value = 1;
      hasMorePages.value = true;
    },
  });
};

const resetFilters = () => {
  searchQuery.value = "";
  selectedDegreeTypes.value = [];
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
  [selectedDegreeTypes],
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
  if (selectedDegreeTypes.value.length > 0) count++;
  return count;
});
</script>

<style scoped>
/* Animations */
.grid>div {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Scrollbar styling */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(250, 204, 21, 0.5) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(250, 204, 21, 0.5);
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(250, 204, 21, 0.7);
}
</style>
