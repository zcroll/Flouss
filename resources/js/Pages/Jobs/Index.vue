<template>
  <Head title="Jobs" />
  <div class="flex-1 flex flex-col space-y-8">
    <!-- Hero Section -->
    <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl p-8 overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,...'); background-size: 20px 20px;"></div>
      </div>
      
      <!-- Content -->
      <div class="relative">
        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-4">
          Explore Career Opportunities
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl">
          Discover and explore detailed information about various career paths that match your interests and skills.
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
              {{ __('jobs.search') }}
            </label>
            <div class="relative group">
              <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5 transition-colors group-hover:text-yellow-400" />
              <input 
                id="search-desktop" 
                v-model="searchQuery" 
                type="text" 
                :placeholder="__('jobs.search_jobs')"
                class="w-full pl-12 pr-6 py-3 text-gray-900 placeholder-gray-500 bg-white/80 backdrop-blur-md rounded-full border border-white/20 focus:ring-2 focus:ring-yellow-400/20 focus:border-yellow-400 transition duration-200" 
                @input="debouncedSearch" 
              />
            </div>
          </div>

          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              {{ __('jobs.education_levels') }}
            </label>
            <CustomMultiSelect 
              v-model="selectedEducationLevels" 
              :options="educationLevelOptions"
              :placeholder="__('jobs.select_education_levels')"
              class="backdrop-blur-md"
            />
          </div>

          <div class="flex items-end">
            <button 
              @click="resetFilters"
              class="group px-6 py-3 bg-white/80 border border-white/20 backdrop-blur-xl text-gray-700 font-medium rounded-full hover:bg-yellow-400 hover:text-white hover:border-transparent focus:ring-2 focus:ring-yellow-400/50 focus:ring-offset-2 transition duration-200"
            >
              <span class="flex items-center gap-2">
                <RefreshCw class="w-4 h-4 transition-transform group-hover:rotate-180" />
                {{ __('jobs.reset_filters') }}
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Filters -->
      <div class="sm:hidden">
        <Sheet>
          <SheetTrigger asChild>
            <Button variant="outline" 
                    class="w-full flex justify-between items-center bg-white/80 backdrop-blur-xl rounded-full border border-white/20">
              <span class="flex items-center gap-2">
                <Search class="w-5 h-5" />
                {{ __('jobs.filters') }}
              </span>
              <div class="flex items-center gap-1">
                <span v-if="activeFiltersCount" 
                      class="bg-yellow-400 text-white text-xs font-medium px-2 py-0.5 rounded-full">
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
                  {{ __('jobs.search') }}
                </label>
                <div class="relative">
                  <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                  <input 
                    v-model="searchQuery" 
                    type="text" 
                    :placeholder="__('jobs.search_jobs')"
                    class="w-full pl-12 pr-6 py-3 bg-white/80 rounded-full border border-gray-200" 
                  />
                </div>
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

              <!-- Reset Button -->
              <button 
                @click="resetFilters"
                class="w-full px-6 py-3 bg-yellow-400 text-white font-medium rounded-full hover:bg-yellow-500 transition-colors duration-200"
              >
                {{ __('jobs.reset_filters') }}
              </button>
            </div>
          </SheetContent>
        </Sheet>
      </div>
    </div>

    <!-- Jobs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="(job, index) in jobs.data" 
           :key="job.id"
           class="group relative bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300"
           :style="{ animationDelay: `${index * 100}ms` }"
      >
        <!-- Header with Image and Title -->
        <div class="flex items-center gap-4 mb-6">
          <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white/50 p-3 backdrop-blur-sm border border-white/20 group-hover:border-yellow-400/20 transition-colors duration-300">
            <img :src="job.image" 
                 :alt="`image for ${job.name}`"
                 class="w-full h-full object-contain filter contrast-125 transition-transform duration-300 group-hover:scale-110" 
            />
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">{{ job.name }}</h3>
            <p class="text-sm text-gray-500">{{ job.category }}</p>
          </div>
        </div>
        
        <!-- Job Details -->
        <div class="space-y-4 mb-6">
          <!-- Description -->
          <div>
            <p class="text-sm text-gray-600 line-clamp-2">{{ job.description }}</p>
          </div>

          <!-- Requirements -->
          <div class="space-y-2">
            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Requirements</h4>
            <div class="flex flex-wrap gap-2">
              <span v-for="(level, idx) in job.education_levels" 
                    :key="idx"
                    class="px-3 py-1 bg-yellow-400/10 text-yellow-600 text-xs rounded-full border border-yellow-400/20">
                {{ level }}
              </span>
            </div>
          </div>

          <!-- Key Details -->
          <div class="grid grid-cols-2 gap-4">
            <template v-for="(value, key) in jobDetails(job)" :key="key">
              <div v-if="value" class="space-y-1">
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ formatLabel(key) }}</h4>
                <p class="text-sm text-gray-700">{{ value }}</p>
              </div>
            </template>
          </div>

          <!-- Skills -->
          <div v-if="job.skills?.length" class="space-y-2">
            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Key Skills</h4>
            <div class="flex flex-wrap gap-2">
              <span v-for="skill in job.skills" 
                    :key="skill"
                    class="px-3 py-1 bg-white/50 text-gray-600 text-xs rounded-full border border-white/20 group-hover:border-yellow-400/20 transition-colors duration-300">
                {{ skill }}
              </span>
            </div>
          </div>
        </div>
        
        <!-- Action Button -->
        <Link :href="`/career/${job.slug}`" 
              class="inline-flex items-center justify-between w-full px-6 py-3 bg-white/60 backdrop-blur-sm text-gray-700 font-medium rounded-full border border-white/20 group-hover:bg-yellow-400 group-hover:text-white group-hover:border-transparent transition-all duration-300">
          <span>Learn More</span>
          <ArrowRight class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" />
        </Link>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="jobs.data.length === 0" 
         class="flex flex-col items-center justify-center py-12 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20">
      <div class="text-center">
        <div class="mb-4">
          <SearchX class="w-12 h-12 text-gray-400 mx-auto" />
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">
          {{ __("jobs.no_jobs_found") }}
        </h3>
        <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
        <button 
          @click="resetFilters"
          class="px-6 py-3 bg-yellow-400 text-white font-medium rounded-full hover:bg-yellow-500 transition-colors duration-300 flex items-center gap-2 mx-auto"
        >
          <RefreshCw class="w-4 h-4" />
          {{ __("jobs.reset_filters") }}
        </button>
      </div>
    </div>

    <!-- Load More -->
    <WhenVisible v-if="hasMorePages">
      <div class="flex justify-center mt-8">
        <button 
          @click="loadMore"
          class="group px-8 py-3 bg-white/60 backdrop-blur-xl text-gray-700 font-medium rounded-full hover:bg-white/80 transition-all duration-300 flex items-center gap-2"
          :disabled="isLoading"
        >
          <span>Load More</span>
          <ArrowDown class="w-4 h-4 transform group-hover:translate-y-1 transition-transform" />
        </button>
      </div>
    </WhenVisible>

    <BackToTop />
  </div>
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
import MainLayout from "@/Layouts/MainLayout.vue";
import { Search, ArrowRight, ChevronDown, RefreshCw, ArrowDown, SearchX } from 'lucide-vue-next'

const props = defineProps({
  jobs: Object,
  filters: Object,
  language: String
});
defineOptions({
    layout: MainLayout,
})

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

const jobDetails = (job) => ({
  salary_range: job.salary_range,
  experience: job.experience,
  location: job.location,
  employment_type: job.employment_type
});

const formatLabel = (key) => {
  return key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
</script>

<style scoped>
/* Animations */
.grid > div {
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

