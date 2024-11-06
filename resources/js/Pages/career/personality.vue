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
      >
      <link type="text/css" rel="stylesheet" href="/css/career_personnality.css">
      <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
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
              We surveyed {{occupation.name.toLowerCase()}}s to learn what personality traits and interests make them unique. Here are the results.
            </p>
          </section>

          <aside id="table-of-contents-container" class="mb-12">
            <div class="TableOfContents" tabindex="0" role="directory" title="Table of contents">
              <h3>In this article:</h3>
              <ol>
                <li><a href="#holland-codes" class="standard-link">Primary interests (Holland Codes)</a></li>
                <li><a href="#big-five" class="standard-link">Broad personality traits (Big 5)</a></li>
              </ol>
            </div>
          </aside>

          <!-- Holland Codes Section -->
          <section id="holland-codes" class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">
              {{occupation.name}}s are <em>{{getTopHollandTraits}}</em>
            </h2>
            <p class="text-gray-700 mb-8">
              {{ personalityDetails.find(d => d.trait_type === 'Holland Codes')?.description }}
            </p>
            <!-- <p class="text-gray-700 mb-8">
              If you are one or both of these archetypes, you may be well suited to be a {{occupation.name.toLowerCase()}}. However, if you are conventional, this is probably not a good career for you.
              <a href="/career-test/" class="standard-link">Take the career test now</a>.
            </p> -->

            <div id="personality-holland-codes-container" class="PersonalityGraphs">
              <a v-for="trait in hollandCodeTraits" :key="trait.id" class="Box" @click="setActiveDefinition(trait.id)">
                <div class="PersonalityGraphs__scale">
                  <div class="PersonalityGraphs__scale__name" :id="`${trait.id}-personalitygraph-scale-label`" >
                    {{formatTraitName(trait.trait_name)}}
                  </div>
                  <span class="sr-only" :id="`${trait.id}-personalitygraph-scale-desc`" >
                    {{getTraitDefinition(formatTraitName(trait.trait_name))}}
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
                  <p>{{getTraitDefinition(formatTraitName(trait.trait_name))}}</p>
                </div>
              </a>
            </div>
          </section>

          <!-- Big Five Section -->
          <section id="big-five" class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">
              The top personality traits of {{occupation.name.toLowerCase()}}s are <em>{{getTopBigFiveTraits}}</em>
            </h2>
            <p class="text-gray-700 mb-8">
              {{ personalityDetails.find(d => d.trait_type === 'Big Five')?.description }}
            </p>

            <div id="personality-ambi-container" class="PersonalityGraphs">
              <a v-for="trait in bigFiveTraits" :key="trait.scale_id" class="Box" @click="setActiveDefinition(trait.scale_id)">
                <div class="PersonalityGraphs__scale">
                  <div class="PersonalityGraphs__scale__name" :id="`${trait.scale_id}-personalitygraph-scale-label`">
                    {{trait.short_name}}
                  </div>
                  <span class="sr-only" :id="`${trait.scale_id}-personalitygraph-scale-desc`">
                    {{trait.definition}}
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
                  <p>{{trait.definition}}</p>
                </div>
              </a>
            </div>
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
      short_name: "Conscientiousness",
      scale_id: 508,
      definition: "Conscientiousness is one's ability to master our impulses and act on a schedule. Individuals with a high conscientiousness score will find it easier to ignore urges and plan in advance; this also means they may have a difficult time with spontaneous or unexpected situations.",
      value: 0.663973684210526
    },
    {
      name: "AMBI-HEXACO-Honesty",
      short_name: "Social Responsibility",
      scale_id: 509,
      definition: "Social responsibility measures a person's desire to see fair outcomes and their general concern for the welfare of others. People who score high on this trait value equality and are generally not particularly experience-seeking.",
      value: 0.586618421052632
    },
    {
      name: "AMBI-NEO-Extraversion",
      short_name: "Extraversion",
      scale_id: 505,
      definition: "While most people associate extroversion with a love of other people, the reality is that this trait covers a much broader range of situations. A high extroversion level means that an individual need external stimulus to be happy; this can mean surrounding themselves with others or trying new experiences. Introverts tend to be able to satisfy themselves from within, and are therefore usually far more independent than extroverts.",
      value: 0.44247368421052596
    },
    {
      name: "AMBI-NEO-Openness",
      short_name: "Openness",
      scale_id: 506,
      definition: "Do you find yourself searching for new ideas? Do you enjoy talking about big, abstract ideas? If so, you may have a high openness to experience score. Some common traits of a high openness score include a desire for variety, high curiosity, and an active imagination.",
      value: 0.416684210526316
    },
    {
      name: "AMBI-NEO-Agreeableness",
      short_name: "Agreeableness",
      scale_id: 507,
      definition: "Agreeableness is the value which you place on ensuring that everyone gets along. Someone with a high agreeableness score will be more likely to try and understand the needs of other people, and looks for ways to ensure that everyone is satisfied; the downside of this being that their own needs and wants may be given a low priority.",
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
    .map(t => t.short_name.toLowerCase());
  return `${top2[0]} and ${top2[1]}`;
});

const getTraitDefinition = (traitName) => {
  const definitions = {
    'Realistic': 'Realistic people are practical, physical, hands-on problem solvers. They enjoy working outdoors and with tools/machines. They prefer concrete tasks over abstract thinking and often excel in mechanical or technical fields.',
    'Artistic': 'Artistic people are creative, intuitive and expressive. They enjoy working in unstructured situations using their imagination and originality. They tend to be independent and focused on creative pursuits.',
    'Investigative': 'Investigative people are analytical, intellectual and scientific. They enjoy research, mathematical or scientific activities and solving complex problems. They prefer working independently with data and ideas.',
    'Social': 'Social people are helpful, friendly and trustworthy. They enjoy working with and helping others. They prefer solving problems through discussions and have strong communication and teaching abilities.',
    'Enterprising': 'Enterprising people are energetic, ambitious and sociable. They enjoy leading, persuading others and taking risks. They prefer competitive business activities and leadership roles.',
    'Conventional': 'Conventional people are careful, structured and detail-oriented. They enjoy working with data, numbers and records. They prefer following established procedures and working in structured environments.'
  };

  return definitions[traitName] || '';
};
</script>
