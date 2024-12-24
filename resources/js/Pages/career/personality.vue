<template>
  <Head title="Personality Traits" />
  <StickySidebar
    :slug="occupation.slug"
    :title="occupation.name"
    :image="occupation.image"
    type="career"
    :salary="occupation.salary"
    :personality="occupation.personality || 'N/A'"
    :satisfaction="occupation.satisfaction || 'N/A'"
    :id="occupation.id"
    :isFavorited="occupation.is_favorited"
  >
    <template #description>
      Discover the key personality traits and characteristics that make successful {{ occupation.name }}s.
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <Breadcrumbs 
        :items="[
          { name: 'Home', route: 'dashboard' },
          { name: 'Jobs', route: 'jobs.index' },
          { name: occupation.name, route: 'career', params: { id: occupation.id } },
          { name: 'Personality' }
        ]"
        class="mb-8"
      />

      <!-- Introduction -->
      <section class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{ __('career.we_surveyed') }} {{occupation.name.toLowerCase()}}s {{ __('career.to_learn_what_personality_traits') }}
        </h2>
      </section>

      <!-- Table of Contents -->
      <aside class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
          {{ __('career.in_this_article') }}
        </h3>
        <nav class="space-y-2">
          <a href="#holland-codes" 
             class="block text-gray-600 hover:text-yellow-500 transition-colors">
            {{ __('career.primary_interests') }}
          </a>
          <a href="#big-five" 
             class="block text-gray-600 hover:text-yellow-500 transition-colors">
            {{ __('career.broad_personality_traits') }}
          </a>
        </nav>
      </aside>

      <!-- Holland Codes Section -->
      <section id="holland-codes" class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{occupation.name}}s {{ __('career.are') }} <em class="text-yellow-500 font-normal">{{getTopHollandTraits}}</em>
        </h2>
        <p class="text-gray-600">
          {{ personalityDetails.find(d => d.trait_type === 'Holland Codes')?.description }}
        </p>

        <div class="space-y-4">
          <div v-for="trait in hollandCodeTraits" 
               :key="trait.id"
               class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
            <button 
              @click="setActiveDefinition(trait.id)"
              class="w-full"
            >
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                  {{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}`) }}
                </h3>
                <div class="text-gray-400">
                  <svg 
                    class="w-5 h-5 transition-transform duration-200"
                    :class="{ 'rotate-180': activeDefinition === trait.id }"
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                  >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>

              <div class="relative h-8 bg-gray-100 rounded-full overflow-hidden">
                <div 
                  class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-400 to-yellow-500 transition-all duration-1000"
                  :style="{ width: `${Math.round(trait.trait_score * 100)}%` }"
                >
                  <span class="absolute inset-0 flex items-center justify-end pr-4 text-white font-medium">
                    {{ Math.round(trait.trait_score * 100) }}%
                  </span>
                </div>
              </div>
            </button>

            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-200 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <div v-if="activeDefinition === trait.id" 
                   class="mt-4 p-4 bg-gray-50 rounded-xl text-gray-600">
                <p>{{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}_definition`) }}</p>
              </div>
            </transition>
          </div>
        </div>
      </section>

      <!-- Big Five Section -->
      <section id="big-five" class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{ __('career.top_personality_traits_of') }} {{occupation.name.toLowerCase()}}s {{ __('career.are') }} 
          <em class="text-yellow-500 font-normal">{{__(getTopBigFiveTraits)}}</em>
        </h2>
        <p class="text-gray-600">
          {{ personalityDetails.find(d => d.trait_type === 'Big Five')?.description }}
        </p>

        <div class="space-y-4">
          <div v-for="trait in bigFiveTraits" 
               :key="trait.scale_id"
               class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
            <button 
              @click="setActiveDefinition(trait.scale_id)"
              class="w-full"
            >
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                  {{ __(trait.short_name) }}
                </h3>
                <div class="text-gray-400">
                  <svg 
                    class="w-5 h-5 transition-transform duration-200"
                    :class="{ 'rotate-180': activeDefinition === trait.scale_id }"
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                  >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>

              <div class="relative h-8 bg-gray-100 rounded-full overflow-hidden">
                <div 
                  class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-400 to-yellow-500 transition-all duration-1000"
                  :style="{ width: `${Math.round(trait.value * 100)}%` }"
                >
                  <span class="absolute inset-0 flex items-center justify-end pr-4 text-white font-medium">
                    {{ Math.round(trait.value * 100) }}%
                  </span>
                </div>
              </div>
            </button>

            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-200 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <div v-if="activeDefinition === trait.scale_id" 
                   class="mt-4 p-4 bg-gray-50 rounded-xl text-gray-600">
                <p>{{ __(trait.definition) }}</p>
              </div>
            </transition>
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
defineOptions({ layout: MainLayout });

const props = defineProps({
  occupation: {
    type: Object,
    required: true,
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
  return [
    {
      name: "AMBI-NEO-Conscientiousness",
      short_name: 'career.conscientiousness',
      scale_id: 508,
      definition: 'career.conscientiousness_definition',
      value: 0.663973684210526
    },
    {
      name: "AMBI-HEXACO-Honesty",
      short_name: 'career.social_responsibility',
      scale_id: 509,
      definition: 'career.social_responsibility_definition',
      value: 0.586618421052632
    },
    {
      name: "AMBI-NEO-Extraversion",
      short_name: 'career.extraversion',
      scale_id: 505,
      definition: 'career.extraversion_definition',
      value: 0.44247368421052596
    },
    {
      name: "AMBI-NEO-Openness",
      short_name: 'career.openness',
      scale_id: 506,
      definition: 'career.openness_definition',
      value: 0.416684210526316
    },
    {
      name: "AMBI-NEO-Agreeableness",
      short_name: 'career.agreeableness',
      scale_id: 507,
      definition: 'career.agreeableness_definition',
      value: 0.373197368421053
    }
  ];
});

const getTopHollandTraits = computed(() => {
  const top2 = hollandCodeTraits.value
    .sort((a,b) => b.trait_score - a.trait_score)
    .slice(0,2)
    .map(t => formatTraitName(t.trait_name).toLowerCase());
  return `${top2[0]} and ${top2[1]}`;
});

const getTopBigFiveTraits = computed(() => {
  const top2 = bigFiveTraits.value
    .sort((a,b) => b.value - a.value)
    .slice(0,2)
    .map(t => t.short_name);
  return `${__('career.conscientiousness')} and ${__('career.social_responsibility')}`;
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
/* Animations */
section {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Progress bars */
.progress-bar {
  @apply relative h-8 bg-gray-100 rounded-full overflow-hidden;
}

.progress-bar-fill {
  @apply absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-400 to-yellow-500 transition-all duration-1000;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Button hover effects */
button:hover .text-gray-400 {
  @apply text-yellow-500;
}

/* Definition panels */
.definition-panel {
  @apply mt-4 p-4 bg-gray-50 rounded-xl text-gray-600;
}
</style>
