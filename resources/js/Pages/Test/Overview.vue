<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="/css/landingPage/index.css"
    />
    <link rel="stylesheet" media="all" href="/css/landingPage/main-test.css"/>

    <div class="test-page__wrapper__main" :style="wrapperStyle">
      <div class="blur-box-container blur-box-container--spaced overview__container">
        <div class="blur-box">
          <div class="main-test align-center">
            <div class="main-test__wrapper">
              <div class="med-spaced-between small-flex-center">
                <div>
                  <p class="large-mobile overview__test-name">
                    {{ __('test.career_interests') }}
                  </p>
                  <p class="large-mobile overview__subheading">
                    {{ __('test.overview') }}
                  </p>
                </div>
                <div class="flex">
                  <div class="overview__space">
                    <p class="overview__answered">{{ answeredCount }}</p>
                    <p>{{ __('test.answered') }}</p>
                  </div>
                  <div>
                    <p class="overview__unanswered">{{ unansweredCount }}</p>
                    <p>{{ __('test.unanswered') }}</p>
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
                    {{ __('test.back') }}
                    <span class="show-for-medium"> {{ __('test.back_to_test') }}</span>
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
import __ from '@/lang';

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
    },
    __
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
