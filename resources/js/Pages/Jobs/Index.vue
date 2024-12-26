<template>

  <Head title="Jobs" />
  <div class="flex-1 flex flex-col space-y-8 container mx-auto px-4 max-w-7xl">
    <!-- Hero Section -->
    <Card :class="['relative p-8 overflow-hidden']">
      <!-- Background Pattern -->
      <CardContent>
        <div class="absolute inset-0 opacity-5">
          <div class="absolute inset-0"
            style="background-image: url('data:image/svg+xml,...'); background-size: 20px 20px;"></div>
        </div>

        <!-- Content -->
        <div class="relative">
          <h1 :class="[
            'text-3xl md:text-4xl font-bold mb-4',
            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
          ]">
            {{ __('jobs.explore_opportunities') }}
          </h1>
          <p :class="[
            'text-lg max-w-2xl',
            themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'
          ]">
            {{ __('jobs.discover_careers') }}
          </p>
        </div>
      </CardContent>
    </Card>

    <!-- Filters -->
    <JobFilters :initial-filters="filters" @update:filters="handleFiltersUpdate" @reset="resetFilters" />

    <!-- Jobs Grid -->
    <div v-if="jobs.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <JobCard v-for="job in jobs.data" :key="job.id" :job="job" />
    </div>

    <!-- Empty State -->
    <EmptyState v-if="jobs.data.length === 0" @reset="resetFilters" />

    <!-- Load More -->
    <div v-if="jobs.meta.current_page < jobs.meta.last_page" class="flex justify-center mt-8">
      <button @click="loadMore"
        class="group px-8 py-3 bg-white/60 backdrop-blur-xl text-gray-700 font-medium rounded-full hover:bg-white/80 transition-all duration-300 flex items-center gap-2"
        :disabled="isLoading">
        <span>{{ isLoading ? 'Loading...' : 'Load More' }}</span>
        <ArrowDown class="w-4 h-4 transform group-hover:translate-y-1 transition-transform"
          :class="{ 'animate-bounce': isLoading }" />
      </button>
    </div>

    <BackToTop />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import { Card, CardContent } from "@/Components/ui/card";
import { useThemeStore } from '@/stores/theme/themeStore';
import JobCard from '@/Components/Jobs/JobCard.vue';
import JobFilters from '@/Components/Jobs/JobFilters.vue';
import EmptyState from '@/Components/Jobs/EmptyState.vue';
import BackToTop from '@/Components/helpers/BackToTop.vue';
import { ArrowDown } from 'lucide-vue-next';

const props = defineProps({
  jobs: {
    type: Object,
    required: true,
    default: () => ({
      data: [],
      meta: {
        current_page: 1,
        last_page: 1
      }
    })
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

defineOptions({
  layout: MainLayout,
});

const themeStore = useThemeStore();
const jobs = ref(props.jobs);
const page = ref(1);
const isLoading = ref(false);

const handleFiltersUpdate = (newFilters) => {
  router.visit(window.location.pathname, {
    data: newFilters,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['jobs'],
    onSuccess: () => {
      jobs.value = props.jobs;
      page.value = 1;
    },
  });
};

const resetFilters = () => {
  router.visit(window.location.pathname, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['jobs']
  });
};

const loadMore = () => {
  if (isLoading.value) return;

  isLoading.value = true;
  page.value++;

  router.reload({
    data: {
      page: page.value,
      ...props.filters,
    },
    preserveState: true,
    preserveScroll: true,
    only: ['jobs'],
    onSuccess: (response) => {
      if (response?.props?.jobs?.data) {
        jobs.value = {
          ...response.props.jobs,
          data: [...jobs.value.data, ...response.props.jobs.data]
        };
      }
      isLoading.value = false;
    },
  });
};
</script>

<style scoped>
/* Animations */
.grid>div {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
