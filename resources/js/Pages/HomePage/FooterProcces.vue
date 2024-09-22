<template>
    <link rel="stylesheet" media="all" href="/css/landingPage/index.css" />
    <div class="grid-container">
      <div class="grid-x text-center">
        <div class="small-12 cell">
          <h2 class="bottom-banner__headline-small before-fade-in fade-in">
            Ready to start testing?
          </h2>
          <h4 class="bottom-banner__desc bottom-banner__desc--450 before-fade-in fade-in">
            Begin today, and reveal accurate career matches and suitable study
            options.
          </h4>
        </div>
          <div class="small-12 cell mt-10">
              <a
                  class="button button--green--large before-fade-in fade-in"
                  aria-label="Get your Discount"
                  :href="route('test-preview')"
              >
                  Go to Tests
                  <span class="button__hovered" />
              </a>
          </div>
      </div>

      <div class="circle right-large show-for-large" />
      <div class="circle right-small show-for-large" />
      <div class="circle right-medium show-for-large" />
    </div>
  </template>

  <script>
  import {Link} from "@inertiajs/vue3";

  export default {
    name: 'FooterProcces',
      components: {Link}
  };
  </script>

  <style scoped>
  </style>
