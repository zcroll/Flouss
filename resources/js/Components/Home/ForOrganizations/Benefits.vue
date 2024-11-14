<template>
  <div class="lrgrid wf-section">
    <div class="lrgrid__background" aria-hidden="true"></div>
    <div class="global_container">
      <!-- First Grid Section -->
      <div class="lrgrid__grid" :style="gridStyle">
        <div class="lrgrid__img">
          <img 
            class="lrgrid__image__abs"
            :src="images.topVector"
            width="674"
            alt="Blob 1"
            aria-hidden="true"
            :sizes="sizes.default"
            loading="lazy"
            :srcset="srcsets.topVector"
          >
          <div class="lrgrid__image__video">
            <div class="html-embed-4">
              <img 
                class="lazyload lazyloaded"
              ></img>
            </div>
            <img 
              class="lrgrid__image__abs"
              :src="images.mobileView"
              alt="video is loading..."
              aria-hidden="true"
              :sizes="sizes.mobile"
              loading="lazy"
              :srcset="srcsets.mobileView"
            >
          </div>
          <img 
            class="lrgrid__image__rel"
            :src="images.bottomVector"
            width="674"
            alt="Blob 2"
            aria-hidden="true"
            :sizes="sizes.default"
            loading="lazy"
            :srcset="srcsets.bottomVector"
          >
        </div>
        <div class="lrgrid__text">
          <h2 class="productive-heading-06 override">
            {{ __('Interactivity promotes student engagement') }}
          </h2>
          <p class="body-short-02">
            {{ __('Our seamless, mobile-first assessment results in higher completion rates and student engagement, directing even more students to your career center.') }}
          </p>
        </div>
      </div>

      <!-- Second Grid Section -->
      <div class="lrgrid__grid">
        <div class="lrgrid__img">
          <template v-for="(image, index) in discoveryImages" :key="index">
            <img 
              :class="index === discoveryImages.length - 1 ? 'lrgrid__image__rel' : 'lrgrid__image__abs'"
              :src="image.src"
              width="594"
              :alt="image.alt"
              :aria-hidden="image.ariaHidden"
              :sizes="sizes.discovery"
              :srcset="image.srcset"
            >
          </template>
        </div>
        <div class="lrgrid__text">
          <h2 class="productive-heading-06 override">
            {{ __('Discoveries make career exploration inspiring and enjoyable') }}
          </h2>
          <p class="body-short-02">
            {{ __('As they\'re completing the assessment, students get previews of their matches and the qualities that make them different from others.') }}
          </p>
        </div>
      </div>

      <!-- Third Grid Section -->
      <div class="lrgrid__grid">
        <div class="lrgrid__img">
          <img 
            class="lrgrid__image__abs"
            :src="images.mid"
            width="594"
            alt="Your compatibility"
            :sizes="sizes.discovery"
            loading="lazy"
            :srcset="srcsets.mid"
          >
          <img 
            class="lrgrid__image__abs"
            :src="images.top"
            width="594"
            alt="Compatibility menu"
            aria-hidden="true"
            :sizes="sizes.discovery"
            loading="lazy"
            :srcset="srcsets.top"
          >
          <img 
            class="lrgrid__image__rel"
            :src="images.bottom"
            width="594"
            alt="Blobs"
            aria-hidden="true"
            :sizes="sizes.discovery"
            loading="lazy"
            :srcset="srcsets.bottom"
          >
        </div>
        <div class="lrgrid__text">
          <h2 class="productive-heading-06 override">
            {{ __('Intuitive user experience empowers students to explore independently') }}
          </h2>
          <p class="body-short-02">
            {{ __('Students come prepared for counseling sessions, saving counselors time and fostering better learning outcomes.') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LearningGrid',
  data() {
    return {
      gridStyle: {
        opacity: 1,
        transform: 'translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg)',
        transformStyle: 'preserve-3d'
      },
      images: {
        topVector: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955e1e795c1b9a4e3500_top%20vector%20container.png',
        mobileView: '/landingPage/organization/results_mobile.png',
        bottomVector: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955f26a48585ba636929_bottom%20vector%20container.png',
        mid: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db85847919360ccd94e92_mid.png',
        top: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858326fae24dca5130d_top.png',
        bottom: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858ae19b91345718dd6_bottom.png'
      },
      sizes: {
        default: '(max-width: 479px) 95vw, (max-width: 767px) 96vw, (max-width: 991px) 674px, 47vw',
        mobile: '(max-width: 479px) 95vw, (max-width: 608px) 96vw, (max-width: 991px) 584px, 47vw',
        discovery: '(max-width: 479px) 95vw, (max-width: 767px) 96vw, (max-width: 991px) 594px, 47vw'
      },
      srcsets: {
        topVector: `
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955e1e795c1b9a4e3500_top%20vector%20container-p-500.png 500w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955e1e795c1b9a4e3500_top%20vector%20container-p-800.png 800w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955e1e795c1b9a4e3500_top%20vector%20container.png 1168w
        `,
        mobileView: `
          /landingPage/organization/results_mobile.png 500w,
          /landingPage/organization/results_mobile.png 584w
        `,
        bottomVector: `
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955f26a48585ba636929_bottom%20vector%20container-p-500.png 500w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955f26a48585ba636929_bottom%20vector%20container-p-800.png 800w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d955f26a48585ba636929_bottom%20vector%20container.png 1168w
        `,
        mid: `
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db85847919360ccd94e92_mid-p-500.png 500w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db85847919360ccd94e92_mid-p-800.png 800w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db85847919360ccd94e92_mid-p-1080.png 1080w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db85847919360ccd94e92_mid.png 1292w
        `,
        top: `
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858326fae24dca5130d_top-p-500.png 500w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858326fae24dca5130d_top-p-800.png 800w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858326fae24dca5130d_top-p-1080.png 1080w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858326fae24dca5130d_top.png 1300w
        `,
        bottom: `
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858ae19b91345718dd6_bottom-p-500.png 500w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858ae19b91345718dd6_bottom-p-800.png 800w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858ae19b91345718dd6_bottom-p-1080.png 1080w,
          https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db858ae19b91345718dd6_bottom.png 1292w
        `
      },
      discoveryImages: [
        {
          src: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db74f31e5b44459586437_discoveries-1.png',
          alt: 'discoveries as tiles',
          ariaHidden: true,
          srcset: `
            https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db74f31e5b44459586437_discoveries-1-p-500.png 500w,
            https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db74f31e5b44459586437_discoveries-1-p-800.png 800w,
            https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db74f31e5b44459586437_discoveries-1-p-1080.png 1080w,
            https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609db74f31e5b44459586437_discoveries-1.png 1188w
          `
        },
        // Add other discovery images similarly
      ]
    }
  }
}
</script>

<style scoped>
@import '/resources/css/ForOrganizations.css';
@import '/resources/css/global.css';
</style>
