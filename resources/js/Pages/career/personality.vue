<template>
  <AppLayout>
    <!-- <link rel="preload" href="https://use.typekit.net/wpm5wyy.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->

    <div class="layout--sidebar__body__main">

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
      <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow-2xl">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <Link :href="route('dashboard')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Home') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('jobs.index')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Jobs') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('career', { id: occupation.slug })" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ occupation.name }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-400 font-medium border-b-2 border-gray-400 transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Personality') }}</span>
            </nav>

          <section>
            <p class="lead text-gray-700 mb-8" tabindex="0">
              {{ __('career.we_surveyed') }} {{occupation.name.toLowerCase()}}s {{ __('career.to_learn_what_personality_traits') }}
            </p>
          </section>

          <aside id="table-of-contents-container" class="mb-12">
            <div class="TableOfContents" tabindex="0" role="directory" title="Table of contents">
              <h3>{{ __('career.in_this_article') }}</h3>
              <ol>
                <li><a href="#holland-codes" class="standard-link">{{ __('career.primary_interests') }}</a></li>
                <li><a href="#big-five" class="standard-link">{{ __('career.broad_personality_traits') }}</a></li>
              </ol>
            </div>
          </aside>

          <!-- Holland Codes Section -->
          <section id="holland-codes" class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">
              {{occupation.name}}s {{ __('career.are') }} <em>{{getTopHollandTraits}}</em>
            </h2>
            <p class="text-gray-700 mb-8">
              {{ personalityDetails.find(d => d.trait_type === 'Holland Codes')?.description }}
            </p>
            <!-- <p class="text-gray-700 mb-8">
              If you are one or both of these archetypes, you may be well suited to be a {{occupation.name.toLowerCase()}}. However, if you are conventional, {{ __('career.probably_not_good_career_for_you') }}
              <a href="/career-test/" class="standard-link">{{ __('career.take_career_test_now') }}</a>.
            </p> -->

            <div id="personality-holland-codes-container" class="PersonalityGraphs">
              <a v-for="trait in hollandCodeTraits" :key="trait.id" class="Box" @click="setActiveDefinition(trait.id)">
                <div class="PersonalityGraphs__scale">
                  <div class="PersonalityGraphs__scale__name" :id="`${trait.id}-personalitygraph-scale-label`" >
                    {{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}`) }}
                  </div>
                  <span class="sr-only" :id="`${trait.id}-personalitygraph-scale-desc`" >
                    {{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}_definition`) }}
                  </span>
                  <figure class="PersonalityGraphs__scale__graph"
                          tabindex="0"
                          :aria-labelledby="`${trait.id}-personalitygraph-scale-label`"
                          :aria-describedby="`${trait.id}-personalitygraph-scale-desc`">
                    <span class="BarMeter--gradient-dusk" :data-value="Math.round(trait.trait_score * 100)">
                      <span class="BarMeter__meter" :style="{width: `${Math.round(trait.trait_score * 100)}%`}"></span>
                    </span>
                  </figure>
                  <div class="PersonalityGraphs__scale__toggle" >
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" :data-icon="activeDefinition === trait.id ? 'chevron-up' : 'chevron-down'"
                         class="svg-inline--fa fa-chevron-down fa-sm" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                      <path fill="currentColor" :d="activeDefinition === trait.id ?
                        'M416 352c-8.188 0-16.38-3.125-22.62-9.375L224 173.3l-169.4 169.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l192-192c12.5-12.5 32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25C432.4 348.9 424.2 352 416 352z' :
                        'M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z'">
                      </path>
                    </svg>
                  </div>
                </div>
                <div v-if="activeDefinition === trait.id" class="PersonalityGraphs__scale__definition">
                  <p>{{ __(`career.${formatTraitName(trait.trait_name).toLowerCase()}_definition`) }}</p>
                </div>
              </a>
            </div>
          </section>

          <!-- Big Five Section -->
          <section id="big-five" class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">
              {{ __('career.top_personality_traits_of') }} {{occupation.name.toLowerCase()}}s {{ __('career.are') }} <em>{{__(getTopBigFiveTraits)}}</em>
            </h2>
            <p class="text-gray-700 mb-8">
              {{ personalityDetails.find(d => d.trait_type === 'Big Five')?.description }}
            </p>

            <div id="personality-ambi-container" class="PersonalityGraphs">
              <a v-for="trait in bigFiveTraits" :key="trait.scale_id" class="Box" @click="setActiveDefinition(trait.scale_id)">
                <div class="PersonalityGraphs__scale">
                  <div class="PersonalityGraphs__scale__name" :id="`${trait.scale_id}-personalitygraph-scale-label`">
                    {{ __(trait.short_name)}}
                  </div>
                  <span class="sr-only" :id="`${trait.scale_id}-personalitygraph-scale-desc`">
                    {{ __(trait.definition) }}
                  </span>
                  <figure class="PersonalityGraphs__scale__graph"
                          tabindex="0"
                          :aria-labelledby="`${trait.scale_id}-personalitygraph-scale-label`"
                          :aria-describedby="`${trait.scale_id}-personalitygraph-scale-desc`">
                    <span class="BarMeter--gradient-dusk" :data-value="Math.round(trait.value * 100)">
                      <span class="BarMeter__meter" :style="{width: `${Math.round(trait.value * 100)}%`}"></span>
                    </span>
                  </figure>
                  <div class="PersonalityGraphs__scale__toggle">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" :data-icon="activeDefinition === trait.scale_id ? 'chevron-up' : 'chevron-down'"
                         class="svg-inline--fa fa-chevron-down fa-sm" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                      <path fill="currentColor" :d="activeDefinition === trait.scale_id ?
                        'M416 352c-8.188 0-16.38-3.125-22.62-9.375L224 173.3l-169.4 169.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l192-192c12.5-12.5 32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25C432.4 348.9 424.2 352 416 352z' :
                        'M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z'">
                      </path>
                    </svg>
                  </div>
                </div>
                <div v-if="activeDefinition === trait.scale_id" class="PersonalityGraphs__scale__definition">
                  <p>{{ __(trait.definition) }}</p>
                </div>
              </a>
            </div>
              <BackToTop />
          </section>


        </div>
      </StickySidebar>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/vue3';
import BackToTop from "@/Components/BackToTop.vue";
import __ from '@/lang';

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


@import '/public/css/career_personnality.css';

</style>