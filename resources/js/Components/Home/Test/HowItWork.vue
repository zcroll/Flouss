<template>
  <section class="CareerTest__how-it-works">
    <div class="CareerTest__spacer"></div>
    <h2>How our career test works</h2>
    <div class="CareerTest__how-it-works__animation" :data-path="animationDataPath">
      <lottie-player 
        autoplay
        loop
        style="width:400px"
        :src="animationDataPath"
        speed="1"
        debug
        @frame="onAnimationFrame"
        :renderer-settings="{ preserveAspectRatio: 'xMidYMid slice' }"
      />
    </div>
    <div class="CareerTest__how-it-works__copy">
      <h2>How our career test works</h2>
      <ul class="CareerTest__how-it-works__items">
        <li v-for="(item, index) in items" :key="index" 
            class="CareerTest__how-it-works__item"
            :class="{ 'CareerTest__how-it-works__item--active': item.active }">
          <div class="CareerTest__how-it-works__item__progress" :style="{ height: 100 + '%', transition: 'height 0.5s ease-in-out' }"></div>
          <h3>{{ item.title }}</h3>
          <p>{{ item.description }}</p>
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
          title: 'Measure who you are',
          description: 'We ask you a series of questions that correspond to 150+ personality traits and learn about your career interests, skills, and goals.',
          progress: 99.2582,
          active: false,
          startFrame: 0,
          endFrame: 90
        },
        {
          title: 'Calculate career attributes',
          description: 'We produce detailed profiles of 1,500+ careers and degrees based on labor market data, psychometric profiles, and other users',
          progress: 44.8639,
          active: false,
          startFrame: 91,
          endFrame: 255
        },
        {
          title: 'Compare the data',
          description: 'We compare these two datasets to determine your compatibility with the careers and degrees in our database.',
          progress: 99.7219,
          active: false,
          startFrame: 256,
          endFrame: 384
        },
        {
          title: 'Determine your top career matches',
          description: 'We surface the careers and degrees that are top matches for you, and provide insights on your unique personality.',
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
