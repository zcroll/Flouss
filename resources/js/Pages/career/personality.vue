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
      <link type="text/css" rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/careers.098b42e5fd20.css">
      <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <Link :href="route('dashboard')" class="hover:text-blue-600 transition-colors">{{ __('Home') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('jobs.index')" class="hover:text-blue-600 transition-colors">{{ __('Jobs') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('career', { id: occupation.slug })" class="hover:text-blue-600 transition-colors">{{ occupation.name }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-400">{{ __('Personality') }}</span>
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

<style scoped>
.standard-link {
  font-family: "aktiv-grotesk","Helvetica Neue",Helvetica,Arial,sans-serif !important;
  color: #53777a;
  font-weight: 500;
  border-bottom: 2px solid #53777a;
  transition: color 0.2s ease-in-out, border-bottom 0.2s ease-in-out;
}

.standard-link:hover {
  color: #53777a;
  border-bottom: 2px solid #53777a;
}
html {
  box-sizing: border-box;
  overflow-x: hidden;
  color: rgb(36, 36, 36);
  font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
  line-height: 1.6;
  font-size: 16px;
}

body {
  box-sizing: border-box;
  margin: 0px;
}

.layout--sidebar__body__main {
  box-sizing: "border-box";
  display: "block";
  grid-column: "1";
}
.svg-inline--fa{display:var(--fa-display, inline-block);height:1em;overflow:visible;vertical-align:-.125em}
article {
  box-sizing: "border-box";
  display: "block";
}

section {
  box-sizing: "border-box";
  display: "block";
}

.lead {
  box-sizing: "border-box";
  margin-block: "1.5em";
  font-size: "1.55rem";
  margin-bottom: "0px";
  line-height: 1.3;
  letter-spacing: "-0.4px";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#table-of-contents-container {
  box-sizing: "border-box";
  display: "block";
}

#table-of-contents {
  box-sizing: "border-box";
  border-radius: "10px";
  margin: "1rem 0px 0px";
  width: "100%";
  background-color: rgb(248, 248, 241);
  color: rgb(36, 36, 36);
  position: "relative";
  padding: "37px 42px";
}

#table-of-contents h3 {
  box-sizing: "border-box";
  margin: "0px 0px 10px";
  font-weight: 300;
  padding-top: "0px";
  letter-spacing: "-0.3px";
  font-size: "32px";
  line-height: "36px";
  margin-bottom: "30px";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#table-of-contents ol {
  box-sizing: "border-box";
  margin-block: "1.5em";
  padding: "0px";
  overflow: "hidden";
  transition: "0.35s";
  margin: "0px 0px 8px";
  font-size: "16px";
  line-height: "26px";
  list-style-type: "none";
  counter-reset: "elementcounter 0";
  padding-left: "0px";
  margin-left: "0px";
  font-weight: 300;
  letter-spacing: "-0.3px";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#table-of-contents li {
  box-sizing: "border-box";
  list-style: "none";
  position: "relative";
  padding-left: "0px";
  letter-spacing: "-0.3px";
  font-size: "24px";
  line-height: "39px";
  margin-bottom: "0px";
}

#table-of-contents a {
  box-sizing: "border-box";
  background-color: "transparent";
  text-decoration: "none";
  transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out";
  border-bottom: "0px";
  color: rgb(36, 36, 36);
  font-weight: 300;
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

br {
  box-sizing: "border-box";
}

#holland-codes {
  box-sizing: "border-box";
  display: "block";
}

.info {
  box-sizing: "border-box";
  color: rgba(36, 36, 36, 0.7);
  font-size: "16px";
  font-weight: 800;
  cursor: "pointer";
}

.info__icon {
  box-sizing: "border-box";
}

.svg-inline--fa {
  display: "var(--fa-display, inline-block)";
  height: "1em";
  vertical-align: "-0.125em";
  overflow: "visible";
  box-sizing: "content-box";
}

#holland-codes h2 {
  box-sizing: "border-box";
  line-height: 1.16;
  letter-spacing: "-0.7px";
  font-weight: 300;
  font-size: "1.55em";
  margin-bottom: "0px";
  padding-top: "0px";
  margin-top: "0px";
}

#holland-codes em {
  box-sizing: "border-box";
}

#holland-codes p {
  box-sizing: "border-box";
  margin-block: "1.5em";
  letter-spacing: "-0.01em";
  font-size: "1rem";
  line-height: "1.6em";
  font-family: '"pt-serif", serif';
}

#holland-codes strong {
  box-sizing: "border-box";
  font-weight: "bolder";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#holland-codes a {
  box-sizing: "border-box";
  background-color: "transparent";
  text-decoration: "none";
  border-bottom: "2px solid rgb(83, 119, 122)";
  transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out";
  color: rgb(83, 119, 122);
  font-weight: 500;
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#personality-holland-codes-container {
  box-sizing: "border-box";
}

.PersonalityGraphs {
  box-sizing: "border-box";
}

.Box {
  box-sizing: "border-box";
  text-decoration: "none";
  border-radius: "7px";
  background-color: rgb(255, 255, 255);
  box-shadow: rgba(0, 0, 0, 0.11) 0px 0px 12px 0px;
  display: "block";
  margin-bottom: "24px";
  padding: "20px 16px";
  transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out";
  font-weight: 500;
  border-bottom: "medium";
  color: rgb(34, 34, 34);
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

.PersonalityGraphs__scale {
  box-sizing: "border-box";
  display: "flex";
  align-items: "center";
}

.PersonalityGraphs__scale__name {
  box-sizing: "border-box";
  flex: "0 1 0%";
  font-weight: 300;
  hyphens: "auto";
  line-height: 1.2;
  font-size: "18px";
  min-width: "195px";
}

.sr-only {
  box-sizing: "border-box";
  padding: "0px";
  margin: "-1px";
  white-space: "nowrap";
  border-width: "0px";
  clip: "rect(0px, 0px, 0px, 0px)";
  overflow: "hidden";
  position: "absolute";
  left: "-10000px";
  width: "1px";
  height: "1px";
}

.PersonalityGraphs__scale__graph {
  box-sizing: "border-box";
  display: "block";
  margin: "0px";
  flex: "1 1 0%";
}

.BarMeter--gradient-dusk {
  box-sizing: "border-box";
  background: "transparent";
  border: "0px transparent";
  display: "inline-block";
  position: "relative";
  box-shadow: "none";
  width: "100%";
  max-width: "100%";
}

.BarMeter__meter {
  box-sizing: "border-box";
  border-radius: "12.5px";
  display: "block";
  min-width: "65px";
  max-width: "100%";
  position: "absolute";
  top: "0px";
  height: "34px";
  box-shadow: rgba(22, 22, 22, 0.25) 0px 2px 12px 0px;
  background-color: "transparent";
  background-image: "linear-gradient(-90deg, rgb(222, 176, 85) 0%, rgb(163, 111, 178) 50%, rgb(70, 44, 103) 100%)";
  background-size: "120%";
  transition: "width 0.68s linear, min-width 0.68s linear";
}

.PersonalityGraphs__scale__toggle {
  box-sizing: "border-box";
  color: rgb(177, 177, 177);
  padding-left: "16px";
}

.fa-chevron-down {
  display: "var(--fa-display, inline-block)";
  height: "1em";
  font-size: "0.875em";
  line-height: "0.07143em";
  vertical-align: "-0.07143em";
  overflow: "visible";
  box-sizing: "content-box";
}

.fa-chevron-down path {
  box-sizing: "border-box";
}


/* ... (rest of the styles, following the same pattern) ... */

.UpNextCard__art {
  border-style: none;
  width: 100%;
  max-height: fit-content;
  box-sizing: border-box;
}

.Feedback__submit {
  box-sizing: border-box;
}

.CareerSectionFeedback {
  box-sizing: border-box;
  padding: "8px 0px";
  cssFloat: "right";
  position: "relative";
}

.CareerSectionFeedback-text {
  box-sizing: border-box;
  font-weight: 400;
  font-size: "16px";
  margin-right: "8px";
}

.CareerSectionFeedback-button {
  box-sizing: border-box;
  margin: "0px";
  overflow: "visible";
  text-transform: "none";
  appearance: "button";
  border-radius: "8px";
  transition: "0.4s";
  position: "relative";
  display: "inline-flex";
  flex-direction: "row";
  justify-content: "center";
  font-size: "16px";
  font-weight: 500;
  line-height: 1.2;
  text-align: "center";
  cursor: "pointer";
  border: "1px solid rgb(177, 177, 177)";
  background-color: "white";
  color: rgb(36, 36, 36);
  padding: "8px 4px";
  margin-right: "8px";
  vertical-align: "middle";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

.CareerSectionFeedback-button__icon {
  display: "var(--fa-display, inline-block)";
  height: "1em";
  vertical-align: "-0.125em";
  margin: "0px 5px";
  font-size: "1.1em";
  overflow: "visible";
  box-sizing: "content-box";
}

.fa-duotone-group {
  box-sizing: "border-box";
}

.fa-secondary {
  box-sizing: "border-box";
  fill: "var(--fa-secondary-color, currentColor)";
  opacity: "var(--fa-secondary-opacity, 0.4)";
}

.fa-primary {
  box-sizing: "border-box";
  fill: "var(--fa-primary-color, currentColor)";
  opacity: "var(--fa-primary-opacity, 1)";
}

.up-next {
  box-sizing: "border-box";
  display: "block";
}

#up-next__title {
  box-sizing: "border-box";
  line-height: 1.16;
  letter-spacing: "-0.7px";
  font-weight: 300;
  font-size: "1.55em";
  margin-bottom: "0px";
  padding-top: "calc(1.5em + 0.5rem)";
}

#up-next-container {
  box-sizing: "border-box";
}

.UpNextCard {
  box-sizing: "border-box";
  overflow: "hidden";
  transition: "transform 0.5s, box-shadow 0.5s";
  margin: "1em 0px 3em";
  border-radius: "24px";
  position: "relative";
  color: rgb(255, 255, 255);
  flex-direction: "row";
  grid-template-columns: "66% max-content";
  grid-template-rows: "auto";
  display: "flex";
  background-color: rgb(22, 22, 22);
  box-shadow: rgba(22, 22, 22, 0.2) 0px 12px 56px 0px, rgba(0, 0, 0, 0.15) 0px 12px 24px 0px;
}

.UpNextCard__copy {
  box-sizing: "border-box";
  flex: "2 1 0%";
  min-width: "66%";
  display: "flex";
  flex-direction: "column";
  justify-content: "center";
  padding: "48px 12px 48px 44px";
}

.UpNextCard__title {
  box-sizing: "border-box";
  letter-spacing: "-0.5px";
  font-weight: 300;
  color: rgb(255, 255, 255);
  padding-top: "0px";
  margin: "0px 0px 40px";
  font-size: "36px";
  line-height: "41px";
  margin-top: "0px";
  margin-bottom: "40px";
}

.UpNextCard__description {
  box-sizing: "border-box";
  margin-block: "1.5em";
  letter-spacing: "-0.3px";
  color: rgb(255, 255, 255);
  margin-bottom: "16px";
  display: "-webkit-box";
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: "hidden";
  font-size: "16px";
  line-height: "32px";
  margin-top: "0px";
  max-height: "63px";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

.UpNextCard__cta {
  box-sizing: "border-box";
  background-color: "transparent";
  text-decoration: "none";
  font-size: "14px";
  line-height: "16px";
  letter-spacing: "1px";
  text-transform: "uppercase";
  display: "inline";
  margin-top: "30px";
  transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out";
  font-weight: 500;
  border-bottom: "1px solid white";
  color: rgb(255, 255, 255);
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

#back-top-container {
  box-sizing: "border-box";
  height: "0px";
  text-align: "right";
  position: "sticky";
  bottom: "10vh";
  width: "calc(100% + 64px)";
  margin-bottom: "60px";
}

.back-to-top {
  box-sizing: "border-box";
}

.BackToTop {
  box-sizing: "border-box";
  text-decoration: "none";
  border-radius: "50%";
  width: "44px";
  height: "44px";
  margin-right: "1px";
  background-color: rgb(242, 242, 242);
  cursor: "pointer";
  margin-left: "auto";
  text-transform: "uppercase";
  font-size: "10px";
  display: "flex";
  flex-direction: "column";
  align-items: "center";
  justify-content: "center";
  text-align: "center";
  transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out";
  color: rgb(83, 119, 122);
  font-weight: 500;
  border-bottom: "0px";
  font-family: '"aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif';
}

.fa-chevron-up {
  display: "var(--fa-display, inline-block)";
  height: "1em";
  vertical-align: "-0.125em";
  font-size: "2em";
  margin-bottom: "-8px";
  color: rgb(177, 177, 177);
  overflow: "visible";
  box-sizing: "content-box";
}

.fa-chevron-up path {
  box-sizing: "border-box";
}

.fa-circle-question {
  display: "var(--fa-display, inline-block)";
  height: "1em";
  vertical-align: "-0.125em";
  overflow: "visible";
  box-sizing: "content-box";
}

.fa-circle-question path {
  box-sizing: "border-box";
}

.BarMeter__meter:nth-child(1) {
  width: 71%;
}

.BarMeter__meter:nth-child(2) {
  width: 51%;
}

.BarMeter__meter:nth-child(3) {
  width: 41%;
}

.BarMeter__meter:nth-child(4) {
  width: 36%;
}

.BarMeter__meter:nth-child(5) {
  width: 24%;
}

.BarMeter__meter:nth-child(6) {
  width: 17%;
}

.BarMeter__meter:nth-child(7) {
  width: 66%;
}

.BarMeter__meter:nth-child(8) {
  width: 59%;
}

.BarMeter__meter:nth-child(9) {
  width: 44%;
}

.BarMeter__meter:nth-child(10) {
  width: 42%;
}

.BarMeter__meter:nth-child(11) {
  width: 37%;
}
</style>
