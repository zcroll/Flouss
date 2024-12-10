<template>
  <AppLayout>
    <div class="min-h-screen">
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->


        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Filters Sidebar -->
          <div class="lg:col-span-1">
            <FilterFormation
              v-model:filters="form"
              :etablissements="etablissements"
              :diplomas="diplomas"
              :disciplines="disciplines"
            />
          </div>

          <!-- Formation List -->
          <div class="lg:col-span-3">
            <!-- Results Count -->
            <div class="mb-6 flex items-center justify-between">
              <p class="text-sm text-gray-600">
                {{ formations.total }} {{ __('formations.results_found') }}
              </p>
              
              <div class="flex items-center gap-3">
                <!-- View Toggle -->
                <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-lg">
                  <button 
                    @click="viewMode = 'grid'"
                    :class="[
                      'p-2 rounded-md transition-colors',
                      viewMode === 'grid' 
                        ? 'bg-white shadow-sm text-[#db492b]' 
                        : 'text-gray-500 hover:text-gray-700'
                    ]"
                  >
                    <Squares2X2Icon class="w-5 h-5" />
                  </button>
                  <button 
                    @click="viewMode = 'list'"
                    :class="[
                      'p-2 rounded-md transition-colors',
                      viewMode === 'list' 
                        ? 'bg-white shadow-sm text-[#db492b]' 
                        : 'text-gray-500 hover:text-gray-700'
                    ]"
                  >
                    <ListBulletIcon class="w-5 h-5" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Grid View -->
            <div v-if="viewMode === 'grid'" 
                 class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="formation in formations.data" 
                   :key="formation.id" 
                   class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-200 group">
                <Link :href="route('formation.show', formation.id)" 
                      class="block p-6 h-full flex flex-col">
                  <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 group-hover:text-[#db492b] transition-colors duration-200 line-clamp-2">
                      {{ formation.nom }}
                    </h3>
                    <div class="flex flex-wrap gap-4 text-gray-600 text-sm">
                      <div class="flex items-center gap-2">
                        <AcademicCapIcon class="w-5 h-5 text-gray-400" />
                        <span>{{ formation.type_etablissement }}</span>
                      </div>
                      <div class="flex items-center gap-2">
                        <MapPinIcon class="w-5 h-5 text-gray-400" />
                        <span>{{ formation.ville }}</span>
                      </div>
                    </div>
                  </div>

                  <div class="mt-auto pt-4">
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-3 py-1 bg-[#db492b]/10 text-[#db492b] rounded-lg text-sm font-medium">
                        {{ formation.niveau }}
                      </span>
                      <span class="inline-flex items-center px-3 py-1 bg-[#1d7678]/10 text-[#1d7678] rounded-lg text-sm font-medium">
                        {{ formation.diploma }}
                      </span>
                    </div>
                  </div>
                </Link>
              </div>
            </div>

            <!-- List View -->
            <div v-else class="space-y-4">
              <div v-for="formation in formations.data" 
                   :key="formation.id" 
                   class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-200 group">
                <Link :href="route('formation.show', formation.id)" 
                      class="block p-6">
                  <div class="flex items-start justify-between gap-6">
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-gray-900 mb-3 group-hover:text-[#db492b] transition-colors duration-200">
                        {{ formation.nom }}
                      </h3>
                      <div class="flex flex-wrap gap-4 text-gray-600 text-sm mb-4">
                        <div class="flex items-center gap-2">
                          <AcademicCapIcon class="w-5 h-5 text-gray-400" />
                          <span>{{ formation.type_etablissement }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <MapPinIcon class="w-5 h-5 text-gray-400" />
                          <span>{{ formation.ville }}</span>
                        </div>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 bg-[#db492b]/10 text-[#db492b] rounded-lg text-sm font-medium">
                          {{ formation.niveau }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-[#1d7678]/10 text-[#1d7678] rounded-lg text-sm font-medium">
                          {{ formation.diploma }}
                        </span>
                      </div>
                    </div>
                    <ChevronRightIcon class="w-5 h-5 text-gray-400 group-hover:text-[#db492b] transition-colors duration-200" />
                  </div>
                </Link>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
              <div v-if="formations.links && formations.links.length > 3" 
                   class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px overflow-hidden" 
                     aria-label="Pagination">
                  <Link v-for="(link, index) in formations.links" 
                        :key="index"
                        :href="link.url"
                        :only="['formations', 'filters']"
                        :data="form"
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
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
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
    required: true
  },
  etablissements: {
    type: Array,
    required: true
  },
  diplomas: {
    type: Array,
    required: true
  },
  disciplines: {
    type: Array,
    required: true
  }
});

const viewMode = ref('grid'); // 'grid' or 'list'

const form = ref({
  search: props.filters.search || '',
  etablissements: props.filters.etablissements || [],
  diplomas: props.filters.diplomas || [],
  disciplines: props.filters.disciplines || [],
  region: props.filters.region || ''
});

console.log(props.diplomas);

// Watch for filter changes and update the URL
watch(form, (newForm) => {
  router.get(route('formations.index'), newForm, {
    preserveState: true,
    preserveScroll: true,
    only: ['formations', 'filters']
  });
}, { deep: true });
</script>