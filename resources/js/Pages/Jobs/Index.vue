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
    <TransitionGroup 
      name="job-list"
      tag="div"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
      v-if="jobs.data.length > 0"
      appear
    >
      <JobCard 
        v-for="(job, index) in jobs.data" 
        :key="job.id" 
        :job="job"
        :style="{ animationDelay: `${index * 100}ms` }"
        class="job-card"
      />
    </TransitionGroup>

    <!-- Empty State -->
    <EmptyState v-if="jobs.data.length === 0" @reset="resetFilters" />

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="flex justify-center py-4">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
    </div>

    <!-- Intersection Observer Target -->
    <div ref="infiniteScrollTrigger" class="h-4 w-full"></div>

    <BackToTop />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import { Card, CardContent } from "@/Components/ui/card";
import { useThemeStore } from '@/stores/theme/themeStore';
import JobCard from '@/Components/Jobs/JobCard.vue';
import JobFilters from '@/Components/Jobs/JobFilters.vue';
import EmptyState from '@/Components/Jobs/EmptyState.vue';
import BackToTop from '@/Components/helpers/BackToTop.vue';
import { ArrowDown } from 'lucide-vue-next';
import { useActiveLink } from '@/composables/useActiveLink';

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
const { isActive } = useActiveLink();
const jobs = ref(props.jobs);
const page = ref(1);
const isLoading = ref(false);
const infiniteScrollTrigger = ref(null);

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

const loadMoreJobs = () => {
  if (isLoading.value || jobs.value.meta.current_page >= jobs.value.meta.last_page) return;

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

// Intersection Observer setup
let observer;

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      if (entries[0].isIntersecting && !isLoading.value) {
        loadMoreJobs();
      }
    },
    {
      rootMargin: '100px',
      threshold: 0.1
    }
  );

  if (infiniteScrollTrigger.value) {
    observer.observe(infiniteScrollTrigger.value);
  }
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
});
</script>

<style scoped>
.job-card {
  will-change: transform, opacity;
  backface-visibility: hidden;
  transform: translateZ(0);
}

.job-list-enter-active,
.job-list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.job-list-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(20px);
}

.job-list-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(-20px);
}

.job-list-move {
  transition: transform 0.5s ease-out;
}

@media (prefers-reduced-motion: reduce) {
  .job-list-enter-active,
  .job-list-leave-active,
  .job-list-move {
    transition: none;
  }
}
</style>
