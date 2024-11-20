<template>
  <div class="mehero mehero--discord">
    <div class="mehero__wrap mehero__wrap--orgs">
      <div class="mehero__wrap__inner">
        <div class="mehero__text"
             data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c20"
             style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
          <h1 class="productive-heading-07 override">
            {{ __('for-organizations.CareerExplorer_for_Organizations') }} <br>
          </h1>
          <div class="mehero__text__floating">
            <p class="productive-heading-04">
              {{ __('for-organizations.Empower_your_students') }}<br>
            </p>
            <p class="body-short-02">
              {{ __('for-organizations.A_self_serve') }}
            </p>
          </div>
        </div>
        <div class="mehero__img mehero__img--discord mehero__img--orgs"
             data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c34"
             style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
          <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits.png"
               loading="lazy"
               sizes="(max-width: 991px) 100vw, 45vw"
               width="570"
               alt="Gen.Z for Organizations"
               srcset="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-500.png 500w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-800.png 800w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-1080.png 1080w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits.png 1140w"
               class="mehero__img__img mehero__img__img--discord">
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import anime from 'animejs/lib/anime.es.js';
import __ from '@/lang';

const initBackgroundAnimation = () => {
  anime({
    targets: '.line-drawing, .draw-text',
    strokeDashoffset: [anime.setDashoffset, 0],
    easing: 'easeInOutSine',
    duration: 1500,
    delay: function(el, i) { return i * 250 },
    direction: 'alternate',
    loop: true
  });

  const elements = document.querySelectorAll('.element');

  elements.forEach((el, i) => {
    anime({
      targets: el,
      translateX: anime.stagger(10),
      translateY: anime.stagger(10),
      rotate: anime.stagger([0, 360]),
      duration: 4000,
      delay: i * 100,
      easing: 'easeInOutQuad',
      loop: true,
      direction: 'alternate',
      autoplay: true,
      motion: {
        path: i % 2 === 0 ? '#motionPath1' : '#motionPath2',
        autoRotate: true,
        start: i / elements.length,
        end: 1 + (i / elements.length)
      }
    });
  });
}

onMounted(() => {
  initBackgroundAnimation();
});
</script>

<style scoped>
.background-animation {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
}

.line-drawing, .draw-text {
  fill: none;
  stroke: #333;
  stroke-width: 2;
}

.element {
  transform-origin: center;
  will-change: transform;
}

.mehero--discord {
  position: relative;
  overflow: hidden;
}
</style>
