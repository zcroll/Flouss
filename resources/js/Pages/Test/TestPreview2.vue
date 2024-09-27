<template>
  <div>
    <link rel="stylesheet" media="all" href="/css/landingPage/index.css" />
      <link rel="stylesheet" media="all" href="/css/landingPage/main-test.css"/>

    <div class="flex justify-center items-center min-h-screen">
      <div class="test-page__wrapper for-ci-start-page">
        <div class="test-page__wrapper__main for-start-main-page with-save ">
          <div class="blur-box-container blur-box-container__main blur-box-container--spaced test-main-page">
            <div class="blur-box blur-box__main">
              <div class="main-test align-center">
                <div class="breadcrumbs-nav breadcrumbs-nav--tests">
                  <Link :href="route('dashboard')">{{ __('test.dashboard') }}</Link>
                  <div class="small-icon arrow-breadcrumbs"></div>
                  <Link :href="route('interests')">{{ __('test.career_interests') }}</Link>
                  <div class="small-icon arrow-breadcrumbs"></div>
                  <span>{{ __('test.start') }}</span>
                </div>

                <div class="large-icon interests-white-bold"></div>
                <h1 class="main-test__title">{{ __('test.career_interests_test') }}</h1>

                <div class="grid-x main-test__details">
                  <div class="small-5 medium-4 cell flex-center">
                    <span class="small-icons questions main-test__details__icon"></span>
                    27 {{ __('test.questions') }}
                  </div>
                  <div class="small-7 medium-4 cell flex-center">
                    <span class="small-icons timed main-test__details__icon"></span>
                    {{ __('test.approx_minutes', { minutes: 15 }) }}
                  </div>
                  <div class="small-12 medium-4 cell flex-center main-test__details__last">
                    <span class="small-icons clock-stop-green main-test__details__icon"></span>
                    {{ __('test.no_time_limit') }}
                  </div>
                </div>

                <p class="max-width-777 ci-test-introduction">
                  {{ __('test.test_introduction') }}
                </p>

                <Link :href="route('test')">
                  <button class="button button--green before-fade-in fade-in button--with-icon">
                    {{ __('test.continue') }}
                    <span class="small-icons next-arrow-white-xs is-right"></span>
                  </button>
                </Link>

                <div class="test-btn-container flex-center">
                  <a class="text-button-small" @click="readInstructions">{{ __('test.read_instructions') }}</a>
                </div>

                <div class="tooltip-box tooltip-box-ci-test">
                  <div class="tooltip-trigger small-icon white-tooltip-icon"></div>
                  {{ __('test.complete_all_tests') }}
                </div>

                <div class="ci-test-benefits-block">
                  <h4>{{ __('test.why_take_this_test') }}</h4>
                  <p>{{ __('test.test_description') }}</p>
                  <ul class="ci-test-benefits-list">
                    <li v-for="(benefit, key) in translatedBenefits" :key="key" class="ci-test-benefits-point">
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
import { computed } from 'vue';
import __ from '@/lang';

export default {
  name: 'TestPreview2',
  components: {
    Link,
  },
  setup() {
    const translatedBenefits = computed(() => {
      return {
        'Compare interests': __('test.benefits.Compare interests'),
        'Discover passions': __('test.benefits.Discover passions'),
        'Identify activities': __('test.benefits.Identify activities'),
        'Informed decision': __('test.benefits.Informed decision'),
        'Ensure enjoyment': __('test.benefits.Ensure enjoyment'),
      };
    });

    return {
      translatedBenefits,
    };
  },
  methods: {
    readInstructions() {
      // Implement the logic to show instructions
      console.log('Reading instructions...')
    },
    __
  }
}
</script>
