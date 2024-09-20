<template>
  <div>
    <link rel="stylesheet" media="all" href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css" />
    <link rel="stylesheet" media="all" href="//cdn3.careerhunter.io/assets/main_tests-bd884139ec646feab4ac86d2f494e405033a3d476b5b9ac820fc8fbc6576c466.css" />

    <div class="flex justify-center items-center min-h-screen">
      <div class="test-page__wrapper for-ci-start-page">
        <div class="test-page__wrapper__main for-start-main-page with-save">
          <div class="blur-box-container blur-box-container__main blur-box-container--spaced test-main-page">
            <div class="blur-box blur-box__main">
              <div class="main-test align-center">
                <div class="breadcrumbs-nav breadcrumbs-nav--tests">
                  <a href="https://www.careerhunter.io/tests">Tests</a>
                  <div class="small-icon arrow-breadcrumbs"></div>
                  <a href="https://www.careerhunter.io/tests/interests">Career Interests</a>
                  <div class="small-icon arrow-breadcrumbs"></div>
                  <span>Start</span>
                </div>

                <div class="large-icon interests-white-bold"></div>
                <h1 class="main-test__title">Career Interests Test</h1>

                <div class="grid-x main-test__details">
                  <div class="small-5 medium-4 cell flex-center">
                    <span class="small-icons questions main-test__details__icon"></span>
                    27 questions
                  </div>
                  <div class="small-7 medium-4 cell flex-center">
                    <span class="small-icons timed main-test__details__icon"></span>
                    Approx. 15 minutes
                  </div>
                  <div class="small-12 medium-4 cell flex-center main-test__details__last">
                    <span class="small-icons clock-stop-green main-test__details__icon"></span>
                    No time limit
                  </div>
                </div>

                <p class="max-width-777 ci-test-introduction">
                  Discover your areas of interest based on the work activities that you find most interesting and enjoyable.
                </p>

                <Link :href="route('test')">
                  <button class="button button--green before-fade-in fade-in button--with-icon">
                    Continue
                    <span class="small-icons next-arrow-white-xs is-right"></span>
                  </button>
                </Link>

                <div class="test-btn-container flex-center">
                  <a class="text-button-small" @click="readInstructions">Read instructions</a>
                </div>

                <div class="tooltip-box tooltip-box-ci-test">
                  <div class="tooltip-trigger small-icon white-tooltip-icon"></div>
                  Complete all tests to reveal your career matches.
                </div>

                <div class="ci-test-benefits-block">
                  <h4>Why take this test?</h4>
                  <p>Discover your areas of interest according to the work activities that you find interesting and enjoyable.</p>
                  <ul class="ci-test-benefits-list">
                    <li v-for="benefit in benefits" :key="benefit" class="ci-test-benefits-point">
                      {{ benefit }}
                    </li>
                  </ul>
                </div>
              </div>

              <div class="bg-copy interests"></div>
              <div class="slideout"></div>
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
  name: 'TestPreview2',
  components: {
    Link,
  },
  data() {
    return {
      benefits: [
        'See how your interests compare against 27 key areas',
        'Discover if you have any unrealized passions to pursue',
        'Identify what work activities you enjoy most',
        'Make an informed decision about your career aspirations',
        'Ensure you are pursuing a job or course you enjoy'
      ]
    }
  },
  methods: {
    readInstructions() {
      // Implement the logic to show instructions
      console.log('Reading instructions...')
    }
  }
}
</script>
