<template>
  <div v-if="hasResult" class="lg:col-span-2 bg-stone-950 rounded-2xl p-6 border border-stone-700">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.your_assessment_results') }}</h2>
      <Link href="/results"
        class="px-3 py-1.5 text-sm bg-white hover:bg-stone-300/30 hover:text-white text-stone-900 rounded-lg transition-colors duration-200 w-auto text-center">
        {{ __('dashboard.view_full_results') }}
      </Link>
    </div>

    <!-- Archetype Card -->
    <div 
      class="bg-[#353535] rounded-2xl p-4 sm:p-8 mb-6 shadow-2xl hover:shadow-amber-500/5 transition-all duration-500 border border-stone-700/50 relative overflow-hidden"
      :style="{
        backgroundImage: `url('https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607545512/dashboard-sharing-bg-lg_ngkdfq.png')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center'
      }">
      <div class="absolute top-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-amber-500/5 rounded-full blur-3xl -mr-24 sm:-mr-32 -mt-24 sm:-mt-32"></div>
      <div class="absolute bottom-0 left-0 w-48 sm:w-64 h-48 sm:h-64 bg-stone-600/5 rounded-full blur-3xl -ml-24 sm:-ml-32 -mb-24 sm:-mb-32"></div>

      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6 relative">
        <div class="flex items-center gap-4">
          <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
              <h3 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent tracking-tight hover:scale-105 transition-transform duration-300">
                {{ archetype?.slug || 'No Archetype' }}
              </h3>
              <span class="px-2 sm:px-3 py-1 text-xs font-semibold text-amber-400 bg-amber-400/10 rounded-full border border-amber-400/20">
                {{ archetype?.rarity_string || 'Unknown' }}
              </span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-3 h-3 sm:w-4 sm:h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
              <span class="text-stone-400 text-xs sm:text-sm font-medium">Personality Type</span>
            </div>
            <p class="text-sm sm:text-base text-stone-300 font-bold leading-relaxed tracking-wide">
              {{ archetype?.rationale || 'No rationale available' }}
            </p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 pt-4 sm:pt-6">
        <div v-for="(score, trait) in topTraits" :key="trait"
          class="bg-black rounded-xl p-3 sm:p-6 transition-all duration-300 border border-stone-700 hover:border-amber-500/30 group">
          <div class="flex justify-between items-center mb-3 sm:mb-4">
            <h4 class="text-base sm:text-lg font-semibold text-stone-100 tracking-wide">
              {{ trait }}
            </h4>
            <span class="text-lg sm:text-xl font-bold text-amber-400 bg-amber-400/10 px-2 sm:px-3 py-0.5 sm:py-1 rounded-lg">
              {{ Math.round(score * 100) }}%
            </span>
          </div>
          <div class="w-full bg-stone-800 rounded-full h-4 sm:h-5 mb-3 sm:mb-4 overflow-hidden">
            <div class="bg-amber-500 h-4 sm:h-5 rounded-full transition-all duration-700 ease-out"
              :style="{ width: `${Math.round(score * 100)}%` }">
            </div>
          </div>
          <div @click="toggleDescription(trait)"
            class="cursor-pointer group-hover:bg-stone-800/50 rounded-lg p-2 sm:p-3 transition-colors">
            <p class="text-xs sm:text-sm leading-relaxed text-stone-300 font-medium tracking-wide group-hover:text-stone-200 transition-colors"
              :class="{ 'line-clamp-2': !expandedDescriptions[trait] }">
              {{ getTraitDescription(trait) }}
            </p>
            <span class="text-amber-400 text-xs sm:text-sm mt-1 sm:mt-2 hover:text-amber-300">
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
    type: Array,
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