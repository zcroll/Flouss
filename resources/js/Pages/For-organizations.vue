<template>
  <link rel="stylesheet" href="/css/ForOrganizations.css">
  <Header />
  <div class="landinghero wf-section">
    <div id="orbs" aria-hidden="true" class="orbs">
      <div class="html-embed-orbs-opacity-onscroll">
      </div>
      <div class="html-embed-orbs-rotate">
        <div id="blue"></div>
        <div id="red"></div>
        <div id="green"></div>

      </div>
    </div>
    <div class="mehero mehero--discord">
      <div class="mehero__wrap mehero__wrap--orgs">
        <div class="mehero__wrap__inner">
          <div class="mehero__text" 
               data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c20" 
               style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
            <h1 class="productive-heading-07 override">
              CareerExplorer for Organizations<br>
            </h1>
            <div class="mehero__text__floating">
              <p class="productive-heading-04">
                Empower your students with comprehensive career discovery tools.<br>
              </p>
              <p class="body-short-02">
                A self-serve platform for universities, high schools, independent educational consultants, career counselors, non-profits, and other organizations.
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

onMounted(() => {
  window.addEventListener("scroll", handleOrbs);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleOrbs);
});
</script>
