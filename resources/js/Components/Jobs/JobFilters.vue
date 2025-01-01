<template>
  <div class="sticky top-20 transition-all duration-200" style="max-height: calc(100vh - 6rem);">
    <div :class="[
      'backdrop-blur-xl rounded-3xl border shadow-sm overflow-hidden',
      themeStore.getThemeClasses('base'),
      themeStore.isDarkMode ? `border-${themeStore.currentTheme.border}` : `border-${themeStore.currentTheme.border}`
    ]">
      <!-- Header -->
      <div :class="[
        'p-6 border-b',
        themeStore.isDarkMode ? 'border-gray-800/20 bg-gray-900/30' : 'border-gray-100/20 bg-gray-50/50'
      ]">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4"
            :class="[`text-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '400' : '500'}`]" />
          <input 
            v-model="searchQuery" 
            type="search" 
            :placeholder="__('jobs.search_jobs')" 
            :class="[
              'w-full h-11 pl-10 rounded-3xl shadow-sm transition-colors duration-200',
              themeStore.isDarkMode 
                ? 'bg-gray-900/50 border-gray-700 text-white placeholder-gray-400'
                : 'bg-white/50 border-gray-200 text-gray-900 placeholder-gray-500',
              `focus:border-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '500' : '400'}`,
              `focus:ring-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '500' : '400'}`
            ]" 
            @input="debouncedSearch"
          />
        </div>
      </div>

      <!-- Scrollable Content -->
      <div class="p-6 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 12rem);">
        <!-- Active Filters -->
        <TransitionGroup 
          name="filter-tags" 
          tag="div" 
          class="space-y-2"
          v-if="activeFiltersCount"
        >
          <div class="flex items-center justify-between">
            <h3 :class="[
              'text-sm font-medium',
              themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700'
            ]">
              {{ __('jobs.active_filters') }}
            </h3>
            <button 
              @click="resetAllFilters"
              :class="[
                'text-sm flex items-center gap-1 transition-colors duration-200',
                themeStore.isDarkMode 
                  ? 'text-gray-400 hover:text-gray-200' 
                  : 'text-gray-500 hover:text-gray-700'
              ]"
            >
              <XCircle class="h-4 w-4" />
              {{ __('jobs.clear_all') }}
            </button>
          </div>

          <div class="flex flex-wrap gap-2">
            <TransitionGroup name="filter-tag">
              <!-- Search Query Tag -->
              <div 
                v-if="searchQuery" 
                :key="'search'"
                :class="[
                  'inline-flex items-center px-3 py-1.5 rounded-lg text-sm transition-all duration-200',
                  themeStore.isDarkMode
                    ? `bg-${themeStore.currentTheme.primary}-900/50 text-${themeStore.currentTheme.primary}-300`
                    : `bg-${themeStore.currentTheme.primary}-50 text-${themeStore.currentTheme.primary}-600`
                ]"
              >
                <Search class="h-4 w-4 mr-1.5" />
                {{ searchQuery }}
                <button 
                  @click="clearSearch" 
                  class="ml-2 hover:opacity-75 transition-opacity"
                >
                  <X class="h-4 w-4" />
                </button>
              </div>

              <!-- Filter Tags -->
              <template v-for="(items, type) in activeFiltersByType" :key="type">
                <div 
                  v-for="item in items" 
                  :key="`${type}-${item.value}`"
                  :class="[
                    'inline-flex items-center px-3 py-1.5 rounded-lg text-sm transition-all duration-200',
                    themeStore.isDarkMode
                      ? `bg-${themeStore.currentTheme.primary}-900/50 text-${themeStore.currentTheme.primary}-300`
                      : `bg-${themeStore.currentTheme.primary}-50 text-${themeStore.currentTheme.primary}-600`
                  ]"
                >
                  <component :is="filterIcons[type]" class="h-4 w-4 mr-1.5" />
                  {{ item.label }}
                  <button 
                    @click="removeFilter(type, item)"
                    class="ml-2 hover:opacity-75 transition-opacity"
                  >
                    <X class="h-4 w-4" />
                  </button>
                </div>
              </template>
            </TransitionGroup>
          </div>
        </TransitionGroup>

        <!-- Filter Groups -->
        <div class="space-y-4">
          <template v-for="(group, index) in filterGroups" :key="index">
            <div :class="[
              'filter-section transition-all duration-200',
              themeStore.isDarkMode
                ? 'bg-gray-900/50 border-gray-800/30'
                : 'bg-white/50 border-white/20'
            ]">
              <label class="filter-label">
                <component :is="group.icon" :class="[
                  'h-4 w-4',
                  themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
                ]" />
                <span :class="[
                  themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700'
                ]">
                  {{ __(`jobs.${group.label}`) }}
                </span>
              </label>
              <CustomMultiSelect 
                v-model="filters[group.key]" 
                :options="group.options"
                :placeholder="__(`jobs.select_${group.label}`)" 
                class="filter-select"
                :classes="{
                  focus: `ring-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '500' : '400'}`,
                  icon: `text-${themeStore.currentTheme.primary}-${themeStore.isDarkMode ? '400' : '500'}`
                }"
                @update:modelValue="handleFilterChange(group.key)" 
              />
            </div>
          </template>
        </div>

        <!-- Help Section -->
        <div :class="[
          'mt-8 p-4 rounded-lg border transition-colors duration-200',
          themeStore.isDarkMode 
            ? 'bg-gray-900/30 border-gray-800/30 text-gray-300'
            : 'bg-gray-50/50 border-gray-100/20 text-gray-600'
        ]">
          <h4 :class="[
            'text-sm font-medium mb-2',
            themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700'
          ]">
            {{ __('jobs.need_help') }}
          </h4>
          <p class="text-sm">
            {{ __('jobs.filter_help_text') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { debounce } from 'lodash';
import { Search, X, XCircle } from 'lucide-vue-next';
import { AcademicCapIcon, BriefcaseIcon, MapPinIcon } from '@heroicons/vue/24/outline';
import CustomMultiSelect from '@/Components/helpers/CustomMultiSelect.vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  initialFilters: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:filters', 'reset']);
const themeStore = useThemeStore();

// Filter state management
const filters = ref({
  education: [],
  jobTypes: [],
  locations: []
});

const searchQuery = ref('');

// Filter configurations
const filterGroups = [
  {
    key: 'education',
    label: 'education_levels',
    icon: AcademicCapIcon,
    options: [
      { value: "High School", label: "High School" },
      { value: "Associate", label: "Associate" },
      { value: "Bachelor's", label: "Bachelor's" },
      { value: "Master's", label: "Master's" },
      { value: "Doctorate", label: "Doctorate" },
      { value: "No Education", label: "No Education" }
    ]
  },
  
];

const filterIcons = {
  education: AcademicCapIcon,
  jobTypes: BriefcaseIcon,
  locations: MapPinIcon
};

// Computed properties
const activeFiltersByType = computed(() => {
  const result = {};
  Object.entries(filters.value).forEach(([key, value]) => {
    if (value.length > 0) {
      result[key] = value;
    }
  });
  return result;
});

const activeFiltersCount = computed(() => {
  return Object.values(filters.value).reduce((count, items) => count + items.length, 0) + 
    (searchQuery.value ? 1 : 0);
});

// Methods
const debouncedSearch = debounce(() => {
  emitFilters();
}, 300);

const emitFilters = () => {
  const emitData = { ...filters.value };
  if (searchQuery.value) {
    emitData.q = searchQuery.value;
  }
  emit('update:filters', emitData);
};

const handleFilterChange = () => {
  emitFilters();
};

const removeFilter = (type, item) => {
  filters.value[type] = filters.value[type].filter(i => i.value !== item.value);
  emitFilters();
};

const clearSearch = () => {
  searchQuery.value = '';
  emitFilters();
};

const resetAllFilters = () => {
  // Reset search query
  searchQuery.value = '';
  
  // Reset all filters
  Object.keys(filters.value).forEach(key => {
    filters.value[key] = [];
  });

  // Emit reset event and force page reload to reset pagination
  emit('reset');
  
  // Use router to reset the page with empty query parameters
  router.visit(window.location.pathname, {
    preserveState: false, // Don't preserve the state
    preserveScroll: false, // Don't preserve scroll position
    replace: true, // Replace current history entry
    data: {} // Empty data to clear all query parameters
  });
};

// Watch for external changes
watch(() => props.initialFilters, (newFilters) => {
  Object.keys(filters.value).forEach(key => {
    filters.value[key] = newFilters[key] || [];
  });
  searchQuery.value = newFilters.q || '';
}, { deep: true, immediate: true });
</script>

<style scoped>
.filter-section {
  @apply p-4 rounded-xl border transition-all duration-200;
}

.filter-label {
  @apply flex items-center gap-2 text-sm font-medium mb-2;
}

/* Transitions */
.filter-tags-enter-active,
.filter-tags-leave-active {
  transition: all 0.3s ease;
}

.filter-tags-enter-from,
.filter-tags-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.filter-tag-enter-active,
.filter-tag-leave-active {
  transition: all 0.2s ease;
}

.filter-tag-enter-from,
.filter-tag-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

/* Scrollbar styling */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  @apply bg-gray-300/50 rounded-full dark:bg-gray-600/50;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  @apply bg-gray-400/50 dark:bg-gray-500/50;
}
</style>