<template>
  <Head title="Formations" />
  <div class="flex-1 flex flex-col space-y-8 container mx-auto px-4 max-w-7xl">
    <!-- Hero Section -->
    <Card :class="[
      'relative p-8 overflow-hidden'
    ]">
      <CardContent>
        <div class="relative">
          <h1 :class="[
            'text-3xl md:text-4xl font-bold mb-4',
            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
          ]">
            {{ __('formations.explore_formations') }}
          </h1>
          <p :class="[
            'text-lg max-w-2xl',
            themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'
          ]">
            {{ __('formations.discover_programs') }}
          </p>
        </div>
      </CardContent>
    </Card>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Filters Sidebar -->
      <div class="lg:col-span-1">
        <FilterFormation :filters="filters" :filter-options="filterOptions" @update:filters="updateFilters" />
      </div>

      <!-- Formation List -->
      <div class="lg:col-span-3">
        <!-- Results Count & View Toggle -->
        <div class="mb-6 flex items-center justify-between">
          <p :class="[`text-${themeStore.currentTheme.primary}-600`, 'text-sm']">
            {{ formations.total }} {{ __('formations.results_found') }}
          </p>

          <!-- View Toggle -->
          <div :class="[
            'flex items-center gap-2 p-1 rounded-lg',
            `bg-${themeStore.currentTheme.primary}-50`
          ]">
            <button v-for="mode in ['grid', 'list']" :key="mode" @click="viewMode = mode" :class="[
              'p-2 rounded-md transition-colors',
              viewMode === mode
                ? 'bg-white shadow-sm ' + themeStore.getThemeClasses('text')
                : 'text-gray-500 hover:text-gray-700'
            ]">
              <component :is="mode === 'grid' ? LayoutGrid : List" class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Formation Cards -->
        <div :class="[viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 gap-4' : 'space-y-4']">
          <FormationCard v-for="(formation, index) in formations.data" :key="formation.id" :formation="formation"
            :style="{ animationDelay: `${index * 50}ms` }" :view-mode="viewMode" />
        </div>

        <!-- Loading Indicator -->
        <div v-if="isLoading" class="flex justify-center py-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
        </div>

        <!-- Intersection Observer Target -->
        <div ref="infiniteScrollTrigger" class="h-4 w-full"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import { Card, CardContent } from "@/Components/ui/card";
import { LayoutGrid, List } from 'lucide-vue-next';
import { useThemeStore } from '@/stores/theme/themeStore';
import FormationCard from '@/Components/Formations/FormationCard.vue';
import FilterFormation from '@/Components/Formations/FilterFormation.vue';
import { useActiveLink } from '@/composables/useActiveLink';

defineOptions({
  layout: MainLayout,
});

const props = defineProps({
  formations: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    required: true,
    default: () => ({})
  },
  filterOptions: {
    type: Object,
    required: true,
    default: () => ({
      etablissements: [],
      diplomas: [],
      disciplines: []
    })
  }
});

// Theme and active link
const themeStore = useThemeStore();
const { isActive } = useActiveLink();

const viewMode = ref('grid');
const formations = ref(props.formations);
const page = ref(1);
const isLoading = ref(false);
const infiniteScrollTrigger = ref(null);

// Handle filter updates
const updateFilters = (newFilters) => {
  router.visit(window.location.pathname, {
    data: newFilters,
    preserveState: true,
    preserveScroll: true,
    replace: true,
    only: ['formations'],
    onSuccess: () => {
      formations.value = props.formations;
      page.value = 1;
    },
  });
};

const loadMoreFormations = () => {
  if (isLoading.value || formations.value.current_page >= formations.value.last_page) return;

  isLoading.value = true;
  page.value++;

  router.reload({
    data: {
      page: page.value,
      ...props.filters,
    },
    preserveState: true,
    preserveScroll: true,
    only: ['formations'],
    onSuccess: (response) => {
      if (response?.props?.formations?.data) {
        formations.value = {
          ...response.props.formations,
          data: [...formations.value.data, ...response.props.formations.data]
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
        loadMoreFormations();
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