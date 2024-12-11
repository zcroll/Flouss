<template>
  <AppLayout>
    <div class="min-h-screen">
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Filters Sidebar -->
          <div class="lg:col-span-1">
            <FilterFormation
              :filters="filters"
              :etablissements="availableFilters.etablissements"
              :diplomas="availableFilters.diplomas"
              :disciplines="availableFilters.disciplines"
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
            <div v-if="formations.links?.length > 3" class="mt-12 flex justify-center">
              <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px overflow-hidden" aria-label="Pagination">
                <Link 
                  v-for="(link, index) in formations.links" 
                  :key="index"
                  :href="link.url || ''"
                  :only="['formations', 'filters']"
                  :data="filters"
                  :class="[
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                    link.active 
                      ? 'z-10 bg-[#db492b] border-[#db492b] text-white'
                      : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50',
                    link.url === null ? 'cursor-not-allowed opacity-50' : ''
                  ]"
                  v-html="link.label"
                  :preserve-scroll="true"
                />
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
  ChevronRightIcon
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
  }
});

const viewMode = ref('grid');

// Compute available filter options from current formations data
const availableFilters = computed(() => {
  const values = {
    etablissements: new Set(),
    disciplines: new Set(),
    diplomas: new Set()
  };

  // Extract unique values from current formations
  props.formations.data.forEach(formation => {
    if (formation.type_etablissement) {
      values.etablissements.add(formation.type_etablissement);
    }
    if (formation.discipline) {
      values.disciplines.add(formation.discipline);
    }
    if (formation.diploma) {
      values.diplomas.add(formation.diploma);
    }
  });

  // Convert to sorted arrays and format as needed
  return {
    etablissements: Array.from(values.etablissements)
      .sort()
      .map(value => ({ id: value, nom: value })),
    disciplines: Array.from(values.disciplines).sort(),
    diplomas: Array.from(values.diplomas).sort()
  };
});

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