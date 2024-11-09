<template>
  <link rel="stylesheet" href="/css/ForOrganizations.css">
  <Header />
  <div class="landinghero wf-section">
    <div class="mehero mehero--discord">
      <svg class="background-animation" width="800" height="800" viewBox="0 0 800 800">
        <path class="line-drawing" 
              d="M100,200 Q400,100 700,200" 
              fill="none" 
              stroke="#333"
              stroke-width="2"/>

        <path id="motionPath1" 
              d="M100,400 C100,100 700,100 700,400 S100,700 100,400 Z" 
              fill="none" 
              stroke="none"/>
              
        <path id="motionPath2"
              d="M200,300 C200,500 600,500 600,300 S200,100 200,300 Z"
              fill="none"
              stroke="none"/>

        <g class="moving-elements">
          <circle class="element" r="15" fill="#5E5141" opacity="0.65"/>
          <rect class="element" width="30" height="30" fill="#E4D5C6" opacity="0.5"/>
          <path class="element" 
                d="M-10,-10 L10,-10 L0,10 Z" 
                fill="#7B6E61" 
                opacity="0.4"/>
        </g>

        <text class="draw-text" x="400" y="400" text-anchor="middle" fill="none" stroke="#333">
          CareerExplorer for Organizations
        </text>
      </svg>

      <div class="mehero__wrap mehero__wrap--orgs">
        <div class="mehero__wrap__inner">
          <div class="mehero__text" 
               data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c20" 
               style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
            <h1 class="productive-heading-07 override">
              {{ __('CareerExplorer for Organizations') }} <br>
            </h1>
            <div class="mehero__text__floating">
              <p class="productive-heading-04">
                {{ __('Empower your students with comprehensive career discovery tools.') }}<br>
              </p>
              <p class="body-short-02">
                {{ __('A self-serve platform for universities, high schools, independent educational consultants, career counselors, non-profits, and other organizations.') }}
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
                 alt="CareerExplorer for Organizations"
                 srcset="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-500.png 500w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-800.png 800w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-1080.png 1080w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits.png 1140w"
                 class="mehero__img__img mehero__img__img--discord">
          </div>
        </div>
      </div>
    </div>
    <WorldClass />
  </div>
  <Benefits />
  <Experience />
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import Header from '@/Components/Home/Navbar/Header.vue';
import WorldClass from '@/Components/Home/ForOrganizations/WorldClass.vue';
import Benefits from '@/Components/Home/ForOrganizations/Benefits.vue';
import Experience from '@/Components/Home/ForOrganizations/Experience.vue';
import anime from 'animejs/lib/anime.es.js';
import __ from '@/lang';

const handleOrbs = () => {
  const w = document.getElementById("welcome");
  const orbs = document.getElementById("orbs");
  if (w && orbs) {
    if (window.scrollY >= w.getBoundingClientRect().height) {
      orbs.style.opacity = 0.4;
    } else {
      orbs.style.opacity = 0.6;
    }
  }
}

const initBackgroundAnimation = () => {
  // Line drawing animation
  anime({
    targets: '.line-drawing, .draw-text',
    strokeDashoffset: [anime.setDashoffset, 0],
    easing: 'easeInOutSine',
    duration: 1500,
    delay: function(el, i) { return i * 250 },
    direction: 'alternate',
    loop: true
  });

  // Motion path animations
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
  window.addEventListener("scroll", handleOrbs);
  initBackgroundAnimation();
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleOrbs);
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
