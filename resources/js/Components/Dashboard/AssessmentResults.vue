<template>
  <div v-if="hasResult" class="lg:col-span-2 bg-white rounded-xl p-6 border border-gray-200">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
      <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">{{ __('dashboard.your_assessment_results') }}</h2>
      <Link href="/results"
        class="px-3 py-1.5 text-sm bg-gray-900 hover:bg-gray-700 text-white rounded-xl transition-colors duration-200 w-auto text-center">
        {{ __('dashboard.view_full_results') }}
      </Link>
    </div>

    <!-- Archetype Card -->
    <div 
      class="bg-gray-50 rounded-2xl p-4 sm:p-8 mb-6 shadow-2xl hover:shadow-[#E05B42]/5 transition-all duration-500 border border-gray-200 relative overflow-hidden"
      :style="{
        backgroundImage: `url('https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607545512/dashboard-sharing-bg-lg_ngkdfq.png')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center'
      }">
      <div class="absolute top-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-[#E05B42]/5 rounded-full blur-3xl -mr-24 sm:-mr-32 -mt-24 sm:-mt-32"></div>
      <div class="absolute bottom-0 left-0 w-48 sm:w-64 h-48 sm:h-64 bg-gray-200/5 rounded-full blur-3xl -ml-24 sm:-ml-32 -mb-24 sm:-mb-32"></div>

      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6 relative">
        <div class="flex items-center gap-4">
          <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
              <h3 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-[#E05B42] to-[#F07B66] bg-clip-text text-transparent tracking-tight hover:scale-105 transition-transform duration-300">
                {{ archetype?.slug || 'No Archetype' }}
              </h3>
              <span class="px-2 sm:px-3 py-1 text-xs font-semibold text-[#E05B42] bg-[#E05B42]/10 rounded-full border border-[#E05B42]/20">
                {{ archetype?.rarity_string || 'Unknown' }}
              </span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-3 h-3 sm:w-4 sm:h-4 text-[#E05B42]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
              <span class="text-gray-600 text-xs sm:text-sm font-medium">{{ __('dashboard.personality_type') }}</span>
            </div>
            <p class="text-sm sm:text-base text-gray-700 font-bold leading-relaxed tracking-wide">
              {{ archetype?.rationale || 'No rationale available' }}
            </p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 pt-4 sm:pt-6">
        <div v-for="(score, trait) in topTraits" :key="trait"
          class="bg-white rounded-xl p-3 sm:p-6 transition-all duration-300 border border-gray-200 hover:border-[#E05B42]/30 group">
          <div class="flex justify-between items-center mb-3 sm:mb-4">
            <h4 class="text-base sm:text-lg font-semibold text-gray-900 tracking-wide">
              {{ trait }}
            </h4>
            <span class="text-lg sm:text-xl font-bold text-[#E05B42] bg-[#E05B42]/5 px-2 sm:px-3 py-0.5 sm:py-1 rounded-lg">
              {{ Math.round(score * 100) }}%
            </span>
          </div>
          <div class="w-full bg-gray-100 rounded-full h-4 sm:h-5 mb-3 sm:mb-4 overflow-hidden">
            <div class="bg-[#E05B42] h-4 sm:h-5 rounded-full transition-all duration-700 ease-out"
              :style="{ width: `${Math.round(score * 100)}%` }">
            </div>
          </div>
          <div @click="toggleDescription(trait)"
            class="cursor-pointer group-hover:bg-gray-50 rounded-lg p-2 sm:p-3 transition-colors">
            <p class="text-xs sm:text-sm leading-relaxed text-gray-700 font-medium tracking-wide group-hover:text-gray-900 transition-colors"
              :class="{ 'line-clamp-2': !expandedDescriptions[trait] }">
              {{ getTraitDescription(trait) }}
            </p>
            <span class="text-[#E05B42] text-xs sm:text-sm mt-1 sm:mt-2 hover:text-[#E05B42]/80">
              {{ expandedDescriptions[trait] ? '▲ Show less' : '▼ Read more' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  hasResult: {
    type: Boolean,
    required: true
  },
  archetype: {
    type: Object,
    default: null
  },
  topTraits: {
    type: Object,
    default: null
  }
});

const expandedDescriptions = ref({});

const traitDescriptions = {
  'Realistic': 'You have a strong preference for working with objects, tools, and machines. You enjoy hands-on problem solving and creating tangible results.',
  'Investigative': 'You are analytical, intellectual and scientific. You enjoy solving complex problems through observation and research.',
  'Artistic': 'You are creative, intuitive and expressive. You thrive in environments that allow for self-expression and innovative thinking.',
  'Social': 'You excel at helping, teaching, and counseling others. You are empathetic and enjoy working in collaborative environments.',
  'Enterprising': 'You excel at leading, persuading, and managing others. You are goal-oriented and enjoy taking initiative in business ventures and organizational projects.',
  'Conventional': 'You are detail-oriented, organized and methodical. You excel at following established procedures and working with data and numbers.'
};

const parsedScales = computed(() => {
  if (!props.archetype?.scales) return [];
  try {
    const scales = JSON.parse(props.archetype.scales);
    return scales;
  } catch (e) {
    console.error('Error parsing scales:', e);
    return [];
  }
});

function getTraitDescription(trait) {
  return traitDescriptions[trait] || 'Description not available';
}

function toggleDescription(id) {
  expandedDescriptions.value[id] = !expandedDescriptions.value[id];
}
</script>