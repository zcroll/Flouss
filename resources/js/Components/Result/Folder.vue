<template>
    <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">

        <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/user-results.3aa4bb301b9f.css"></link>

    <div class="DashboardPage__reports">
      <div class="DashboardPage__report" style="background-image: url('/results_folder/circles-5444818_1920.png'); background-size: cover; background-position: center;">
        <div class="DashboardPage__report__book" >
          <div class="ReportBook ReportBook--trait">
            <div class="ReportBook__content">
              <h1 class="ReportBook__title">
                {{ __('results.trait_report') }}
              </h1>
              <h2 class="ReportBook__heading">
                {{ __('results.youre_social') }}
              </h2>
              <p class="ReportBook__copy">
                {{ __('results.more_social_than') }}
              </p>
              <div class="ReportBook__footer">
                <svg
                    className="ReportBook__logo"
                    version="1.1"
                    viewBox="0 0 239 56"
                    xmlns="http://www.w3.org/2000/svg"

                  >
                    <title >
                        lorer
                    </title>
                    <g
                      id="Final(WithSokanu)--default"
                      fill="none"
                      fillRule="evenodd"
                      stroke="none"
                      strokeWidth="1"
                    >
                      <g
                        id="Finalized-Logo---Black-and-White--default"
                        fill="currentColor"
                        transform="translate(-18.000000, -29.000000)"
                      >
                        <g
                          id="Logo--default"
                          transform="translate(18.000000, 29.000000)"
                        >


                        </g>
                      </g>
                    </g>
                  </svg>
              </div>
            </div>
          </div>
        </div>
        <p class="DashboardPage__report__copy">
          {{ __('results.learn_what_makes_unique') }}
        </p>

      </div>
      <div class="DashboardPage__report" style="background-image: url('/results_folder/sunrise-6587391_1920.png'); background-size: cover; background-position: center;">
        <div class="DashboardPage__report__book" >
          <div class="ReportBook ReportBook--personality">
            <div class="ReportBook__content">
              <h1 class="ReportBook__title">
                {{ __('results.personality_report') }}
              </h1>
              <h2 class="ReportBook__heading">
                  {{archetype.name}}
              </h2>
              <p class="ReportBook__copy">
              </p>
              <div class="ReportBook__footer">
                <svg
                    className="ReportBook__logo"
                    version="1.1"
                    viewBox="0 0 239 56"
                    xmlns="http://www.w3.org/2000/svg"

                  >
                    <title >
                        lorer
                    </title>
                    <g
                      id="Final(WithSokanu)--default"
                      fill="none"
                      fillRule="evenodd"
                      stroke="none"
                      strokeWidth="1"
                    >
                      <g
                        id="Finalized-Logo---Black-and-White--default"
                        fill="currentColor"
                        transform="translate(-18.000000, -29.000000)"
                      >
                        <g
                          id="Logo--default"
                          transform="translate(18.000000, 29.000000)"
                        >

                        </g>
                      </g>
                    </g>
                  </svg>

              </div>
            </div>
          </div>
        </div>
        <p class="DashboardPage__report__copy">
         {{ archetype.strengths_short }}
        </p>
        <Link
          class="DashboardPage__report__link"
          :href="`/results/${userId}/personality`"
          tabindex="0"
          aria-label="{{ __('results.click_to_view_trait_report') }}"
        >
          {{ __('results.view_report') }}
        </Link>
      </div>
    </div>
  </template>

  <script>
  import { Link } from '@inertiajs/vue3';
  import __ from '@/lang';

  export default {
    name: 'Collapse',
    components: {
      Link
    },
    props: {
        userId: {
            type: Number,
            required: true
        },
        archetype: {
            type: Object,
            required: true
        },
    }
  }
  </script>
