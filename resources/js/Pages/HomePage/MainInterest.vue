<template>
  <div>
    <div class="grid-container no-padding-top no-side-pad" :style="gridContainerStyle">
      <h2 class="benefits-title">Good to know</h2>
      <div class="swiper-container careers-tips-slider swiper-container-initialized swiper-container-horizontal" :style="swiperContainerStyle">
        <div class="swiper-wrapper" :style="swiperWrapperStyle">
          <div class="flex flex-row justify-between items-stretch">
            <div v-for="(slide, index) in slides" :key="index" class="swiper-slide flex-1 mx-2" :style="swiperSlideStyle">
              <div class="card card--balanced small h-full" :style="cardStyle">
                <h3 class="mar-bot-10 before-fade-in fade-in" :style="h3Style">{{ slide.title }}</h3>
                <p class="is-marginless before-fade-in fade-in" :style="pStyle">{{ slide.content }}</p>
              </div>
            </div>
          </div>
        </div>
        <span class="swiper-notification" aria-atomic="true" aria-live="assertive" :style="swiperNotificationStyle"></span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MainInterest',
  data() {
    return {
      slides: [
        {
          title: 'Think about what you like',
          content: 'Try not to think about how qualified to do the work or how much money you would make doing the work described in the test.'
        },
        {
          title: 'Don\'t feel guilty',
          content: 'Try not to guilt-trip yourself into choosing an activity over another based on perceived expectations or what\'s "socially acceptable".'
        },
        {
          title: 'Don\'t worry about failing',
          content: 'There are no right or wrong answers. The purpose of the Career Interests Test is to identify your personal interests and how they relate to the world of work.'
        },
        {
          title: 'Keep an open mind',
          content: 'Be open to career directions you never previously considered. It\'s very likely that you\'re best suited to careers you never thought about'
        }
      ],
      // ... styles objects (gridContainerStyle, swiperContainerStyle, etc.) go here
    }
  }
}
</script><style scoped>

</style>

