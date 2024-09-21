<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css"
    />
    <link rel="stylesheet" media="all" href="//cdn3.careerhunter.io/assets/main_tests-bd884139ec646feab4ac86d2f494e405033a3d476b5b9ac820fc8fbc6576c466.css" />

    <div class="test-page__wrapper__main" :style="wrapperStyle">
      <div class="blur-box-container blur-box-container--spaced overview__container">
        <div class="blur-box">
          <div class="main-test align-center">
            <div class="main-test__wrapper">
              <div class="med-spaced-between small-flex-center">
                <div>
                  <p class="large-mobile overview__test-name">
                    Career Interests
                  </p>
                  <p class="large-mobile overview__subheading">
                    Overview
                  </p>
                </div>
                <div class="flex">
                  <div class="overview__space">
                    <p class="overview__answered">{{ answeredCount }}</p>
                    <p>Answered</p>
                  </div>
                  <div>
                    <p class="overview__unanswered">{{ unansweredCount }}</p>
                    <p>Unanswered</p>
                  </div>
                </div>
              </div>
              <div class="grid-x overview-gutter extra-space small-flex-center">
                <button
                  v-for="index in totalPages"
                  :key="index"
                  :class="['button', 'before-fade-in', 'fade-in', 'button--answer', 
                    getButtonClass(index)]"
                  @click="goToPage(index)"
                >
                  <p class="button--answer__number">{{ index }}</p>
                  <span class="button__hovered" />
                </button>
              </div>
            </div>
            <div class="grid-x fixed-grid">
              <div class="small-4 medium-3 cell text-left">
                <button @click="goBackToTest" class="button button--white before-fade-in fade-in nav-btn">
                  <span class="small-icons back-arrow-white-xs is-left" />
                  <span class="large-mobile">
                    Back
                    <span class="show-for-medium"> to test</span>
                  </span>
                  <span class="button__hovered" />
                </button>
              </div>
              
            </div>
          </div>
          <div class="bg-copy interests" />
        </div>
      </div>
      <div class="test-btn-container flex-center" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Overview',
  props: {
    rankedActivities: {
      type: Array,
      required: true
    },
    totalActivities: {
      type: Number,
      required: true
    },
    incompletePoints: {
      type: Array,
      required: true
    },
    currentPage: {
      type: Number,
      required: true
    }
  },
  computed: {
    answeredCount() {
      return this.rankedActivities.length;
    },
    unansweredCount() {
      return this.totalActivities - this.answeredCount;
    },
    totalPages() {
      return Math.ceil(this.totalActivities / 6);
    },
    wrapperStyle() {
      return {
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        minHeight: '100vh'
      };
    }
  },
  methods: {
    getButtonClass(index) {
      if (this.rankedActivities.some(activity => Math.ceil(activity.id / 6) === index)) {
        return 'button--green';
      } else if (this.incompletePoints.includes(index)) {
        return 'button--yellow';
      } else {
        return 'button--grey wrong';
      }
    },
    goToPage(index) {
      this.$emit('go-to-page', index);
    },
    goBackToTest() {
      this.$emit('go-back-to-test');
    },
    saveForLater() {
      this.$emit('save-for-later');
    },
    submitAnswers() {
      this.$emit('submit-answers');
    }
  }
}
</script>

<style scoped>
/* Add any component-specific styles here */
.button--yellow {
  background-color: #FFD700;
  color: #000;
}
</style>