<template>

  <Head title="Degrees" />
  <div class="flex-1 flex flex-col space-y-8 container mx-auto px-4 max-w-7xl">
    <!-- Hero Section -->
    <Card :class="['relative p-8 overflow-hidden']">
      <CardContent>
        <div class="relative">
          <h1 :class="[
            'text-3xl md:text-4xl font-bold mb-4',
            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
          ]">
            {{ __('degrees.explore_degrees') }}
          </h1>
          <p :class="[
            'text-lg max-w-2xl',
            themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'
          ]">
            {{ __('degrees.discover_programs') }}
          </p>
        </div>
      </CardContent>
    </Card>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Filters Sidebar -->
      <div class="lg:col-span-1">
        <DegreeFilters :initial-filters="filters" @update:filters="handleFiltersUpdate" @reset="resetFilters" />
      </div>

      <!-- Degrees List -->
      <div class="lg:col-span-3">
        <!-- Results Count -->
        <div class="mb-6">
          <p :class="[`text-${themeStore.currentTheme.primary}-600`, 'text-sm']">
            {{ degrees.total }} {{ __('degrees.results_found') }}
          </p>
        </div>

        <!-- Degrees Grid -->
        <TransitionGroup
          name="degree-list" 
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 gap-4"
          v-if="degrees.data.length > 0"
          appear
        >
          <DegreeCard 
            v-for="(degree, index) in degrees.data" 
            :key="degree.id" 
            :degree="degree"
            :style="{ animationDelay: `${index * 50}ms` }"
            class="degree-card"
          />
        </TransitionGroup>

        <!-- Empty State -->
        <EmptyState v-if="degrees.data.length === 0" @reset="resetFilters" type="degrees" />

        <!-- Loading Indicator -->
        <div v-if="isLoading" class="flex justify-center py-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
        </div>

        <!-- Intersection Observer Target -->
        <div ref="infiniteScrollTrigger" class="h-4 w-full"></div>
      </div>
    </div>

    <BackToTop />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import BackToTop from '@/Components/helpers/BackToTop.vue';
import { Card, CardContent } from "@/Components/ui/card";
import { ArrowDown } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';
import DegreeCard from '@/Components/Degrees/DegreeCard.vue';
import DegreeFilters from '@/Components/Degrees/DegreeFilters.vue';
import EmptyState from '@/Components/Jobs/EmptyState.vue';
import { useActiveLink } from '@/composables/useActiveLink';

const props = defineProps({
  degrees: Object,
  filters: Object,
  language: String
});

defineOptions({
  layout: MainLayout,
});

// Initialize theme store and active link
const themeStore = useThemeStore();
const { isActive } = useActiveLink();

const degrees = ref(props.degrees);
const page = ref(1);
const isLoading = ref(false);
const infiniteScrollTrigger = ref(null);

const handleFiltersUpdate = (newFilters) => {
  router.visit(window.location.pathname, {
    data: newFilters,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['degrees'],
    onSuccess: () => {
      degrees.value = usePage().props.degrees;
      page.value = 1;
    },
  });
};

const resetFilters = () => {
  router.visit(window.location.pathname, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['degrees']
  });
};

const loadMoreDegrees = () => {
  if (isLoading.value) return;
  if (degrees.value.meta.current_page >= degrees.value.meta.last_page) return;

  isLoading.value = true;
  page.value++;

  router.visit(window.location.pathname, {
    data: {
      page: page.value,
      ...props.filters,
    },
    preserveState: true,
    preserveScroll: true,
    only: ['degrees'],
    onSuccess: (response) => {
      if (response?.props?.degrees?.data) {
        degrees.value = {
          ...response.props.degrees,
          data: [...degrees.value.data, ...response.props.degrees.data]
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
        loadMoreDegrees();
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
.degree-card {
  will-change: transform, opacity;
  backface-visibility: hidden;
  transform: translateZ(0);
}

.degree-list-enter-active,
.degree-list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.degree-list-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(20px);
}

.degree-list-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(-20px);
}

.degree-list-move {
  transition: transform 0.5s ease-out;
}

@media (prefers-reduced-motion: reduce) {
  .degree-list-enter-active,
  .degree-list-leave-active,
  .degree-list-move {
    transition: none;
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
