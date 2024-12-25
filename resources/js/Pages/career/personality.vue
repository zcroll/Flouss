<template>

  <Head title="Personality Traits" />
  <StickySidebar type="career" :model="occupation">
    <template #description>
      Discover the key personality traits and characteristics that make successful {{ occupation.name }}s.
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <Breadcrumbs :items="[
        { name: 'Home', route: 'dashboard' },
        { name: 'Jobs', route: 'jobs.index' },
        { name: occupation.name, route: 'career', params: { id: occupation.id } },
        { name: 'Personality' }
      ]" class="mb-8" />

      <!-- Introduction -->
      <section class="space-y-6">
        <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
          {{ __('career.we_surveyed') }} {{ occupation.name.toLowerCase() }}s {{
            __('career.to_learn_what_personality_traits')
          }}
        </h2>
      </section>

      <!-- Table of Contents -->
      <aside class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
          {{ __('career.in_this_article') }}
        </h3>
        <nav class="space-y-2">
          <a href="#holland-codes" class="block transition-colors"
            :class="[themeStore.isDarkMode ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900']">
            {{ __('career.primary_interests') }}
          </a>
          <a href="#big-five" class="block transition-colors"
            :class="[themeStore.isDarkMode ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900']">
            {{ __('career.broad_personality_traits') }}
          </a>
        </nav>
      </aside>

      <!-- Holland Codes Section -->
      <section id="holland-codes" class="space-y-6">
        <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
          {{ occupation.name }}s {{ __('career.are') }} <em class="font-normal"
            :class="`text-${themeStore.currentTheme.primary}-600`">{{ getTopHollandTraits }}</em>
        </h2>
        <p :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'">
          {{ personalityDetails.find(d => d.trait_type === 'Holland Codes')?.description }}
        </p>

        <div class="space-y-4">
          <div v-for="trait in hollandCodeTraits" :key="trait.id"
            class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm p-6">
            <button @click="setActiveDefinition(trait.id)" class="w-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}`) }}
                </h3>
                <div :class="`text-${themeStore.currentTheme.primary}-500`">
                  <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': activeDefinition === trait.id }"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </div>

              <div class="relative h-8 rounded-full overflow-hidden"
                :class="`bg-${themeStore.currentTheme.primary}-100`">
                <div :class="`bg-${themeStore.currentTheme.button}`" class="absolute inset-y-0 left-0"
                  :style="`width: ${trait.trait_score * 100}%`">
                  <span class="absolute inset-0 flex items-center justify-end pr-4 text-white font-medium">
                    {{ trait.trait_score * 100 }}%
                  </span>
                </div>
              </div>
            </button>

            <div v-if="activeDefinition === trait.id" class="mt-4 p-4 rounded-xl ring-1 ring-white/20"
              :class="[themeStore.isDarkMode ? 'bg-gray-800 text-gray-300' : 'bg-gray-50 text-gray-600']">
              <p>{{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}_definition`) }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Big Five Section -->
      <section id="big-five" class="space-y-6">
        <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
          {{ __('career.top_personality_traits_of') }} {{ occupation.name.toLowerCase() }}s {{ __('career.are') }}
          <em class="font-normal" :class="`text-${themeStore.currentTheme.primary}-600`">{{ getTopBigFiveTraits }}</em>
        </h2>
        <p :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'">
          {{ personalityDetails.find(d => d.trait_type === 'Big Five')?.description }}
        </p>

        <div class="space-y-4">
          <div v-for="trait in bigFiveTraits" :key="trait.scale_id"
            class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm p-6">
            <button @click="setActiveDefinition(trait.scale_id)" class="w-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ __(trait.short_name) }}
                </h3>
                <div :class="`text-${themeStore.currentTheme.primary}-500`">
                  <svg class="w-5 h-5 transition-transform"
                    :class="{ 'rotate-180': activeDefinition === trait.scale_id }" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </div>

              <div class="relative h-8 rounded-full overflow-hidden"
                :class="`bg-${themeStore.currentTheme.primary}-100`">
                <div :class="`bg-${themeStore.currentTheme.button}`" class="absolute inset-y-0 left-0"
                  :style="`width: ${trait.value * 100}%`">
                  <span class="absolute inset-0 flex items-center justify-end pr-4 text-white font-medium">
                    {{ trait.value * 100 }}%
                  </span>
                </div>
              </div>
            </button>

            <div v-if="activeDefinition === trait.scale_id" class="mt-4 p-4 rounded-xl ring-1 ring-white/20"
              :class="[themeStore.isDarkMode ? 'bg-gray-800 text-gray-300' : 'bg-gray-50 text-gray-600']">
              <p>{{ __(trait.definition) }}</p>
            </div>
          </div>
        </div>
      </section>
    </div>

    <BackToTop />
  </StickySidebar>
</template>

<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/vue3';
import BackToTop from "@/Components/helpers/BackToTop.vue";
import __ from '@/lang';
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import { useThemeStore } from '@/stores/theme/themeStore';

defineOptions({ layout: MainLayout });

const themeStore = useThemeStore();

const props = defineProps({
  occupation: {
    type: Object,
    required: true,
    validator: (obj) => {
      return ['id', 'slug', 'name', 'image'].every(prop => prop in obj);
    }
  },
  personalityTraits: {
    type: Array,
    required: true,
  },
  personalityDetails: {
    type: Array,
    required: true,
  },
});

const activeDefinition = ref(null);

const setActiveDefinition = (traitId) => {
  if (activeDefinition.value === traitId) {
    activeDefinition.value = null;
  } else {
    activeDefinition.value = traitId;
  }
};

const formatTraitName = (traitName) => {
  if (!traitName) return '';
  return traitName.replace('Interest in ', '').replace(' Jobs', '');
};

const hollandCodeTraits = computed(() => {
  return props.personalityTraits.filter(trait => trait.trait_type === 'Holland Codes');
});

const bigFiveTraits = computed(() => {
  return props.personalityTraits.filter(trait => trait.trait_type === 'Big Five')
    .map(trait => ({
      name: trait.trait_name,
      short_name: `career.${trait.trait_name.toLowerCase()}`,
      scale_id: trait.id,
      definition: `career.${trait.trait_name.toLowerCase()}_definition`,
      value: trait.trait_score
    }));
});

const getTopHollandTraits = computed(() => {
  const top2 = hollandCodeTraits.value
    .sort((a, b) => b.trait_score - a.trait_score)
    .slice(0, 2)
    .map(t => formatTraitName(t.trait_name).toLowerCase());
  return `${top2[0]} and ${top2[1]}`;
});

const getTopBigFiveTraits = computed(() => {
  const top2 = bigFiveTraits.value
    .sort((a, b) => b.value - a.value)
    .slice(0, 2)
    .map(t => t.short_name);
  return `${top2[0]} and ${top2[1]}`;
});

const getTraitDefinition = (traitName) => {
  const definitions = {
    'career.realistic': 'career.realistic_definition',
    'career.artistic': 'career.artistic_definition',
    'career.investigative': 'career.investigative_definition',
    'career.social': 'career.social_definition',
    'career.enterprising': 'career.enterprising_definition',
    'career.conventional': 'career.conventional_definition'
  };

  return definitions[traitName] || '';
};
</script>

<style scoped>
/* Progress bars */
.progress-bar {
  @apply relative h-8 bg-gray-100 rounded-full overflow-hidden;
}

.progress-bar-fill {
  @apply absolute inset-y-0 left-0;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}
</style>
