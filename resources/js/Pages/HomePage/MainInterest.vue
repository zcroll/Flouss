<template>
  <div>
    <div class="grid-container no-padding-top no-side-pad" :style="gridContainerStyle">
      <h2 class="benefits-title">{{ __('main_interest.good_to_know') }}</h2>
      <div class="swiper-container careers-tips-slider swiper-container-initialized swiper-container-horizontal" :style="swiperContainerStyle">
        <div class="swiper-wrapper" :style="swiperWrapperStyle">
          <div class="flex flex-row justify-between items-stretch">
            <div v-for="(slide, index) in slides" :key="index" class="swiper-slide flex-1 mx-2" :style="swiperSlideStyle">
              <div class="card card--balanced small h-full" :style="cardStyle">
                <h3 class="mar-bot-10 before-fade-in fade-in" :style="h3Style">{{ __(`main_interest.slides.${slide.key}.title`) }}</h3>
                <p class="is-marginless before-fade-in fade-in" :style="pStyle">{{ __(`main_interest.slides.${slide.key}.content`) }}</p>
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
        { key: 'think_about_likes' },
        { key: 'dont_feel_guilty' },
        { key: 'no_wrong_answers' },
        { key: 'keep_open_mind' }
      ],
      // ... styles objects (gridContainerStyle, swiperContainerStyle, etc.) go here
    }
  }
}
</script><style scoped>

</style>

