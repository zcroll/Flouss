<template>
  <div class="content-wrapper">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-4 medium-12 small-12 cell">
          <h2 class="why-ch__title before-fade-in fade-in ">
            {{ __('welcome.unrivaled_testing') }}
          </h2>
          <ul class="accordion nested enable-active-medium-up">
            <li v-for="(item, index) in accordionItems" :key="index" class="accordion-item before-fade-in fade-in" :class="{ 'is-active': activeIndex === index }">
              <span class="accordion-title text-gray-900" @click="toggleAccordion(index)">
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
                  media="(min-width: 320px) and (max-width: 640px)"
                  srcset="/jobs.webp"
                  class="h-20 w-20"
              />
              <img
                  class="show-for-large before-fade-in fade-in test_home"
                  :alt="__('welcome.reveal_your_strengths')"
                  src="/test1.webp"
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

.module {
  max-width: 250px;
  padding: 1rem;
  color: white;
  border-width: 3px;
  border-style: solid;
  border-image: 
    linear-gradient(
      to bottom, 
      red, 
      rgba(0, 0, 0, 0)
    ) 1 100%;
}
</style>
