<template>
  <div>
    <!-- Empty State -->
    <EmptyState
      v-if="!formations.length"
      :title="__('formations.empty_state.title')"
      :description="__('formations.empty_state.description')"
      :button-text="__('formations.empty_state.clear_filters')"
      @action="$emit('clear-filters')"
    />

    <!-- Formations List -->
    <ul v-else role="list" class="grid grid-cols-1 gap-6">
      <li v-for="formation in formations" :key="formation.id" 
          :class="[
            'col-span-1 rounded-2xl shadow-lg transition duration-300 hover:shadow-xl backdrop-blur-xl',
            themeStore.getThemeClasses('background', 'light'),
            `border-2 border-${themeStore.currentTheme.border}`
          ]">
        <!-- Mobile View -->
        <div class="block lg:hidden">
          <div class="p-4">
            <div class="flex flex-col space-y-3">
              <div class="flex items-start justify-between">
                <h3 class="text-base font-semibold text-gray-900 line-clamp-3 flex-1 mr-2 break-words">
                  {{ formation.nom }}
                </h3>
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  `bg-${themeStore.currentTheme.button}/10`,
                  `text-${themeStore.currentTheme.button}`,
                  'whitespace-nowrap'
                ]">
                  {{ formation.niveau }}
                </span>
              </div>

              <div class="space-y-1.5 text-sm">
                <p class="flex items-start text-gray-600 relative">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-4 w-4 mr-2 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.type_etablissement }}</span>
                  <button @mouseenter="showTooltip = formation.id" @mouseleave="showTooltip = null" class="ml-1 flex-shrink-0">
                    <InformationCircleIcon :class="[
                      'h-4 w-4 text-gray-400',
                      `hover:text-${themeStore.currentTheme.button}`
                    ]" />
                  </button>
                  <div v-if="showTooltip === formation.id" 
                       :class="[
                         'absolute right-0 top-6 px-2 py-1 rounded shadow-lg text-xs max-w-[200px] break-words z-10',
                         themeStore.getThemeClasses('background', 'light')
                       ]">
                    {{ getInstitutionFullName(formation.type_etablissement) }}
                  </div>
                </p>
                <p class="flex items-start text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-4 w-4 mr-2 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.diploma }}</span>
                </p>
                <p class="flex items-start text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-4 w-4 mr-2 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.ville }}</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Desktop View -->
        <div class="hidden lg:block">
          <div class="p-6">
            <div class="flex flex-col space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 tracking-tight break-words flex-1 mr-4">{{ formation.nom }}</h3>
                <span :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium flex-shrink-0',
                  `bg-${themeStore.currentTheme.button}/10`,
                  `text-${themeStore.currentTheme.button}`
                ]">
                  {{ formation.niveau }}
                </span>
              </div>

              <div class="space-y-2">
                <p class="text-gray-600 flex items-start gap-2 relative">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-5 w-5 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.type_etablissement }}</span>
                  <button @click="showTooltip = formation.id" @mouseleave="showTooltip = null" class="flex-shrink-0">
                    <InformationCircleIcon :class="[
                      'h-5 w-5 text-gray-400',
                      `hover:text-${themeStore.currentTheme.button}`
                    ]" />
                  </button>
                  <div v-if="showTooltip === formation.id" 
                       :class="[
                         'absolute right-0 top-8 px-3 py-2 rounded-lg shadow-lg text-sm max-w-[300px] break-words z-10',
                         themeStore.getThemeClasses('background', 'light')
                       ]">
                    {{ getInstitutionFullName(formation.type_etablissement) }}
                  </div>
                </p>
                <p class="text-gray-600 flex items-start gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-5 w-5 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.diploma }}</span>
                </p>
                <p class="text-gray-600 flex items-start gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" :class="[`text-${themeStore.currentTheme.button}`, 'h-5 w-5 flex-shrink-0 mt-1']" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                  </svg>
                  <span class="break-words flex-1">{{ formation.ville }}</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { EnvelopeIcon, PhoneIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'
import { InstitutionTypes } from '@/Enums/InstitutionTypes'
import EmptyState from './EmptyState.vue'
import { useThemeStore } from '@/stores/theme/themeStore'

const themeStore = useThemeStore()

const props = defineProps({
  formations: {
    type: Array,
    required: true,
  },
})

const showTooltip = ref(null)

const getInstitutionFullName = (shortName) => {
  return InstitutionTypes[shortName] || shortName
}

defineEmits(['clear-filters'])
</script>
