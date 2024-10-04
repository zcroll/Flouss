<template>
  <!-- <AppLayout> -->
  <div>
    <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">
    <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/shared-discovery.4d94d358083a.css"> 
    <div class="SharedDiscoveryPage__wrap">
      <div id="shared-discovery-container">
        <div class="SharedDiscoveryPage">
          <div
            class="Discovery Discovery--no-accent"
            :style="{ backgroundImage: `url(${archetypeDiscovery.image_url})` }"
          >
            <h1 class="Discovery__title">
              I am {{ ArchetypeData.name.charAt(0).toLowerCase() === 'a' || ArchetypeData.name.charAt(0).toLowerCase() === 'e' || ArchetypeData.name.charAt(0).toLowerCase() === 'i' || ArchetypeData.name.charAt(0).toLowerCase() === 'o' || ArchetypeData.name.charAt(0).toLowerCase() === 'u' ? 'an' : 'a' }} <span class="">{{ ArchetypeData.name }}</span>
            </h1>
            <p class="Discovery__rationale">{{ archetypeDiscovery.rationale }}</p>
          </div>

          <section>
            <div class="Discovery__related">
              <p class="Discovery__related__lil-subheading">{{ archetypeDiscovery.verbose_description_header }}</p>
              <p class="Discovery__related__description">{{ archetypeDiscovery.verbose_description }}</p>
              <h3 class="Discovery__related__subheading">Things they're good at</h3>
              <p class="Discovery__related__description">{{ archetypeDiscovery.strengths_body }}</p>
              <h3 class="Discovery__related__subheading">Things they can watch out for</h3>
              <p class="Discovery__related__description">{{ archetypeDiscovery.weaknesses_body }}</p>
              <h3 class="Discovery__related__lil-subheading">The Science</h3>
              <p class="Discovery__related__description">{{ archetypeDiscovery.scales_descriptor }}</p>
              <div class="Discovery__related__content Discovery__related__content--scales">
                <h3 class="Discovery__related__lil-subheading" tabindex="0" aria-label="Below you will find a list of scores detailing where you match-up to chosen traits.">Their scores:</h3>
                <div class="PersonalityGraphs">
                  <a v-for="scale in JSON.parse(archetypeDiscovery.scales)" :key="scale.scale_id" href="#" class="Box">
                    <div class="PersonalityGraphs__scale">
                      <div class="PersonalityGraphs__scale__name" :id="`${scale.scale_id}-personalitygraph-scale-label`">{{ scale.scale_name }}</div>
                      <span class="sr-only" :id="`${scale.scale_id}-personalitygraph-scale-desc`">Your {{ scale.scale_name.toLowerCase() }} rates at {{ scale.percentile_score }} out of 100.</span>
                      <figure class="PersonalityGraphs__scale__graph" tabindex="0" :aria-labelledby="`${scale.scale_id}-personalitygraph-scale-label`" :aria-describedby="`${scale.scale_id}-personalitygraph-scale-desc`">
                        <span class="BarMeter--gradient-dusk" :data-value="Math.round(scale.percentile_score)">
                          <span class="BarMeter__meter" :style="{ width: `${Math.round(scale.percentile_score)}%` }">
                            <span class="BarMeter__label">{{ Math.round(scale.percentile_score) }}%</span>
                          </span>
                        </span>
                      </figure>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="Discovery__sidebar">
              <div>
                <img :src="'https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_480/v1568404399/ux8i7elvayqifan41pj8'" alt="CareerExplorer Assessment" class="">
                <div class="Discovery__sidebar__content">
                  <h3 class="">Your magic 🤩💫</h3>
                  <p class="">Are you {{ ArchetypeData.name.charAt(0).toLowerCase() === 'a' || ArchetypeData.name.charAt(0).toLowerCase() === 'e' || ArchetypeData.name.charAt(0).toLowerCase() === 'i' || ArchetypeData.name.charAt(0).toLowerCase() === 'o' || ArchetypeData.name.charAt(0).toLowerCase() === 'u' ? 'an' : 'a' }} {{ ArchetypeData.name }} {{ArchetypeData.personality_paragraph}}.</p>
                
                </div>
              </div>
            </div>
            <div class="Discovery__related">
              <h2 class="Discovery__related__subheading">Insights</h2>
              <div v-for="(insights, category) in Insights" :key="category">
                <h3 class="custom-h3">{{ formatCategory(category) }}</h3>
                <ul class="Discovery__related__description">
                  <li v-for="(insight, index) in insights" :key="index">
                    <span class="custom-li">{{ insight }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </section>
          
        </div>
      </div>
    </div>
  </div>
  <!-- </AppLayout> -->
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
  props: {
    Insights: {
      type: Object,
      required: true,
    },
    archetypeDiscovery: {
      type: Object,
      required: true,
    },
    ArchetypeData: {
      type: Object,
      required: true,
    },
  },
  methods: {
    formatCategory(category) {
      return category.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    },
  },
  components: {
    AppLayout,
  },
};
</script>
