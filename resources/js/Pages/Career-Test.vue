<script setup>
import { ref } from 'vue';
import HowItWork from '@/Components/Home/Test/HowItWork.vue';
import Final from '@/Components/Home/Test/Final.vue';
import Header from '@/Components/Home/Navbar/Header.vue';
import { Link } from '@inertiajs/vue3';
const stats = ref([
  {
    heading: '500 M',
    label: 'career-test.questions_answered'
  },
  {
    heading: '1500+', 
    label: 'career-test.degrees_and_careers'
  },
  {
    heading: '140+',
    label: 'career-test.personality_traits' 
  }
]);
</script>

<template>
  <Header />
  <div role="main">    <link rel="preload" href="/css/career-test.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <section class="CareerTest__header">
    <svg class="CareerTest__header__bg" width="766" height="706" viewBox="0 0 766 706" fill="none">
      <path opacity="0.65" fill-rule="evenodd" clip-rule="evenodd" d="M444.131 327.152C500.409 290.742 545.935 248.683 607.834 261.181C666.907 273.107 714.796 299.465 762.798 328.921C826.817 368.207 923.52 390.452 933.546 461.056C943.525 531.328 861.491 594.814 809.433 657.326C760.175 716.477 712.175 776.703 640.1 813.433C562.095 853.186 467.266 895.586 391.356 870.382C315.879 845.322 322.068 750.247 277.528 693.051C238.601 643.062 130.284 622.052 147.891 557.733C167.015 487.872 297.14 486.778 350.104 427.922C394.555 378.526 386.292 364.572 444.131 327.152Z" fill="#5E5141"/>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M579.037 275.789C611.709 317.722 648.446 350.67 641.416 400.844C634.706 448.728 615.62 488.834 594.005 529.268C565.178 583.194 551.969 661.79 494.751 675.208C437.802 688.563 381.663 628.243 327.864 591.679C276.957 557.081 225.237 523.568 191.489 469.101C154.965 410.153 115.409 338.035 132.108 275.742C148.712 213.803 226.776 211.388 271.252 171.567C310.124 136.763 321.725 49.0294 375.226 58.0647C433.339 67.8786 440.936 171.245 491.792 208.811C534.474 240.339 545.459 232.693 579.037 275.789Z" fill="#E4D5C6" fill-opacity="0.65"/>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M622.228 573.148C583.029 598.6 551.393 628.291 507.974 618.343C466.537 608.848 432.848 589.1 399.062 567.112C354.003 537.788 286.154 520.281 278.723 469.147C271.326 418.254 328.404 373.634 364.496 329.271C398.647 287.293 431.911 244.519 482.17 219.072C536.564 191.531 602.725 162.33 656.029 181.663C709.03 200.886 705.249 269.449 736.771 311.415C764.321 348.094 840.295 364.883 828.339 411.066C815.353 461.228 724.236 460.075 687.488 501.785C656.647 536.79 662.514 546.99 622.228 573.148Z" fill="#7B6E61"/>
      <path d="M224.867 754.152C171.398 689.31 128.389 615.278 97.6981 540.302C89.7386 520.857 83.1884 498.502 81.9117 477.271C81.8016 475.441 80.7438 464.13 89.5497 464.897C107.058 466.422 156.37 487.582 161.031 489.559C224.36 516.42 283.197 552.541 350.93 573.48C431.1 598.263 535.482 587.345 612.693 542.318C704.886 488.554 692.547 408.726 659.682 341.766C632.317 286.01 606.292 253.673 555.929 210.877C525.494 185.016 494.239 163.251 449.27 154.081C431.96 150.552 413.36 149.775 394.918 150.156C363.468 150.805 269.268 162.889 240.88 168.306C192.306 177.576 100.836 196.13 95.6661 248.516C93.9165 266.243 114.366 271.027 132.316 271.837C186.814 274.294 247.128 262.07 300.509 247.005C326.923 239.551 351.492 226.539 373.706 212.363C450.903 163.101 507.811 103.329 526.815 27.422" stroke="#E3D661" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    
    <div class="CareerTest__header__copy">
      <h1>{{ __('career-test.title') }}</h1>
      <p class="CareerTest__header__description">
        {{ __('career-test.description') }}
      </p>
      <Link :href="route('main-test')" class="alans-butt--large alans-butt--grey CareerTest__header__button" data-track="mixpanel" data-target="Start the career test">{{ __('career-test.start_test') }}</Link>

      <section class="CareerTest__header__stats">
        <h2 class="CareerTest__header__stats__heading">{{ __('career-test.by_the_numbers') }}</h2>
        
        <div class="UserSatisfactionStars">
          <div class="UserSatisfactionStars__wrap">
            <div class="UserSatisfactionStars__label">{{ __('career-test.avg_rating') }}</div>
            <div class="UserSatisfactionStars__stars-wrap">
              <div class="Stars" aria-label="4.5 out of 5">
                <svg v-for="i in 4" :key="i" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star" class="svg-inline--fa fa-star fa-fw Stars__star Stars__star--full" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-testid="star-full">
                  <path fill="currentColor" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                </svg>
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star-half-stroke" class="svg-inline--fa fa-star-half-stroke Stars__star Stars__star--half" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-testid="star-half">
                  <path fill="currentColor" d="M463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7zM288 376.4L288.1 376.3L399.7 435.9L378.4 309.6L469.2 219.8L343.8 201.4L288.1 86.85L288 87.14V376.4z"/>
                </svg>
              </div>
              <label class="UserSatisfactionStars__rating">{{ __('career-test.rating') }}</label>
            </div>
          </div>
          <div class="UserSatisfactionStars__count">{{ __('career-test.customers_count') }}</div>
        </div>

        <div class="CareerTest__header__stats-wrap">
          <div v-for="stat in stats" :key="stat.heading" class="CareerTest__header__stats__stat">
            <div class="CareerTest__header__stats__stat__heading">{{ stat.heading }}</div>
            <div class="CareerTest__header__stats__stat__label">{{ __(stat.label) }}</div>
          </div>
        </div>
      </section>
    </div>

    <div class="CareerTest__header__images">
      <div class="CareerTest__header__images__desktop" style="background-image: url(/list_jobs_desktop.png)"></div>
      <div class="CareerTest__header__images__mobile" style="background-image: url(/test_answer.png)"></div>
    </div>
    </section>
    <section class="CareerTest__features lazyloaded" data-bg="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/blobs-features.5e6a32f66cd7.svg" style="background-image: url(&quot;https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/blobs-features.5e6a32f66cd7.svg&quot;);">
      <div class="CareerTest__spacer"></div>
      <div class="CareerTest__features__copy">
        <h2>{{ __('career-test.discover_your_top_matches') }}</h2>

        <div class="CareerTest__features__feature-items">
          <div class="CareerTest__features__feature-items__item">
            <img class=" ls-is-cached lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/platform-icon.068d8a375c6e.svg" alt="Rapidly-advancing platform" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/platform-icon.068d8a375c6e.svg">
            <h3>{{ __('career-test.rapidly_advancing_platform') }}</h3>
            <p>
              {{ __('career-test.rapidly_advancing_description') }}
            </p>
          </div>
          <div class="CareerTest__features__feature-items__item">
            <img class=" ls-is-cached lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/model-icon.3a2aea803edc.svg" alt="Comprehensive model" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/model-icon.3a2aea803edc.svg">
            <h3>{{ __('career-test.comprehensive_model') }}</h3>
            <p>
              {{ __('career-test.comprehensive_description') }}
            </p>
          </div>
          <div class="CareerTest__features__feature-items__item">
            <img class=" ls-is-cached lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/enhancements-icon.7323e6b3b168.svg" alt="Intelligent enhancements" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/enhancements-icon.7323e6b3b168.svg">
            <h3>{{ __('career-test.intelligent_enhancements') }}</h3>
            <p>
              {{ __('career-test.intelligent_enhancements_description') }}
            </p>
          </div>
          <div class="CareerTest__features__feature-items__item">
            <img class=" ls-is-cached lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/experience-icon.69a0bea45714.svg" alt="Up-to-date information" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/experience-icon.69a0bea45714.svg">
            <h3>{{ __('career-test.up_to_date_information') }}</h3>
            <p>
              {{ __('career-test.up_to_date_description') }}
            </p>
          </div>
        </div>
      </div>
      <div class="CareerTest__features__key-features lazyloaded" data-bg="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-features-squiggle.6ef6785e15bf.svg" style="background-image: url(&quot;https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-features-squiggle.6ef6785e15bf.svg&quot;);">
        <div class="CareerTest__features__key-feature">
          <div class="CareerTest__features__key-feature__copy">
            <h3 class="CareerTest__top-heading">
              {{ __('career-test.personalized_recommendations') }}
            </h3>
            <h4 class="CareerTest__heading">
              {{ __('career-test.learn_as_you_go') }}
            </h4>
            <p>
              {{ __('career-test.learn_as_you_go_description') }}
            </p>
          </div>
          <div class="CareerTest__features__key-feature__preview lazyloaded" data-bg="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-feature-1-blobs.720d484e9146.svg" style="background-image: url(&quot;https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-feature-1-blobs.720d484e9146.svg&quot;);">
            <video class=" lazyloaded " preload="auto" src="https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/discover-flow.d58bb08296d1.mp4" data-autoplay="" muted="" loop="" playsinline="" data-expand="-10">
            </video></div>
        </div>
        <div class="CareerTest__features__key-feature">
          <div class="CareerTest__features__key-feature__copy">
            <h3 class="CareerTest__top-heading">
              {{ __('career-test.insights_worth_mentioning') }}
            </h3>
            <h4 class="CareerTest__heading">
              {{ __('career-test.share_discoveries') }}
            </h4>
            <p> 
              {{ __('career-test.Are_you_a_Visionary_?') }}
            </p>
          </div>
          <div class="CareerTest__features__key-feature__preview lazyloaded" data-bg="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-feature-2-blobs.59d70cefb4c9.svg" style="background-image: url(&quot;https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-feature-2-blobs.59d70cefb4c9.svg&quot;);">
            <video class=" lazyload lazyloaded" preload="auto" data-autoplay="" muted="" loop="" playsinline="" data-expand="-10">
            </video></div>
        </div>
        <div class="CareerTest__features__key-feature">
          <div class="CareerTest__features__key-feature__copy">
            <h3 class="CareerTest__top-heading">
              {{ __('career-test.work_on_your_schedule') }}
            </h3>
            <h4 class="CareerTest__heading">
              {{ __('career-test.complete_on_your_time') }}
            </h4>
            <p>
              {{ __('career-test.complete_on_your_time_description') }}
            </p>
          </div>
          <div class="CareerTest__features__key-feature__preview lazyload" data-bg="https://res.cloudinary.com/hnpb47ejt/image/fetch/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/key-feature-3-blobs.e4ada8534a83.svg">
            <video class="lazyload" preload="none" src="https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/pick-up-flow.76d8df8d4bbe.mp4" data-autoplay="" muted="" loop="" playsinline="">
            </video></div>
          <a href="/members/register/deferred/" class="alans-butt--black alans-butt--large CareerTest__features__key-feature__button--mobile" data-track="mixpanel" data-target="Let’s get started">{{ __('career-test.find_your_career_matches') }}</a>
        </div>
        
        <div class="CareerTest__features__four-dimensions__wrap">
          <div class="CareerTest__features__four-dimensions__diagram">
            <img class=" lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/four-dimensions.c6ddda17fdb8.png" alt="Four Dimensions" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/four-dimensions.c6ddda17fdb8.png">
          </div>
          <div class="CareerTest__features__four-dimensions__copy">
            <div class="CareerTest__features__four-dimensions__highlight">
              <img class=" lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-workplace.4cf14d18c9a0.png" alt="Workplace" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-workplace.4cf14d18c9a0.png">
              <h5>{{ __('career-test.workplace') }} <span>01</span></h5>
              <p><em>{{ __('career-test.will_the_typical_workplace') }}</em></p>
              <p>{{ __('career-test.environment_contributor') }}</p>
            </div>
            <div class="CareerTest__features__four-dimensions__highlight">
              <img class=" lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-history.e14b04166268.png" alt="History" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-history.e14b04166268.png">
              <h5>{{ __('career-test.history') }} <span>02</span></h5>
              <p><em>{{ __('career-test.will_my_past_work') }}</em></p>
              <p>{{ __('career-test.experiences_education') }}</p>
            </div>
            <div class="CareerTest__features__four-dimensions__highlight">
              <img class=" lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-interests.35cb4edb5fde.png" alt="Interests" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-interests.35cb4edb5fde.png">
              <h5>{{ __('career-test.interests') }} <span>03</span></h5>
              <p><em>{{ __('career-test.will_i_find_this_career') }}</em></p>
              <p>{{ __('career-test.research_shows') }}</p>
            </div>
            <div class="CareerTest__features__four-dimensions__highlight">
              <img class=" lazyloaded" data-src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-personality.48b04c933afb.png" alt="Personality" src="https://res.cloudinary.com/hnpb47ejt/image/fetch/f_auto/https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/images/career-test/icon-personality.48b04c933afb.png">
              <h5>{{ __('career-test.personality') }} <span>04</span></h5>
              <p><em>{{ __('career-test.will_i_be_good') }}</em></p>
              <p>{{ __('career-test.personality_fit') }}</p>
            </div>
          </div>
        </div>
      </div>
      <HowItWork />
      <Final />
    </section>
  </div>
</template>

<style scoped>

</style>
