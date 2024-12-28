<template>

  <Head title="Degrees" />
  <div class="flex-1 flex flex-col space-y-8 container mx-auto px-4 max-w-7xl">
    <!-- Hero Section -->
    <Card :class="[
      'relative p-8 overflow-hidden'
    ]">
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
            {{ __('degrees.explore_degrees') }}
          </h1>
          <p :class="[
            'text-lg max-w-2xl',
            themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'
          ]">
            {{ __('degrees.discover_education') }}
          </p>
        </div>
      </CardContent>
    </Card>

    <!-- Filters -->
    <DegreeFilters :initial-filters="filters" @update:filters="handleFiltersUpdate" @reset="resetFilters" />

    <!-- Degrees Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <DegreeCard v-for="(degree, index) in degrees.data" :key="degree.id" :degree="degree"
        :style="{ animationDelay: `${index * 50}ms` }" />
    </div>

    <!-- Empty State -->
    <EmptyState v-if="degrees.data.length === 0" @reset="resetFilters" type="degrees" />

    <!-- Load More -->
    <div v-if="hasMorePages" class="flex justify-center mt-8">
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
const hasMorePages = ref(degrees.value.meta.current_page < degrees.value.meta.last_page);

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
      hasMorePages.value = degrees.value.meta.current_page < degrees.value.meta.last_page;
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
    only: ['degrees'],
    onSuccess: (page) => {
      degrees.value.data = [...degrees.value.data, ...page.props.degrees.data];
      hasMorePages.value = page.props.degrees.meta.current_page < page.props.degrees.meta.last_page;
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
