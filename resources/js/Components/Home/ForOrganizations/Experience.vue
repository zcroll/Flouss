<template>
  <div class="org123 wf-section">
    <div id="org-gradient-background" aria-hidden="true" class="org123__background">
      <div class="org123__background__block" :style="{opacity: activeIndex === 0 ? 1 : 0, transition: 'opacity 2s ease'}"></div>
      <div class="org123__background__block org123__background__block--2" :style="{opacity: activeIndex === 1 ? 1 : 0, transition: 'opacity 2s ease'}"></div>
      <div class="org123__background__block org123__background__block--3" :style="{opacity: activeIndex === 2 ? 1 : 0, transition: 'opacity 2s ease'}"></div>
    </div>
    <div class="one-off-container">
      <div class="scrollframeorg">
        <div class="scrollframeorg__nav">
          <div class="scrollframeorg__nav__container">
            <h2 class="productive-heading-01 uppercase">CREATE A CUSTOM EXPERIENCE FOR YOUR ORGANIZATION</h2>
            <div class="scrollframeorg__nav__progress">
              <div class="scrollframeorg__nav__progress-container">
                <div class="scrollframeorg__nav__progress-bar">
                  <div class="scrollframeorg__nav__progress-bar-track"></div>
                  <div class="scrollframeorg__nav__progress-bar-track scrollframeorg__nav__progress-bar-track--progression" :style="{height: progressHeight + '%', transition: 'height 2s ease'}"></div>
                </div>
              </div>
            </div>
            <ul role="list" class="scrollframeorg__nav__list w-list-unstyled">
              <li v-for="(item, index) in navItems" :key="index" class="scrollframeorg__nav__list-item">
                <div class="scrollframeorg__nav__list-num">{{ item.num }}</div>
                <a :href="item.href" class="scrollframeorg__nav__list-link w-inline-block" :class="{'w--current': activeIndex === index}">
                  <div class="scrollframeorg__nav__list-label">{{ item.label }}</div>
                  <p class="body-short-02 scrollframeorg__nav__list-desc">{{ item.desc }}</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="scrollframeorg__content">
          <div v-for="(content, index) in contentItems" :key="index" :id="content.id" class="scrollframeorg__content__block" :class="content.class">
            <div class="scrollframeorg__content-mobile" :style="{opacity: activeIndex === index ? 1 : 0, transition: 'opacity 2s ease'}">
              <h2 v-if="index === 0" class="productive-heading-01 uppercase">CREATE A CUSTOM EXPERIENCE FOR YOUR ORGANIZATION</h2>
              <div class="productive-heading-04 tint">{{ content.num }}</div>
              <h2 class="productive-heading-04">{{ content.title }}</h2>
              <p class="body-short-02 tint">{{ content.desc }}</p>
            </div>
            <div class="worldclass__grid__item" style="display: flex; justify-content: center; align-items: center;">
              <img 
                :src="content.img.src"
                loading="lazy"
                :sizes="content.img.sizes"
                :width="content.img.width"
                :alt="content.img.alt"
                :srcset="content.img.srcset"
                class="worldclass__img"
                :style="{
                  ...imageStyle, 
                  opacity: activeIndex === index ? 1 : 0,
                  transform: activeIndex === index 
                    ? 'translate3d(0px, 0px, 0px) scale3d(1, 1, 1)' 
                    : (index === 2 ? 'translate3d(0px, 100px, 0px) scale3d(0.8, 0.8, 1)' : 'translate3d(0px, 0px, 0px) scale3d(0.8, 0.8, 1)'),
                  transition: 'all 2s ease'
                }"
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Experience',
  data() {
    return {
      activeIndex: 0,
      progressHeight: 33,
      imageStyle: {
        transformStyle: 'preserve-3d'
      },
      navItems: [
        {
          num: '01',
          href: '#org-it-1',
          label: 'Apply your brand assets',
          desc: 'Create a unique URL that leads to your custom landing page, co-branded with your logo and a personalized message to students.'
        },
        {
          num: '02',
          href: '#org-it-2',
          label: 'View your members\' activity',
          desc: 'Use the Administrator\'s Dashboard to view individual student information and progress.'
        },
        {
          num: '03',
          href: '#org-it-3',
          label: 'Keep track of broader trends',
          desc: 'Review aggregate data to track participation rates across your organization.'
        }
      ],
      contentItems: [
        {
          id: 'org-it-1',
          num: '01',
          title: 'Apply your brand assets',
          desc: 'Create a unique URL that leads to your custom landing page, co-branded with your logo and a personalized message to students.',
          img: {
            src: '/landingPage/organization/admin_brand.png',
            sizes: '(max-width: 479px) 80vw, (max-width: 991px) 450px, 49vw',
            width: '584',
            alt: 'Apply your brand assets',
            srcset: '/landingPage/organization/admin_brand.png 500w, /landingPage/organization/admin_brand.png 800w, /landingPage/organization/admin_brand.png 1080w, /landingPage/organization/admin_brand.png 1168w'
          }
        },
        {
          id: 'org-it-2',
          class: 'scrollframeorg__content__block--2',
          num: '02', 
          title: 'View your students\' activity',
          desc: 'Use the Administrator\'s Dashboard to view individual student information and progress.',
          img: {
            src: '/landingPage/organization/admin_member\'s_activity.png',
            sizes: '(max-width: 479px) 80vw, (max-width: 991px) 450px, 49vw',
            width: '584',
            alt: 'View your students\' activity',
            srcset: '/landingPage/organization/admin_member\'s_activity.png 500w, /landingPage/organization/admin_member\'s_activity.png 800w, /landingPage/organization/admin_member\'s_activity.png 1080w, /landingPage/organization/admin_member\'s_activity.png 1168w'
          }
        },
        {
          id: 'org-it-3',
          class: 'scrollframeorg__content__block--3',
          num: '03',
          title: 'Keep track of broader trends',
          desc: 'Review aggregate data to track participation rates across your organization.',
          img: {
            src: '/landingPage/organization/admin_.trends.png',
            sizes: '(max-width: 479px) 80vw, (max-width: 991px) 450px, 49vw',
            width: '584',
            alt: 'Keep track of broader trends',
            srcset: '/landingPage/organization/admin_.trends.png 500w, /landingPage/organization/admin_.trends.png 800w, /landingPage/organization/admin_.trends.png 1080w, /landingPage/organization/admin_.trends.png 1168w'
          }
        }
      ]
    }
  },
  mounted() {
    this.setupIntersectionObserver();
  },
  methods: {
    setupIntersectionObserver() {
      const observerMargin = Math.floor(window.innerHeight / 2);
      const first = window.innerHeight % 2 === 0 ? observerMargin - 1 : observerMargin;
      const rootMargin = `-${first}px 0px -${observerMargin}px 0px`;

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const activeIndex = this.contentItems.findIndex(item => item.id === entry.target.id);
            if (activeIndex !== -1) {
              this.activeIndex = activeIndex;
              this.progressHeight = Math.round(((activeIndex + 1) / this.contentItems.length) * 100);
            }
          }
        });
      }, { rootMargin });

      this.contentItems.forEach(item => {
        const element = document.getElementById(item.id);
        if (element) {
          observer.observe(element);
        }
      });
    }
  }
}
</script>
