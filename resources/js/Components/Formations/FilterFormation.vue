<template>
  <Card class="sticky top-4 z-30">
    <CardContent class="p-6">
      <!-- Search Bar -->
      <div class="mb-6">
        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
          {{ __('formations.search') }}
        </label>
        <div class="relative group">
          <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5 transition-colors"
            :class="themeClasses.hover" />
          <input id="search" v-model="form.search" type="search" :placeholder="__('formations.search_placeholder')"
            class="w-full pl-12 pr-6 py-3 text-gray-900 placeholder-gray-500 bg-white/80 backdrop-blur-md rounded-full border border-white/20 transition duration-200"
            :class="themeClasses.focus" @input="debouncedEmit" />
        </div>
      </div>

      <!-- Active Filters -->
      <div v-if="hasActiveFilters" class="mb-6">
        <div class="flex flex-wrap gap-2">
          <!-- Selected Etablissements -->
          <div v-for="item in form.etablissements" :key="item.value" :class="[themeClasses.tag]">
            <Building class="h-3.5 w-3.5" />
            {{ item.label }}
            <button @click="removeFilter('etablissements', item)" class="ml-1 hover:text-gray-700">
              <X class="h-3.5 w-3.5" />
            </button>
          </div>

          <!-- Selected Diplomas -->
          <div v-for="item in form.diplomas" :key="item.value" :class="[themeClasses.tag]">
            <GraduationCap class="h-3.5 w-3.5" />
            {{ item.label }}
            <button @click="removeFilter('diplomas', item)" class="ml-1 hover:text-gray-700">
              <X class="h-3.5 w-3.5" />
            </button>
          </div>

          <!-- Selected Disciplines -->
          <div v-for="item in form.disciplines" :key="item.value" :class="[themeClasses.tag]">
            <BookOpen class="h-3.5 w-3.5" />
            {{ item.label }}
            <button @click="removeFilter('disciplines', item)" class="ml-1 hover:text-gray-700">
              <X class="h-3.5 w-3.5" />
            </button>
          </div>

          <!-- Selected Region -->
          <div v-if="selectedRegion" :class="[themeClasses.tag]">
            <MapPin class="h-3.5 w-3.5" />
            {{ selectedRegion.name }}
            <button @click="clearRegion" class="ml-1 hover:text-gray-700">
              <X class="h-3.5 w-3.5" />
            </button>
          </div>

          <button v-if="hasActiveFilters" @click="clearFilters"
            class="inline-flex items-center px-2 py-1 text-xs text-gray-500 hover:text-gray-700">
            <XCircle class="h-3.5 w-3.5 mr-1" />
            {{ __('formations.clear_filters') }}
          </button>
        </div>
      </div>

      <!-- Filter Groups -->
      <div class="space-y-6">
        <!-- Etablissement Filter -->
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <Building class="h-4 w-4 text-gray-400" />
            {{ __('formations.establishment') }}
          </label>
          <CustomMultiSelect v-model="form.etablissements" :options="etablissementOptions"
            :placeholder="__('formations.select_establishment')" track-by="value" label="label"
            @update:modelValue="value => handleFilterChange('etablissements', value)" />
        </div>

        <!-- Diploma Filter -->
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <GraduationCap class="h-4 w-4 text-gray-400" />
            {{ __('formations.diploma') }}
          </label>
          <CustomMultiSelect v-model="form.diplomas" :options="diplomaOptions"
            :placeholder="__('formations.select_diploma')" track-by="value" label="label"
            @update:modelValue="value => handleFilterChange('diplomas', value)" />
        </div>

        <!-- Discipline Filter -->
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <BookOpen class="h-4 w-4 text-gray-400" />
            {{ __('formations.discipline') }}
          </label>
          <CustomMultiSelect v-model="form.disciplines" :options="disciplineOptions"
            :placeholder="__('formations.select_discipline')" track-by="value" label="label"
            @update:modelValue="value => handleFilterChange('disciplines', value)" />
        </div>

        <!-- Region Selection -->
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <MapPin class="h-4 w-4 text-gray-400" />
            {{ __('formations.region') }}
          </label>
          <button @click="isMapOpen = true"
            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
            <span class="text-gray-600">
              {{ selectedRegion?.name || __('formations.select_region') }}
            </span>
            <Map class="h-4 w-4 text-gray-400" />
          </button>
        </div>
      </div>

      <!-- Map Dialog -->
      <Dialog :open="isMapOpen" @close="isMapOpen = false">
        <DialogContent class="sm:max-w-3xl">
          <DialogHeader>
            <DialogTitle>{{ __('formations.explore_by_region') }}</DialogTitle>
          </DialogHeader>
          <MoroccoMap v-model="selectedRegion" @update:modelValue="handleRegionSelect" />
        </DialogContent>
      </Dialog>
    </CardContent>
  </Card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { debounce } from 'lodash';
import { Card, CardContent } from "@/Components/ui/card";
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/Components/ui/dialog";
import {
  Search,
  X,
  XCircle,
  Building,
  GraduationCap,
  BookOpen,
  MapPin,
  Map
} from 'lucide-vue-next';
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import MoroccoMap from '@/Components/Maps/MoroccoMap.vue';
import { useThemeStore } from '@/stores/theme/themeStore';

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

const themeStore = useThemeStore();

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

// Computed properties
const hasActiveFilters = computed(() =>
  Boolean(
    form.value.search ||
    form.value.etablissements.length ||
    form.value.diplomas.length ||
    form.value.disciplines.length ||
    selectedRegion.value
  )
);

const themeClasses = computed(() => ({
  hover: themeStore.isDarkMode ? 'hover:text-white' : 'hover:text-gray-900',
  focus: themeStore.isDarkMode ? 'focus:ring-gray-500' : 'focus:ring-primary-500',
  tag: [
    'inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs',
    'bg-white/50 border border-white/20',
    themeStore.isDarkMode
      ? `text-${themeStore.currentTheme.primary}-400`
      : `text-${themeStore.currentTheme.primary}-600`
  ]
}));

const etablissementOptions = computed(() =>
  props.filterOptions.etablissements.map(e => ({
    value: e.id,
    label: e.nom
  }))
);

const diplomaOptions = computed(() =>
  props.filterOptions.diplomas.map(d => ({
    value: d,
    label: d
  }))
);

const disciplineOptions = computed(() =>
  props.filterOptions.disciplines.map(d => ({
    value: d,
    label: d
  }))
);

// Event handlers
const debouncedEmit = debounce(() => {
  emitFilters();
}, 300);

const emitFilters = () => {
  emit('update:filters', {
    search: form.value.search,
    etablissements: form.value.etablissements.map(item => item.value),
    diplomas: form.value.diplomas.map(item => item.value),
    disciplines: form.value.disciplines.map(item => item.value),
    region: selectedRegion.value?.name || '',
    page: 1
  });
};

const handleFilterChange = (field, value) => {
  form.value[field] = value;
  emitFilters();
};

const handleRegionSelect = (region) => {
  selectedRegion.value = region;
  isMapOpen.value = false;
  emitFilters();
};

const clearFilters = () => {
  form.value = {
    search: '',
    etablissements: [],
    diplomas: [],
    disciplines: []
  };
  selectedRegion.value = null;
  emitFilters();
};

const clearRegion = () => {
  selectedRegion.value = null;
  emitFilters();
};

const removeFilter = (field, itemToRemove) => {
  form.value[field] = form.value[field].filter(item => item.value !== itemToRemove.value);
  emitFilters();
};
</script>