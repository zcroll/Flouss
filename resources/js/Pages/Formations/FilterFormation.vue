<template>
  <div class="bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
    <!-- Search Bar -->
    <div class="mb-8">
      <div class="relative">
        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
        <input 
          id="search"
          v-model="form.search"
          type="search"
          :placeholder="__('formations.search_placeholder')"
          class="w-full h-11 pl-10 rounded-3xl border-gray-200 bg-gray-50 shadow-sm focus:border-[#db492b] focus:ring-[#db492b] text-gray-900"
          @input="debouncedFilter"
        />
      </div>
    </div>

    <!-- Active Filters -->
    <div v-if="hasActiveFilters" class="mb-6 -mt-4">
      <div class="flex flex-wrap gap-2">
        <div v-if="selectedRegion" 
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#db492b]/10 text-[#db492b] text-sm">
          <MapPinIcon class="h-4 w-4 mr-1.5" />
          {{ selectedRegion.name }}
          <button @click="clearRegion" class="ml-2 hover:text-[#db492b]/80">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>
        
        <button v-if="hasActiveFilters" 
                @click="clearAllFilters"
                class="text-sm text-gray-500 hover:text-[#db492b] flex items-center">
          <XCircleIcon class="h-4 w-4 mr-1" />
          {{ __('formations.clear_filters') }}
        </button>
      </div>
    </div>

    <!-- Filter Groups -->
    <div class="space-y-6">
      <!-- Etablissement Filter -->
      <div class="filter-group">
        <label class="filter-label">
          <BuildingOfficeIcon class="h-4 w-4 text-gray-400" />
          {{ __('formations.establishment') }}
        </label>
        <CustomMultiSelect
          v-model="form.etablissements"
          :options="etablissementOptions"
          :placeholder="__('formations.select_establishment')"
          class="filter-select"
          @update:modelValue="filter"
        />
      </div>

      <!-- Diploma Filter -->
      <div class="filter-group">
        <label class="filter-label">
          <AcademicCapIcon class="h-4 w-4 text-gray-400" />
          {{ __('formations.diploma') }}
        </label>
        <CustomMultiSelect
          v-model="form.diplomas"
          :options="diplomaOptions"
          :placeholder="__('formations.select_diploma')"
          class="filter-select"
          @update:modelValue="filter"
        />
      </div>

      <!-- Discipline Filter -->
      <div class="filter-group">
        <label class="filter-label">
          <BookOpenIcon class="h-4 w-4 text-gray-400" />
          {{ __('formations.discipline') }}
        </label>
        <CustomMultiSelect
          v-model="form.disciplines"
          :options="disciplineOptions"
          :placeholder="__('formations.select_discipline')"
          class="filter-select"
          @update:modelValue="filter"
        />
      </div>

      <!-- Region Selection -->
      <div class="filter-group pt-2">
        <label class="filter-label">
          <MapPinIcon class="h-4 w-4 text-gray-400" />
          {{ __('formations.region') }}
        </label>
        <button 
          @click="openMap"
          class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-200"
        >
          <span class="text-gray-600">
            {{ selectedRegion?.name || __('formations.select_region') }}
          </span>
          <MapIcon class="h-5 w-5 text-gray-400" />
        </button>
      </div>
    </div>

    <!-- Map Dialog -->
    <TransitionRoot appear :show="isMapOpen" as="template">
      <Dialog as="div" @close="closeMap" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/30" aria-hidden="true" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-3xl rounded-xl bg-white p-6 shadow-xl">
                <div class="flex justify-between items-center mb-4">
                  <DialogTitle class="text-lg font-medium text-gray-900">
                    {{ __('formations.explore_by_region') }}
                  </DialogTitle>
                  <button @click="closeMap" class="text-gray-400 hover:text-gray-500">
                    <XMarkIcon class="h-6 w-6" />
                  </button>
                </div>
                <MoroccoMap 
                  v-model="selectedRegion" 
                  @update:modelValue="onRegionSelect" 
                />
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { 
  MapIcon, 
  XMarkIcon, 
  MagnifyingGlassIcon,
  MapPinIcon,
  XCircleIcon,
  AcademicCapIcon,
  BookOpenIcon,
  BuildingOfficeIcon
} from '@heroicons/vue/24/outline';
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import MoroccoMap from '@/Components/Maps/MoroccoMap.vue';

const props = defineProps({
  filters: {
    type: Object,
    required: true
  },
  etablissements: {
    type: Array,
    required: true,
    default: () => []
  },
  diplomas: {
    type: Array,
    required: true,
    default: () => []
  },
  disciplines: {
    type: Array,
    required: true,
    default: () => []
  }
});

const emit = defineEmits(['update:filters']);

const isMapOpen = ref(false);
const selectedRegion = ref(props.filters.region ? { name: props.filters.region } : null);

const form = ref({
  search: props.filters.search || '',
  etablissements: props.filters.etablissements || [],
  diplomas: props.filters.diplomas || [],
  disciplines: props.filters.disciplines || [],
  region: props.filters.region || ''
});

// Computed properties for options
const etablissementOptions = computed(() => 
  props.etablissements.map(e => ({ value: e.id, label: e.nom }))
);

const diplomaOptions = computed(() => 
  props.diplomas.map(d => ({ value: d, label: d }))
);

const disciplineOptions = computed(() => 
  props.disciplines.map(d => ({ value: d, label: d }))
);

const hasActiveFilters = computed(() => {
  return form.value.search || 
         form.value.etablissements.length > 0 || 
         form.value.diplomas.length > 0 || 
         form.value.disciplines.length > 0 || 
         form.value.region;
});

// Methods
const filter = () => {
  emit('update:filters', {
    ...form.value,
    page: 1
  });
};

const debouncedFilter = debounce(filter, 300);

const openMap = () => {
  isMapOpen.value = true;
};

const closeMap = () => {
  isMapOpen.value = false;
};

const onRegionSelect = (region) => {
  selectedRegion.value = region;
  form.value.region = region ? region.name : '';
  closeMap();
  filter();
};

const clearRegion = () => {
  selectedRegion.value = null;
  form.value.region = '';
  filter();
};

const clearAllFilters = () => {
  form.value = {
    search: '',
    etablissements: [],
    diplomas: [],
    disciplines: [],
    region: ''
  };
  selectedRegion.value = null;
  filter();
};

// Watch for external filter changes
watch(() => props.filters, (newFilters) => {
  form.value = {
    search: newFilters.search || '',
    etablissements: newFilters.etablissements || [],
    diplomas: newFilters.diplomas || [],
    disciplines: newFilters.disciplines || [],
    region: newFilters.region || ''
  };
  
  if (newFilters.region) {
    selectedRegion.value = { name: newFilters.region };
  } else {
    selectedRegion.value = null;
  }
}, { deep: true });
</script>

<style scoped>
.filter-group {
  @apply space-y-2;
}

.filter-label {
  @apply flex items-center gap-2 text-sm font-medium text-gray-700;
}

.filter-select :deep(.multiselect) {
  @apply rounded-lg border-gray-200 shadow-sm;
}

.filter-select :deep(.multiselect-tags) {
  @apply gap-1;
}

.filter-select :deep(.multiselect-tag) {
  @apply bg-[#db492b]/10 text-[#db492b] border-none rounded-lg py-1 px-2;
}

.filter-select :deep(.multiselect-option.is-selected) {
  @apply bg-[#db492b] text-white;
}

.filter-select :deep(.multiselect-option.is-pointed) {
  @apply bg-[#db492b]/10 text-[#db492b];
}
</style>