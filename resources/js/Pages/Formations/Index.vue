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

        <!-- Pagination -->
        <div v-if="formations.links?.length > 3" class="mt-8">
          <!-- Desktop Pagination -->
          <nav class="hidden sm:flex items-center justify-center gap-1" aria-label="Pagination">
            <Link v-for="(link, index) in formations.links" :key="index" :href="link.url || ''"
              :only="['formations', 'filters']" :data="filters" :class="[
                'relative inline-flex items-center justify-center min-w-[40px] h-10 text-sm font-medium transition-colors',
                index === 0 || index === formations.links.length - 1
                  ? 'px-3 rounded-lg'
                  : 'px-4 rounded-md',
                link.active
                  ? `bg-${themeStore.currentTheme.primary}-500 text-white shadow-sm`
                  : 'text-gray-700 hover:bg-gray-100',
                link.url === null
                  ? 'pointer-events-none text-gray-400 bg-gray-50'
                  : 'bg-white',
                'border border-gray-200',
                !link.active && link.url !== null
                  ? `hover:border-${themeStore.currentTheme.primary}-500/20 hover:text-${themeStore.currentTheme.primary}-600`
                  : ''
              ]" :preserve-scroll="true">
            <template v-if="index === 0 || index === formations.links.length - 1">
              <span v-if="index === 0" class="flex items-center gap-1">
                <ChevronLeft class="w-4 h-4" />
                <span>{{ __('formations.previous') }}</span>
              </span>
              <span v-else class="flex items-center gap-1">
                <span>{{ __('formations.next') }}</span>
                <ChevronRight class="w-4 h-4" />
              </span>
            </template>
            <template v-else>
              <span v-html="link.label"></span>
            </template>
            </Link>
          </nav>

          <!-- Mobile Pagination -->
          <nav class="sm:hidden flex items-center justify-between px-2" aria-label="Pagination">
            <Link :href="formations.prev_page_url || ''" :only="['formations', 'filters']" :data="filters" :class="[
              'flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors',
              formations.prev_page_url
                ? 'text-gray-700 bg-white border border-gray-200 hover:border-primary-500/20 hover:text-primary-600'
                : 'pointer-events-none text-gray-400 bg-gray-50 border border-gray-200'
            ]" :preserve-scroll="true">
            <ChevronLeft class="w-4 h-4" />
            {{ __('formations.previous') }}
            </Link>

            <span class="text-sm text-gray-700">
              {{ __('formations.page_info', { current: formations.current_page, total: formations.last_page }) }}
            </span>

            <Link :href="formations.next_page_url || ''" :only="['formations', 'filters']" :data="filters" :class="[
              'flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors',
              formations.next_page_url
                ? 'text-gray-700 bg-white border border-gray-200 hover:border-primary-500/20 hover:text-primary-600'
                : 'pointer-events-none text-gray-400 bg-gray-50 border border-gray-200'
            ]" :preserve-scroll="true">
            {{ __('formations.next') }}
            <ChevronRight class="w-4 h-4" />
            </Link>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import { Card, CardContent } from "@/Components/ui/card";
import { LayoutGrid, List, ChevronLeft, ChevronRight } from 'lucide-vue-next';
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

// Handle filter updates
const updateFilters = (newFilters) => {
  router.get(
    route('formations.index'),
    { ...newFilters, page: 1 },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['formations', 'filters']
    }
  );
};
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