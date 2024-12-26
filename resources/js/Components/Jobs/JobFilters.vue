<template>
  <div class="sticky top-4 z-30">
    <!-- Desktop Filters -->
    <Card :class="[
      'hidden sm:block'
    ]">
      <CardContent class="p-6">
        <div class="flex gap-6">
          <div class="flex-1">
            <label for="search-desktop" class="block text-sm font-medium text-gray-700 mb-2">
              {{ __('jobs.search') }}
            </label>
            <div class="relative group">
              <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5 transition-colors"
                :class="themeClasses.hover" />
              <input id="search-desktop" v-model="searchQuery" type="text" :placeholder="__('jobs.search_jobs')"
                class="w-full pl-12 pr-6 py-3 text-gray-900 placeholder-gray-500 bg-white/80 backdrop-blur-md rounded-full border border-white/20 transition duration-200"
                :class="themeClasses.focus" @input="debouncedSearch" />
            </div>
          </div>

          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              {{ __('jobs.education_levels') }}
            </label>
            <CustomMultiSelect v-model="selectedEducationLevels" :options="educationLevelOptions"
              :placeholder="__('jobs.select_education_levels')" class="backdrop-blur-md" />
          </div>

          <div class="flex items-end">
            <button @click="resetFilters"
              class="group px-6 py-3 bg-white/80 border border-white/20 backdrop-blur-xl text-gray-700 font-medium rounded-full focus:ring-2 focus:ring-offset-2 transition duration-200"
              :class="[themeClasses.hover, themeClasses.focus]">
              <span class="flex items-center gap-2">
                <RefreshCw class="w-4 h-4 transition-transform group-hover:rotate-180" />
                {{ __('jobs.reset_filters') }}
              </span>
            </button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Mobile Filters -->
    <div class="sm:hidden">
      <Sheet>
        <SheetTrigger asChild>
          <Button variant="outline"
            class="w-full flex justify-between items-center bg-white/80 backdrop-blur-xl rounded-full border border-white/20">
            <span class="flex items-center gap-2">
              <Search class="w-5 h-5" />
              {{ __('jobs.filters') }}
            </span>
            <div class="flex items-center gap-1">
              <span v-if="activeFiltersCount" class="text-white text-xs font-medium px-2 py-0.5 rounded-full"
                :class="themeClasses.active">
                {{ activeFiltersCount }}
              </span>
              <ChevronDown class="w-4 h-4" />
            </div>
          </Button>
        </SheetTrigger>
        <SheetContent side="bottom" class="rounded-t-3xl bg-white/95 backdrop-blur-xl">
          <div class="space-y-6 p-4">
            <!-- Search -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('jobs.search') }}
              </label>
              <div class="relative">
                <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                <input v-model="searchQuery" type="text" :placeholder="__('jobs.search_jobs')"
                  class="w-full pl-12 pr-6 py-3 bg-white/80 rounded-full border border-gray-200"
                  :class="themeClasses.focus" />
              </div>
            </div>

            <!-- Education Levels -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('jobs.education_levels') }}
              </label>
              <CustomMultiSelect v-model="selectedEducationLevels" :options="educationLevelOptions"
                :placeholder="__('jobs.select_education_levels')" />
            </div>

            <!-- Reset Button -->
            <button @click="resetFilters"
              class="w-full px-6 py-3 font-medium rounded-full transition-colors duration-200"
              :class="[themeClasses.button, themeClasses.text]">
              {{ __('jobs.reset_filters') }}
            </button>
          </div>
        </SheetContent>
      </Sheet>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Card, CardContent } from "@/Components/ui/card";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import { Button } from "@/Components/ui/button";
import { Search, ChevronDown, RefreshCw } from 'lucide-vue-next';
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import debounce from 'lodash/debounce';

const props = defineProps({
  initialFilters: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:filters', 'reset']);

const themeStore = useThemeStore();

const searchQuery = ref(props.initialFilters.q || '');
const selectedEducationLevels = ref(props.initialFilters.education ? props.initialFilters.education.map(level => ({
  value: level,
  label: level
})) : []);

const educationLevelOptions = [
  { value: "High School", label: "High School" },
  { value: "Associate", label: "Associate" },
  { value: "Bachelor's", label: "Bachelor's" },
  { value: "Doctorate", label: "Doctorate" },
  { value: "Master's", label: "Master's" },
  { value: "No Education", label: "No Education" }
];

const themeClasses = computed(() => ({
  hover: themeStore.isDarkMode ? 'hover:text-white' : 'hover:text-gray-900',
  focus: themeStore.isDarkMode ? 'focus:ring-gray-500' : 'focus:ring-primary-500',
  button: [
    'transition-colors duration-200',
    themeStore.isDarkMode
      ? `bg-${themeStore.currentTheme.primary}-600 hover:bg-${themeStore.currentTheme.primary}-700`
      : `bg-${themeStore.currentTheme.primary}-500 hover:bg-${themeStore.currentTheme.primary}-600`,
    'text-white'
  ],
  text: [
    themeStore.isDarkMode
      ? `text-${themeStore.currentTheme.primary}-400`
      : `text-${themeStore.currentTheme.primary}-600`
  ],
  active: [
    themeStore.isDarkMode
      ? `bg-${themeStore.currentTheme.primary}-600`
      : `bg-${themeStore.currentTheme.primary}-500`
  ]
}));

const debouncedSearch = debounce(() => {
  emitFilters();
}, 300);

const emitFilters = () => {
  const filters = {};
  if (searchQuery.value) filters.q = searchQuery.value;
  if (selectedEducationLevels.value.length > 0) {
    filters.education = selectedEducationLevels.value.map(level => level.value);
  }
  emit('update:filters', filters);
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedEducationLevels.value = [];
  emit('reset');
};

const activeFiltersCount = computed(() => {
  let count = 0;
  if (searchQuery.value) count++;
  if (selectedEducationLevels.value.length) count++;
  return count;
});

watch([selectedEducationLevels], () => {
  emitFilters();
}, { deep: true });

watch(() => props.initialFilters, (newFilters) => {
  searchQuery.value = newFilters.q || '';
  selectedEducationLevels.value = newFilters.education
    ? newFilters.education.map(level => ({ value: level, label: level }))
    : [];
}, { deep: true });
</script>