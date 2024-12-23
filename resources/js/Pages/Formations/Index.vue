<template>
  <AppLayout>
    <div class="min-h-screen">
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Filters Sidebar -->
          <div class="lg:col-span-1">
            <FilterFormation
              :filters="filters"
              :filter-options="filterOptions"
              @update:filters="updateFilters"
            />
          </div>

          <!-- Formation List -->
          <div class="lg:col-span-3">
            <!-- Results Count -->
            <div class="mb-6 flex items-center justify-between">
              <p class="text-sm text-gray-600">
                {{ formations.total }} {{ __('formations.results_found') }}
              </p>
              
              <!-- View Toggle -->
              <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-lg">
                <button 
                  v-for="mode in ['grid', 'list']"
                  :key="mode"
                  @click="viewMode = mode"
                  :class="[
                    'p-2 rounded-md transition-colors',
                    viewMode === mode 
                      ? 'bg-white shadow-sm text-[#db492b]' 
                      : 'text-gray-500 hover:text-gray-700'
                  ]"
                >
                  <component 
                    :is="mode === 'grid' ? Squares2X2Icon : ListBulletIcon" 
                    class="w-5 h-5"
                  />
                </button>
              </div>
            </div>

            <!-- Formation List with Dynamic Layout -->
            <div 
              :class="[
                viewMode === 'grid' 
                  ? 'grid grid-cols-1 md:grid-cols-2 gap-6'
                  : 'space-y-4'
              ]"
            >
              <div 
                v-for="formation in formations.data" 
                :key="formation.id" 
                class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-200 group"
              >
                <Link 
                  :href="route('formation.show', formation.id)" 
                  :class="[
                    'block p-6',
                    viewMode === 'list' ? 'flex items-start justify-between gap-6' : 'h-full flex flex-col'
                  ]"
                >
                  <div :class="viewMode === 'list' ? 'flex-1' : ''">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 group-hover:text-[#db492b] transition-colors duration-200 line-clamp-2">
                      {{ formation.nom }}
                    </h3>
                    <div class="flex flex-wrap gap-4 text-gray-600 text-sm mb-4">
                      <div v-if="formation.type_etablissement" class="flex items-center gap-2">
                        <AcademicCapIcon class="w-5 h-5 text-gray-400" />
                        <span>{{ formation.type_etablissement }}</span>
                      </div>
                      <div v-if="formation.ville" class="flex items-center gap-2">
                        <MapPinIcon class="w-5 h-5 text-gray-400" />
                        <span>{{ formation.ville }}</span>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                      <span v-if="formation.niveau" class="inline-flex items-center px-3 py-1 bg-[#db492b]/10 text-[#db492b] rounded-lg text-sm font-medium">
                        {{ formation.niveau }}
                      </span>
                      <span v-if="formation.diploma" class="inline-flex items-center px-3 py-1 bg-[#1d7678]/10 text-[#1d7678] rounded-lg text-sm font-medium">
                        {{ formation.diploma }}
                      </span>
                    </div>
                  </div>
                  <ChevronRightIcon v-if="viewMode === 'list'" class="w-5 h-5 text-gray-400 group-hover:text-[#db492b] transition-colors duration-200" />
                </Link>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="formations.links?.length > 3" class="mt-12">
              <!-- Desktop Pagination -->
              <nav class="hidden sm:flex items-center justify-center gap-1" aria-label="Pagination">
                <Link
                  v-for="(link, index) in formations.links"
                  :key="index"
                  :href="link.url || ''"
                  :only="['formations', 'filters']"
                  :data="filters"
                  :class="[
                    'relative inline-flex items-center justify-center min-w-[40px] h-10 text-sm font-medium transition-colors',
                    index === 0 || index === formations.links.length - 1
                      ? 'px-3 rounded-lg'
                      : 'px-4 rounded-md',
                    link.active
                      ? 'bg-[#db492b]/90 text-white shadow-sm'
                      : 'text-gray-700 hover:bg-gray-100',
                    link.url === null
                      ? 'pointer-events-none text-gray-400 bg-gray-50'
                      : 'bg-white',
                    'border border-gray-200',
                    !link.active && link.url !== null
                      ? 'hover:border-[#db492b]/20 hover:text-[#db492b]'
                      : ''
                  ]"
                  :preserve-scroll="true"
                >
                  <template v-if="index === 0 || index === formations.links.length - 1">
                    <span v-if="index === 0" class="flex items-center gap-1">
                      <ChevronLeftIcon class="w-4 h-4" />
                      <span>{{ __('formations.previous') }}</span>
                    </span>
                    <span v-else class="flex items-center gap-1">
                      <span>{{ __('formations.next') }}</span>
                      <ChevronRightIcon class="w-4 h-4" />
                    </span>
                  </template>
                  <template v-else>
                    <span v-html="link.label"></span>
                  </template>
                </Link>
              </nav>

              <!-- Mobile Pagination -->
              <nav class="sm:hidden flex items-center justify-between px-2 mb-8" aria-label="Pagination">
                <Link
                  :href="formations.prev_page_url || ''"
                  :only="['formations', 'filters']"
                  :data="filters"
                  :class="[
                    'flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                    formations.prev_page_url
                      ? 'text-gray-700 bg-white border border-gray-200 hover:border-[#db492b]/20 hover:text-[#db492b]'
                      : 'pointer-events-none text-gray-400 bg-gray-50 border border-gray-200'
                  ]"
                  :preserve-scroll="true"
                >
                  <ChevronLeftIcon class="w-4 h-4" />
                  {{ __('formations.previous') }}
                </Link>

                <span class="text-sm text-gray-700">
                  Page {{ formations.current_page }} of {{ formations.last_page }}
                </span>

                <Link
                  :href="formations.next_page_url || ''"
                  :only="['formations', 'filters']"
                  :data="filters"
                  :class="[
                    'flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                    formations.next_page_url
                      ? 'text-gray-700 bg-white border border-gray-200 hover:border-[#db492b]/20 hover:text-[#db492b]'
                      : 'pointer-events-none text-gray-400 bg-gray-50 border border-gray-200'
                  ]"
                  :preserve-scroll="true"
                >
                  {{ __('formations.next') }}
                  <ChevronRightIcon class="w-4 h-4" />
                </Link>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FilterFormation from './FilterFormation.vue';
import { 
  AcademicCapIcon, 
  MapPinIcon, 
  Squares2X2Icon,
  ListBulletIcon,
  ChevronRightIcon,
  ChevronLeftIcon
} from '@heroicons/vue/24/outline';

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