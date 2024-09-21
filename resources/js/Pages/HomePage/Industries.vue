<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css"
    />
    <link 
      rel="stylesheet" 
      media="all" 
      href="//cdn3.careerhunter.io/assets/main_tests-bd884139ec646feab4ac86d2f494e405033a3d476b5b9ac820fc8fbc6576c466.css" 
    />

    <div class="grid-container industries">
      <div class="grid-x">
        <div class="large-3 medium-12 cell">
          <h2 class="industries__title before-fade-in fade-in">
            Explore our areas of interest
          </h2>
          <p class="industries__description before-fade-in fade-in">
            We'll score you against each of our 27 areas of interest to
            uncover where your passions truly lie.
          </p>
        </div>
        <div class="large-9 medium-12 cell">
          <div>
            <div class="grid-x">
              <div v-for="(industry, index) in industries" :key="index" class="card industries__card large-3 medium-6 small-12 cell" :class="{'fade-out': industry.fading, 'fade-in': !industry.fading}">
                <h3 class="industries__name">
                  {{ industry.name }}
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
        { name: 'Construction and Trades', fading: false },
        { name: 'Military', fading: false },
        { name: 'Counseling and Social Services', fading: false },
        { name: 'Banking and Finance', fading: false },
        { name: 'Engineering', fading: false },
        { name: 'Healthcare', fading: false },
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
          this.industries[this.currentIndex].name = this.getNextIndustry();
          this.industries[this.currentIndex].fading = false;
          this.currentIndex = (this.currentIndex + 1) % this.industries.length;
        }, 500);
      }, 3000);
    },
    getNextIndustry() {
      const allIndustries = [
        'Construction and Trades', 'Military', 'Counseling and Social Services',
        'Banking and Finance', 'Engineering', 'Healthcare', 'Education',
        'Technology', 'Arts and Entertainment', 'Law and Legal Services'
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