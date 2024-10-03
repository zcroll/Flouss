<template>
    <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">
    <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/user-results.3aa4bb301b9f.css"> 
    <div class="flex justify-center items-center">
      <div role="document" class="dialog-element Dialog Dialog--Discovery Dialog--Discovery--archetype Dialog--Discovery--share-dialog-closed">
        <div class="dialog-inner Dialog-wrap Dialog--DiscoveryDialog">
          <button class="dialog-close Dialog-close" title="Close" type="button" aria-label="Close this dialog window" tabindex="-1" @click="closeDialog">
            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-down" class="svg-inline--fa fa-chevron-down Dialog-close-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
              <path fill="currentColor" d="M4.251 181.1C7.392 177.7 11.69 175.1 16 175.1c3.891 0 7.781 1.406 10.86 4.25l197.1 181.1l197.1-181.1c6.5-6 16.64-5.625 22.61 .9062c6 6.5 5.594 16.59-.8906 22.59l-208 192c-6.156 5.688-15.56 5.688-21.72 0l-208-192C-1.343 197.7-1.749 187.6 4.251 181.1z" />
            </svg>
          </button>
          <div>
            <p role="heading" aria-level="1" id="dialog-discovery-title" class="dialog-title Dialog-title">{{ archetypeDiscovery.notification_text }}</p>
            <div class="Dialog-wrap__background">
              <div class="Dialog-discovery" :style="{ backgroundImage: `url('${archetypeDiscovery.image_url}')` }">
                <div class="Dialog-discovery__actions">
                  <button class="Dialog-discovery__share-button alans-butt--grey" tabindex="0" aria-label="Click here to share your discovery on social media." @click="shareDiscovery">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="share-from-square" class="svg-inline--fa fa-share-from-square Dialog-discovery__share-button__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                      <path fill="currentColor" d="M568.9 143.5l-150.9-138.2C404.8-6.773 384 3.039 384 21.84V96C241.2 97.63 128 126.1 128 260.6c0 54.3 35.2 108.1 74.08 136.2c12.14 8.781 29.42-2.238 24.94-16.46C186.7 252.2 256 224 384 223.1v74.2c0 18.82 20.84 28.59 34.02 16.51l150.9-138.2C578.4 167.8 578.4 152.2 568.9 143.5zM416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z" />
                    </svg> Share
                  </button>
                  <button class="Dialog-discovery__extraclose-button alans-butt--grey" tabindex="0" aria-label="Click here to close the dialog and to continue your assessment." @click="closeDialog">
                    <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="xmark" class="svg-inline--fa fa-xmark Dialog-discovery__extraclose-button__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                      <path fill="currentColor" d="M312.1 375c9.369 9.369 9.369 24.57 0 33.94s-24.57 9.369-33.94 0L160 289.9l-119 119c-9.369 9.369-24.57 9.369-33.94 0s-9.369-24.57 0-33.94L126.1 256L7.027 136.1c-9.369-9.369-9.369-24.57 0-33.94s24.57-9.369 33.94 0L160 222.1l119-119c9.369-9.369 24.57-9.369 33.94 0s9.369 24.57 0 33.94L193.9 256L312.1 375z" />
                    </svg>
                  </button>
                </div>
                <span class="Discovery__rarity DiscoveryRarityTag DiscoveryRarityTag--very-rare" aria-hidden="true">
                  <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="gem" class="svg-inline--fa fa-gem Discovery__rarity__icon DiscoveryRarityTag__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M378.7 32H133.3L256 182.7L378.7 32zM512 192l-107.4-141.3L289.6 192H512zM107.4 50.67L0 192h222.4L107.4 50.67zM244.3 474.9C247.3 478.2 251.6 480 256 480s8.653-1.828 11.67-5.062L510.6 224H1.365L244.3 474.9z" />
                  </svg> {{ archetypeDiscovery.rarity_string }}—{{ (archetypeDiscovery.frequency * 100).toFixed(1) }}% of Users
                </span>
                <h1 class="Discovery__title" tabindex="0" aria-label="You are a Kingpin. That is actually very rare! This places you in the top 4.5% of users.">{{ archetypeDiscovery.notification_text }}</h1>
                <p class="Discovery__rationale" tabindex="0">{{ archetypeDiscovery.rationale }}</p>
              </div>
              <div class="Discovery__related">
                <div class="Discovery__related__readmore">
                  <p class="Discovery__related__description" tabindex="0">{{ archetypeDiscovery.verbose_description }}</p>
                  <h3 class="Discovery__related__lil-subheading">{{ archetypeDiscovery.verbose_description_header }}</h3>
                  <p class="Discovery__related__description">{{ archetypeDiscovery.scales_descriptor }}</p>
                  <div class="Discovery__related__content Discovery__related__content--scales">
                    <h3 class="Discovery__related__lil-subheading" tabindex="0" aria-label="Below you will find a list of scores detailing where you match-up to chosen traits.">Your scores</h3>
                    <div class="PersonalityGraphs">
                      <a href="#" class="Box">
                        <div class="PersonalityGraphs__scale">
                          <div class="PersonalityGraphs__scale__name" id="5-personalitygraph-scale-label">Enterprising</div>
                          <span class="sr-only" id="5-personalitygraph-scale-desc">Your enterprising rates at 75.01249375312344 out of 100.</span>
                          <figure class="PersonalityGraphs__scale__graph" tabindex="0" aria-labelledby="5-personalitygraph-scale-label" aria-describedby="5-personalitygraph-scale-desc">
                            <span class="BarMeter--gradient-dusk" data-value="75">
                              <span class="BarMeter__meter" style="width: 75%;">
                                <span class="BarMeter__label">75%</span>
                              </span>
                            </span>
                          </figure>
                        </div>
                      </a>
                      <a href="#" class="Box">
                        <div class="PersonalityGraphs__scale">
                          <div class="PersonalityGraphs__scale__name" id="6-personalitygraph-scale-label">Conventional</div>
                          <span class="sr-only" id="6-personalitygraph-scale-desc">Your conventional rates at 90.75462268865567 out of 100.</span>
                          <figure class="PersonalityGraphs__scale__graph" tabindex="0" aria-labelledby="6-personalitygraph-scale-label" aria-describedby="6-personalitygraph-scale-desc">
                            <span class="BarMeter--gradient-dusk" data-value="91">
                              <span class="BarMeter__meter" style="width: 91%;">
                                <span class="BarMeter__label">91%</span>
                              </span>
                            </span>
                          </figure>
                        </div>
                      </a>
                    </div>
                  </div>
                  <h3 class="Discovery__related__lil-subheading" tabindex="0" aria-label="Below you will find a list of similar jobs.">Similar Jobs</h3>
                
                </div>
                <div class="Discovery__related__feedback">
                  <div class="DiscoveryFeedback" aria-live="assertive" aria-atomic="true" aria-relevant="text">
                    <span tabindex="0">Was this interesting?</span>
                    <button class="DiscoveryFeedback-button alans-butt--grey" tabindex="0" aria-label="Option one: this discovery was interesting. Click here to provide feedback." title="interesting">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="svg-inline--fa fa-thumbs-up DiscoveryFeedback-button__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M128 447.1V223.1c0-17.67-14.33-31.1-32-31.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64C113.7 479.1 128 465.6 128 447.1zM512 224.1c0-26.5-21.48-47.98-48-47.98h-146.5c22.77-37.91 34.52-80.88 34.52-96.02C352 56.52 333.5 32 302.5 32c-63.13 0-26.36 76.15-108.2 141.6L178 186.6C166.2 196.1 160.2 210 160.1 224c-.0234 .0234 0 0 0 0L160 384c0 15.1 7.113 29.33 19.2 38.39l34.14 25.59C241 468.8 274.7 480 309.3 480H368c26.52 0 48-21.47 48-47.98C512 262.5 492.2 241.9 467.3 240.2z" />
                      </svg>
                    </button>
                    <button class="DiscoveryFeedback-button alans-butt--grey" tabindex="0" aria-label="Option two: this discovery was not interesting. Click here to provide feedback." title="not interesting">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-down" class="svg-inline--fa fa-thumbs-down DiscoveryFeedback-button__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M96 32.04H32c-17.67 0-32 14.32-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64c17.67 0 32-14.33 32-31.1V64.03C128 46.36 113.7 32.04 96 32.04zM467.3 240.2C475.1 231.7 480 220.4 480 207.9c0-23.47-16.87-42.92-39.14-47.09C445.3 153.6 448 145.1 448 135.1c0-21.32-14-39.18-33.25-45.43C415.5 87.12 416 83.61 416 79.98C416 53.47 394.5 32 368 32h-58.69c-34.61 0-68.28 11.22-95.97 31.98L179.2 89.57C167.1 98.63 160 112.9 160 127.1l.1074 160c0 0-.0234-.0234 0 0c.0703 13.99 6.123 27.94 17.91 37.36l16.3 13.03C276.2 403.9 239.4 480 302.5 480c30.96 0 49.47-24.52 49.47-48.11c0-15.15-11.76-58.12-34.52-96.02H464c26.52 0 48-21.47 48-47.98C512 262.5 492.2 241.9 467.3 240.2z" />
                      </svg>
                    </button>
                  </div>
                  <Link :href="comingSoon ? '#' : `results/${userId}/personality`" class="Dialog__back-button alans-butt--grey" tabindex="0" aria-label="Click here to view more details about your personality results">
                    {{ __('results.read_more') }}
                  </Link>
                  <button class="Dialog__back-button alans-butt--grey" tabindex="0" aria-label="Click here to close the dialog and to continue your assessment." @click="$emit('close')">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3';   
  export default {
    components: {
      Link,
    },
    methods: {
      closeDialog() {
        this.$emit('close');
      },
      shareDiscovery() {
        // Implement share discovery logic
      },
    },
    props: {
      archetype: {
        type: Object,
        required: true,
      },
      archetypeJobs: {
        type: Array,
        required: true,
      },
      archetypeDiscovery: {
        type: Object,
        required: true,
      },
      userId: {
        type: Number,
        required: true,
      },
    }
  }
  </script>