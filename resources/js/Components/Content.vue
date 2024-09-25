<template>
  <div class="content-wrapper">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-4 medium-12 small-12 cell">
          <h2 class="why-ch__title before-fade-in fade-in">
            {{ __('welcome.unrivaled_testing') }}
          </h2>
          <ul class="accordion nested enable-active-medium-up">
            <li v-for="(item, index) in accordionItems" :key="index" class="accordion-item before-fade-in fade-in" :class="{ 'is-active': activeIndex === index }">
              <span class="accordion-title" @click="toggleAccordion(index)">
                {{ __(`welcome.accordion_items.${item.key}.title`) }}
              </span>
              <div class="accordion-content" :style="{ display: activeIndex === index ? 'block' : 'none' }">
                <p :class="{ 'before-fade-in fade-in': activeIndex === index }">
                  {{ __(`welcome.accordion_items.${item.key}.content`) }}
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="large-8 medium-12 large-order-1 small-order-2 cell">
          <div class="why-ch__imgs">
            <picture>
              <source
                  type="image/webp"
                  media="(min-width: 1025px)"
                  srcset="https://www.careerhunter.io/images/home/benefits-desktop-home.webp"
              />
              <source
                  type="image/webp"
                  media="(min-width: 641px) and (max-width: 1024px)"
                  srcset="https://www.careerhunter.io/images/home/benefits-desktop-home.webp"
              />
              <source
                  type="image/webp"
                  media="(min-width: 320px) and (max-width: 640px)"
                  srcset="https://www.careerhunter.io/images/home/benefits-mobile-2.webp"
              />
              <img
                  class="show-for-large before-fade-in fade-in"
                  :alt="__('welcome.reveal_your_strengths')"
                  src="https://www.careerhunter.io/images/home/benefits-desktop-home.png"
              />
              <img
                  class="show-for-medium-only"
                  :alt="__('welcome.reveal_your_strengths')"
                  src="https://www.careerhunter.io/images/home/benefits-desktop-home.png"
              />
              <img
                  class="show-for-small-only"
                  :alt="__('welcome.reveal_your_strengths')"
                  src="https://www.careerhunter.io/images/home/benefits-mobile-2.png"
              />
            </picture>
          </div>
        </div>
      </div>
    </div>
    <link rel="stylesheet" href="/css/landingPage/index.css">
  </div>
</template>

<script>
export default {
  name: 'Content',
  data() {
    return {
      activeIndex: null,
      accordionItems: [
        { key: 'smart_matching' },
        { key: 'expert_built' },
        { key: 'unique_algorithm' },
        { key: 'profession_database' },
        { key: 'personalized_insights' }
      ]
    }
  },
  methods: {
    toggleAccordion(index) {
      this.activeIndex = this.activeIndex === index ? null : index;
    }
  }
}
</script>

<style scoped>
.content-wrapper {
  padding-left: 20px;
}
</style>
