<template>
   
    <div class="DashboardPage__reports">
      <div class="DashboardPage__report" style="background-image: url('/results_folder/circles-5444818_1920.png'); background-size: cover; background-position: center;">
        <div class="DashboardPage__report__book" >
          <div class="ReportBook ReportBook--trait">
            <div class="ReportBook__content">
              <h1 class="ReportBook__title">
                Trait Report
              </h1>
              <h2 class="ReportBook__heading">
                You're Social
              </h2>
              <p class="ReportBook__copy">
                In fact, you're more social than 93% of the population.
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
          Learn what makes you unique and how you compare to the rest of the
          world.
        </p>

      </div>
      <div class="DashboardPage__report" style="background-image: url('/results_folder/sunrise-6587391_1920.png'); background-size: cover; background-position: center;">
        <div class="DashboardPage__report__book" >
          <div class="ReportBook ReportBook--personality">
            <div class="ReportBook__content">
              <h1 class="ReportBook__title">
                Personality Report
              </h1>
              <h2 class="ReportBook__heading">
              You're a {{archetype.name}}
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
          aria-label="Click here to view your trait report details."
        >
          {{ __('results.view_report') }}
        </Link>
      </div>
    </div>
  </template>

  <script>
  import { Link } from '@inertiajs/vue3';

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
<style  scoped>
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/user-results.3aa4bb301b9f.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/shared-discovery.4d94d358083a.css');
</style>