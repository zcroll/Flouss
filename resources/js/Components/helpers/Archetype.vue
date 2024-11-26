<template>
  <div class="flex justify-center items-center">
    <div role="document"
      class="dialog-element Dialog Dialog--Discovery Dialog--Discovery--archetype Dialog--Discovery--share-dialog-closed">
      <div class="dialog-inner Dialog-wrap Dialog--DiscoveryDialog">
        <button class="dialog-close Dialog-close" title="Close" type="button" aria-label="Close this dialog window"
          tabindex="-1" @click="closeDialog">
          <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-down"
            class="svg-inline--fa fa-chevron-down Dialog-close-icon" role="img" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512">
            <path fill="currentColor"
              d="M4.251 181.1C7.392 177.7 11.69 175.1 16 175.1c3.891 0 7.781 1.406 10.86 4.25l197.1 181.1l197.1-181.1c6.5-6 16.64-5.625 22.61 .9062c6 6.5 5.594 16.59-.8906 22.59l-208 192c-6.156 5.688-15.56 5.688-21.72 0l-208-192C-1.343 197.7-1.749 187.6 4.251 181.1z" />
          </svg>
        </button>
        <div>
          <p role="heading" aria-level="1" id="dialog-discovery-title" class="dialog-title Dialog-title">{{ archetypeDiscovery.notification_text }}</p>
          <div class="Dialog-wrap__background">
            <div class="Dialog-discovery" :style="{ backgroundImage: `url(${archetypeDiscovery.image_url})` }">
              <div class="Dialog-discovery__actions">
                <button class="Dialog-discovery__share-button alans-butt--grey" tabindex="0" 
                  aria-label="Click here to share your discovery on social media." @click="shareDiscovery">
                  <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="share-from-square"
                    class="svg-inline--fa fa-share-from-square Dialog-discovery__share-button__icon" role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path fill="currentColor"
                      d="M568.9 143.5l-150.9-138.2C404.8-6.773 384 3.039 384 21.84V96C241.2 97.63 128 126.1 128 260.6c0 54.3 35.2 108.1 74.08 136.2c12.14 8.781 29.42-2.238 24.94-16.46C186.7 252.2 256 224 384 223.1v74.2c0 18.82 20.84 28.59 34.02 16.51l150.9-138.2C578.4 167.8 578.4 152.2 568.9 143.5zM416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z">
                    </path>
                  </svg> Share
                </button>
                <button class="Dialog-discovery__extraclose-button alans-butt--grey" tabindex="0"
                  aria-label="Click here to close the dialog and to continue your assessment." @click="closeDialog">
                  <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="xmark"
                    class="svg-inline--fa fa-xmark Dialog-discovery__extraclose-button__icon" role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path fill="currentColor"
                      d="M312.1 375c9.369 9.369 9.369 24.57 0 33.94s-24.57 9.369-33.94 0L160 289.9l-119 119c-9.369 9.369-24.57 9.369-33.94 0s-9.369-24.57 0-33.94L126.1 256L7.027 136.1c-9.369-9.369-9.369-24.57 0-33.94s24.57-9.369 33.94 0L160 222.1l119-119c9.369-9.369 24.57-9.369 33.94 0s9.369 24.57 0 33.94L193.9 256L312.1 375z">
                    </path>
                  </svg>
                </button>
              </div>
              <span class="Discovery__rarity DiscoveryRarityTag" :class="`DiscoveryRarityTag--${archetypeDiscovery.rarity_string.toLowerCase()}`"
                aria-hidden="true">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="gem"
                  class="svg-inline--fa fa-gem Discovery__rarity__icon DiscoveryRarityTag__icon" role="img"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path fill="currentColor"
                    d="M378.7 32H133.3L256 182.7L378.7 32zM512 192l-107.4-141.3L289.6 192H512zM107.4 50.67L0 192h222.4L107.4 50.67zM244.3 474.9C247.3 478.2 251.6 480 256 480s8.653-1.828 11.67-5.062L510.6 224H1.365L244.3 474.9z">
                  </path>
                </svg>{{ archetypeDiscovery.rarity_string }}—{{ (archetypeDiscovery.frequency * 100).toFixed(1) }}% of Users
              </span>
              <h1 class="Discovery__title" tabindex="0"
                :aria-label="`You are ${archetypeDiscovery.name}. That is ${archetypeDiscovery.rarity_string.toLowerCase()}! This places you in the top ${(archetypeDiscovery.frequency * 100).toFixed(1)}% of users.`">
                You are {{ archetypeDiscovery.name }}
              </h1>
              <p class="Discovery__rationale" tabindex="0">{{ archetypeDiscovery.rationale }}</p>
            </div>
            <div class="Discovery__related">
              <div class="Discovery__related__readmore" :class="{ 'Discovery__related__readmore--collapsed': !showMore }">
                <p class="Discovery__related__description" tabindex="0">{{ archetypeDiscovery.verbose_description }}</p>
                <h3 class="Discovery__related__lil-subheading">The Science</h3>
                <p class="Discovery__related__description">{{ archetypeDiscovery.scales_descriptor }}</p>
                <div class="Discovery__related__content Discovery__related__content--scales">
                  <h3 class="Discovery__related__lil-subheading" tabindex="0"
                    aria-label="Below you will find a list of scores detailing where you match-up to chosen traits.">
                    Your scores</h3>
                  <div class="PersonalityGraphs">
                    <a v-for="(scale, index) in JSON.parse(archetypeDiscovery.scales)" :key="index" href="#" class="Box">
                      <div class="PersonalityGraphs__scale">
                        <div class="PersonalityGraphs__scale__name" :id="`${index}-personalitygraph-scale-label`">
                          {{ scale.scale_name }}
                        </div>
                        <span class="sr-only" :id="`${index}-personalitygraph-scale-desc`">
                          Your {{ scale.scale_name.toLowerCase() }} rates at {{ Math.round(scale.percentile_score) }} out of 100.
                        </span>
                        <figure class="PersonalityGraphs__scale__graph" tabindex="0"
                          :aria-labelledby="`${index}-personalitygraph-scale-label`"
                          :aria-describedby="`${index}-personalitygraph-scale-desc`">
                          <span class="BarMeter--gradient-dusk" :data-value="Math.round(scale.percentile_score)">
                            <span class="BarMeter__meter" :style="{ width: Math.round(scale.percentile_score) + '%' }">
                              <span class="BarMeter__label">{{ Math.round(scale.percentile_score) }}%</span>
                            </span>
                          </span>
                        </figure>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
              <button class="Discovery__related__readmore-button" @click="toggleShowMore">
                {{ showMore ? 'Read Less -' : 'Read More +' }}
              </button>
              <div class="Discovery__related__feedback">
                <button class="Dialog__back-button alans-butt--grey" tabindex="0"
                  aria-label="Click here to close the dialog and to continue your assessment." @click="$emit('close')">
                  Continue assessment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
  inheritAttrs: false
});

const props = defineProps({
  archetypeDiscovery: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['close']);
const showMore = ref(false);

const closeDialog = () => {
  emit('close');
};

const shareDiscovery = () => {
  // TODO: Implement share functionality
  console.log('Share discovery');
};

const toggleShowMore = () => {
  showMore.value = !showMore.value;
};
</script>

<style scoped>
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/assessments.ba16abcb0f5b.css');

.dialog-element {
  transform: scale(0.9);
  font-family: 'Quicksand', sans-serif;
  font-size: 1em;
}

.Discovery__title {
  font-size: 1.7em;
  font-weight: 500;
}

.Discovery__related__description {
  font-size: 0.95em;
  font-weight: 300;
}

.Discovery__related__lil-subheading {
  font-size: 1.1em;
  font-weight: 400;
}

.PersonalityGraphs__scale__name {
  font-size: 0.9em;
}

.BarMeter__label {
  font-size: 0.85em;
}

button {
  font-size: 0.95em;
}

.Discovery__related__readmore {
  max-height: 1000px;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out;
}

.Discovery__related__readmore--collapsed {
  max-height: 100px;
}
</style>