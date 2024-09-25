<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="/css/landingPage/index.css"
    />
    <link 
      rel="stylesheet" 
      media="all" 
      href="/css/landingPage/index.css" 
    />

    <div class="grid-container industries">
      <div class="grid-x">
        <div class="large-3 medium-12 cell">
          <h2 class="industries__title before-fade-in fade-in">
            {{ __('industries.explore_areas') }}
          </h2>
          <p class="industries__description before-fade-in fade-in">
            {{ __('industries.areas_description') }}
          </p>
        </div>
        <div class="large-9 medium-12 cell">
          <div>
            <div class="grid-x">
              <div v-for="(industry, index) in industries" :key="index" class="card industries__card large-3 medium-6 small-12 cell" :class="{'fade-out': industry.fading, 'fade-in': !industry.fading}">
                <h3 class="industries__name">
                  {{ __(`industries.industries.${industry.key}`) }}
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Industries',
  data() {
    return {
      industries: [
        { key: 'construction_trades', fading: false },
        { key: 'military', fading: false },
        { key: 'counseling_social_services', fading: false },
        { key: 'banking_finance', fading: false },
        { key: 'engineering', fading: false },
        { key: 'healthcare', fading: false },
      ],
      currentIndex: 0
    }
  },
  mounted() {
    this.startFadingAnimation();
  },
  methods: {
    startFadingAnimation() {
      setInterval(() => {
        this.industries[this.currentIndex].fading = true;
        
        setTimeout(() => {
          this.industries[this.currentIndex].key = this.getNextIndustry();
          this.industries[this.currentIndex].fading = false;
          this.currentIndex = (this.currentIndex + 1) % this.industries.length;
        }, 500);
      }, 3000);
    },
    getNextIndustry() {
      const allIndustries = [
        'construction_trades', 'military', 'counseling_social_services',
        'banking_finance', 'engineering', 'healthcare', 'education',
        'technology', 'arts_entertainment', 'law_legal_services'
      ];
      return allIndustries[Math.floor(Math.random() * allIndustries.length)];
    }
  }
}
</script>

<style scoped>
.fade-out {
  opacity: 0;
  transition: opacity 0.5s ease-out;
}

.fade-in {
  opacity: 1;
  transition: opacity 0.5s ease-in;
}

/* Add any other component-specific styles here */
</style>