<template>
  <section class="CareerTest__how-it-works">
    <div class="CareerTest__spacer"></div>
    <h2>{{ __('how_our_test_work.title') }}</h2>
    <div class="CareerTest__how-it-works__animation" :data-path="animationDataPath">
      <lottie-player 
        autoplay
        loop
        style="width:600px"
        :src="animationDataPath"
        speed="1"
        debug
        @frame="onAnimationFrame"
        :renderer-settings="{ preserveAspectRatio: 'xMidYMid slice' }"
      />
    </div>
    <div class="CareerTest__how-it-works__copy">
      <h2>{{ __('how_our_test_work.title') }}</h2>
      <ul class="CareerTest__how-it-works__items">
        <li v-for="(item, index) in items" :key="index" 
            class="CareerTest__how-it-works__item"
            :class="{ 'CareerTest__how-it-works__item--active': item.active }">
          <div class="CareerTest__how-it-works__item__progress" :style="{ height: 100 + '%', transition: 'height 0.5s ease-in-out' }"></div>
          <h3>{{ __(item.title) }}</h3>
          <p>{{ __(item.description) }}</p>
        </li>
      </ul>
    </div>
  </section>
</template>

<script>
import * as LottiePlayer from "@lottiefiles/lottie-player";
export default {
  data() {
    return {
      animationDataPath: "https://d5lqosquewn6c.cloudfront.net/static/sokanu/compiled/scripts/career-test-animation.22d0c124bcd3.json",
      items: [
        {
          title: 'how_our_test_work.measure_who_you_are',
          description: 'how_our_test_work.measure_who_you_are_description',
          progress: 99.2582,
          active: false,
          startFrame: 0,
          endFrame: 90
        },
        {
          title: 'how_our_test_work.calculate_career_attributes',
          description: 'how_our_test_work.calculate_career_attributes_description',
          progress: 44.8639,
          active: false,
          startFrame: 91,
          endFrame: 255
        },
        {
          title: 'how_our_test_work.compare_the_data',
          description: 'how_our_test_work.compare_the_data_description',
          progress: 99.7219,
          active: false,
          startFrame: 256,
          endFrame: 384
        },
        {
          title: 'how_our_test_work.determine_your_top_career_matches',
          description: 'how_our_test_work.determine_your_top_career_matches_description',
          progress: 99.9821,
          active: false,
          startFrame: 385,
          endFrame: 600
        },
      ],
    };
  },
  methods: {
    onAnimationFrame(event) {
      const frame = event.detail.frame;
      this.items.forEach(item => {
        item.active = frame >= item.startFrame && frame <= item.endFrame;
      });
    }
  }
};
</script>
