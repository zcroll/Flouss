<template>
  <section class="CareerTest__final">
    <div class="CareerTest__spacer"></div>
    <div class="CareerTest__final__container">
      <h2 class="CareerTest__final__heading">
        Your dream career is waiting
      </h2>
      <p class="CareerTest__final__copy">
        It's time to start exploring.
      </p>
      <div class="CareerTest__final__cta">
        <Link href="/main-test" class="CareerTest__final__cta__image" data-track="mixpanel" data-target="Begin the assessment">
          <img class="lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/final-button.f136d289ef06.svg" alt="Begin the assessment" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/final-button.f136d289ef06.svg">
          <div class="CareerTest__final__cta__text">Begin the <br>assessment</div>
        </Link>
      </div>
    </div>
  </section>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link
  },
  name: 'Final'
}
</script>
