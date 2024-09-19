<template>
  <div class="content-wrapper">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-4 medium-12 small-12 cell">
          <h2 class="why-ch__title before-fade-in fade-in">
            Unrivaled career testing
          </h2>
          <ul class="accordion nested enable-active-medium-up">
            <li v-for="(item, index) in accordionItems" :key="index" class="accordion-item before-fade-in fade-in" :class="{ 'is-active': activeIndex === index }">
              <span class="accordion-title" @click="toggleAccordion(index)">
                {{ item.title }}
              </span>
              <div class="accordion-content" :style="{ display: activeIndex === index ? 'block' : 'none' }">
                <p :class="{ 'before-fade-in fade-in': activeIndex === index }">
                  {{ item.content }}
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
                  alt="Reveal your strengths"
                  src="https://www.careerhunter.io/images/home/benefits-desktop-home.png"
              />
              <img
                  class="show-for-medium-only"
                  alt="Reveal your strengths"
                  src="https://www.careerhunter.io/images/home/benefits-desktop-home.png"
              />
              <img
                  class="show-for-small-only"
                  alt="Reveal your strengths"
                  src="https://www.careerhunter.io/images/home/benefits-mobile-2.png"
              />
            </picture>
          </div>
        </div>
      </div>
    </div>
    <link
        rel="stylesheet"
        media="all"
        href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css"
    />
  </div>
</template>

<script>
export default {
  name: 'Content',
  data() {
    return {
      activeIndex: null,
      accordionItems: [
        {
          title: 'Smart and accurate career matching',
          content: 'CareerHunter evaluates your test answers to accurately match you to the careers you\'re best suited to according to your unique interests, personality type, motivations and strengths.'
        },
        {
          title: 'Built by experts and scientifically validated',
          content: 'Our tests were developed by a team of psychometricians, career experts and researchers, and have undergone extensive validity and reliability testing to ensure the accuracy and integrity of the entire platform.'
        },
        {
          title: 'Unique and intelligent algorithm',
          content: 'We score test-takers using an advanced and innovative technology based on our continuously evolving algorithm, designed to analyze the results of each test in real time to generate personalized results.'
        },
        {
          title: 'Database of 250+ popular professions',
          content: 'Explore our database of popular and emerging professions across all major industries, and read up on the ones that you want to pursue with our detailed and insightful career profiles.'
        },
        {
          title: 'Personalized insights and reporting',
          content: 'Get detailed insights into your results as you complete each test — including your scores, what they mean and how they can impact your future career — plus access your full career report that compiles all your matches.'
        }
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
  padding-left: 20px; /* Adjust this value to move the content more or less to the right */
}
</style>
