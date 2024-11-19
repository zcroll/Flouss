
<template>
  <div v-if="hasresult" class="lg:col-span-2 bg-stone-950 rounded-2xl p-6 border border-stone-700">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.your_assessment_results') }}</h2>
      <Link href="/results"
        class="px-3 py-1.5 text-sm bg-white hover:bg-stone-300/30 hover:text-white text-stone-900 rounded-lg transition-colors duration-200 w-auto text-center">
        {{ __('dashboard.view_full_results') }}
      </Link>
    </div>

    <!-- Archetype Card -->
    <div class="bg-[#353535] rounded-2xl p-4 sm:p-8 mb-6 shadow-2xl hover:shadow-amber-500/5 transition-all duration-500 border border-stone-700/50 relative overflow-hidden"
      style="background-image: url('https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607545512/dashboard-sharing-bg-lg_ngkdfq.png'); background-size: cover; background-position: center;">
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
        <div v-for="(scale, index) in parsedScales" :key="scale.scale_id"
          class="bg-black rounded-xl p-3 sm:p-6 transition-all duration-300 border border-stone-700 hover:border-amber-500/30 group">
          <div class="flex justify-between items-center mb-2">
            <h4 class="text-sm sm:text-base font-medium text-stone-300">{{ scale.name }}</h4>
            <button @click="toggleDescription(scale.scale_id)"
              class="p-1 hover:bg-stone-800 rounded-lg transition-colors">
              <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
          <div class="relative pt-1">
            <div class="flex mb-2 items-center justify-between">
              <div>
                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-amber-600 bg-amber-200">
                  {{ scale.score }}%
                </span>
              </div>
            </div>
            <div class="flex h-2 mb-4 overflow-hidden text-xs rounded bg-stone-700">
              <div :style="{ width: `${scale.score}%` }"
                class="flex flex-col justify-center text-center text-white bg-amber-500 transition-all duration-500 ease-in-out">
              </div>
            </div>
          </div>
          <p v-show="scale.showDescription"
            class="text-xs sm:text-sm text-stone-400 mt-2 transition-all duration-300">
            {{ scale.description }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  hasresult: {
    type: Boolean,
    required: true
  },
  archetype: {
    type: Object,
    default: null
  }
});
console.log(props.hasResult);

const parsedScales = computed(() => {
  if (!props.archetype?.scales) return [];
  try {
    const scales = JSON.parse(props.archetype.scales);
    return scales.map(scale => ({
      ...scale,
      showDescription: false
    }));
  } catch (e) {
    console.error('Error parsing scales:', e);
    return [];
  }
});

function toggleDescription(scaleId) {
  const scale = parsedScales.value.find(s => s.scale_id === scaleId);
  if (scale) {
    scale.showDescription = !scale.showDescription;
  }
}
</script>