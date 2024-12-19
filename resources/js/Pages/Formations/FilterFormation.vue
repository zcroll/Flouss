<template>
  <div class="bg-white border-2 border-[#db492b]/20 rounded-2xl p-6 shadow-lg shadow-[#db492b]/5">
    <!-- Search Bar -->
    <div class="mb-8">
      <div class="relative">
        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
        <input 
          id="search"
          :value="form.search"
          type="search"
          :placeholder="__('formations.search_placeholder')"
          class="w-full h-11 pl-10 rounded-3xl border-gray-200 bg-gray-50 shadow-sm focus:border-[#db492b] focus:ring-[#db492b] text-gray-900"
          @input="handleSearch"
        />
      </div>
    </div>

    <!-- Active Filters -->
    <div v-if="hasActiveFilters" class="mb-6 -mt-4">
      <div class="flex flex-wrap gap-2">
        <!-- Selected Etablissements -->
        <div v-for="item in form.etablissements" :key="item.value"
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#db492b]/10 text-[#db492b] text-sm">
          <BuildingOfficeIcon class="h-4 w-4 mr-1.5" />
          {{ item.label }}
          <button @click="removeFilter('etablissements', item)" class="ml-2 hover:text-[#db492b]/80">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>

        <!-- Selected Diplomas -->
        <div v-for="item in form.diplomas" :key="item.value"
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#1d7678]/10 text-[#1d7678] text-sm">
          <AcademicCapIcon class="h-4 w-4 mr-1.5" />
          {{ item.label }}
          <button @click="removeFilter('diplomas', item)" class="ml-2 hover:text-[#1d7678]/80">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>

        <!-- Selected Disciplines -->
        <div v-for="item in form.disciplines" :key="item.value"
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#db492b]/10 text-[#db492b] text-sm">
          <BookOpenIcon class="h-4 w-4 mr-1.5" />
          {{ item.label }}
          <button @click="removeFilter('disciplines', item)" class="ml-2 hover:text-[#db492b]/80">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>

        <div v-if="selectedRegion" 
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#db492b]/10 text-[#db492b] text-sm">
          <MapPinIcon class="h-4 w-4 mr-1.5" />
          {{ selectedRegion.name }}
          <button @click="clearRegion" class="ml-2 hover:text-[#db492b]/80">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>
        
        <button v-if="hasActiveFilters" 
                @click="clearFilters"
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
          :options="props.filterOptions.etablissements.map(e => ({
            value: e.id,
            label: e.nom
          }))"
          :placeholder="__('formations.select_establishment')"
          class="filter-select"
          track-by="value"
          label="label"
          @update:modelValue="value => handleFilterChange('etablissements', value)"
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
          :options="props.filterOptions.diplomas.map(d => ({
            value: d,
            label: d
          }))"
          :placeholder="__('formations.select_diploma')"
          class="filter-select"
          track-by="value"
          label="label"
          @update:modelValue="value => handleFilterChange('diplomas', value)"
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
          :options="props.filterOptions.disciplines.map(d => ({
            value: d,
            label: d
          }))"
          :placeholder="__('formations.select_discipline')"
          class="filter-select"
          track-by="value"
          label="label"
          @update:modelValue="value => handleFilterChange('disciplines', value)"
        />
      </div>

      <!-- Region Selection -->
      <div class="filter-group pt-2">
        <label class="filter-label">
          <MapPinIcon class="h-4 w-4 text-gray-400" />
          {{ __('formations.region') }}
        </label>
        <button 
          @click="isMapOpen = true"
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
      <Dialog as="div" @close="isMapOpen = false" class="relative z-50">
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
                  <button @click="isMapOpen = false" class="text-gray-400 hover:text-gray-500">
                    <XMarkIcon class="h-6 w-6" />
                  </button>
                </div>
                <MoroccoMap 
                  v-model="selectedRegion" 
                  @update:modelValue="handleRegionSelect"
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
import { ref, computed } from 'vue';
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

const emit = defineEmits(['update:filters']);

// Form state
const form = ref({
  search: props.filters.search || '',
  etablissements: (props.filters.etablissements || []).map(id => ({ value: id, label: id })),
  diplomas: (props.filters.diplomas || []).map(id => ({ value: id, label: id })),
  disciplines: (props.filters.disciplines || []).map(id => ({ value: id, label: id })),
});

// Map state
const isMapOpen = ref(false);
const selectedRegion = ref(props.filters.region ? { name: props.filters.region } : null);

// Computed properties for active filters
const hasActiveFilters = computed(() => 
  Boolean(
    form.value.search ||
    form.value.etablissements.length ||
    form.value.diplomas.length ||
    form.value.disciplines.length ||
    selectedRegion.value
  )
);

// Update filters with debounce
const updateFilters = debounce(() => {
  emit('update:filters', {
    search: form.value.search,
    etablissements: form.value.etablissements.map(item => item.value),
    diplomas: form.value.diplomas.map(item => item.value),
    disciplines: form.value.disciplines.map(item => item.value),
    region: selectedRegion.value?.name || '',
    page: 1
  });
}, 300);

// Event handlers
const handleSearch = (event) => {
  form.value.search = event.target.value;
  updateFilters();
};

const handleFilterChange = (field, value) => {
  form.value[field] = value;
  updateFilters();
};

const handleRegionSelect = (region) => {
  selectedRegion.value = region;
  isMapOpen.value = false;
  updateFilters();
};

const clearFilters = () => {
  form.value = {
    search: '',
    etablissements: [],
    diplomas: [],
    disciplines: []
  };
  selectedRegion.value = null;
  updateFilters();
};

const clearRegion = () => {
  selectedRegion.value = null;
  updateFilters();
};

const removeFilter = (field, itemToRemove) => {
  form.value[field] = form.value[field].filter(item => item.value !== itemToRemove.value);
  updateFilters();
};
</script>

<style scoped>
.filter-group {
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
}

.filter-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: rgb(55, 65, 81);
}

.filter-select :deep(.multiselect) {
  border-radius: 0.5rem;
  border-color: rgb(229, 231, 235);
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.filter-select :deep(.multiselect-tags) {
  gap: 0.25rem;
}

.filter-select :deep(.multiselect-tag) {
  background-color: rgba(219, 73, 43, 0.1);
  color: #db492b;
  border: none;
  border-radius: 0.5rem;
  padding: 0.25rem 0.5rem;
}

.filter-select :deep(.multiselect-option.is-selected) {
  background-color: #db492b;
  color: white;
}

.filter-select :deep(.multiselect-option.is-pointed) {
  background-color: rgba(219, 73, 43, 0.1);
  color: #db492b;
}
</style>