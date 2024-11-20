<template>
    <div class="home__splash__image">
        <img src="/landingPage/home/home_list_jobs_phone.png" 
             alt="Mockup of the career test assessment" 
             loading="lazy"
             ref="phoneImage" 
             class="home__splash__image__img">
        <img src="/landingPage/home/woman_home.jpeg" 
             alt="Unlock the future you" 
             loading="eager" 
             id="hero-preload"
             ref="womanImage" 
             class="home__splash__image__bkg w-node-_83b9c443-2bd2-273a-4c48-42013d1edf0e-1b06e9c1">
    </div>
</template>

<script setup>
import anime from 'animejs/lib/anime.es.js';
import { onMounted, ref } from 'vue';

const phoneImage = ref(null);
const womanImage = ref(null);

onMounted(() => {
    // Floating animation for phone
    anime({
        targets: phoneImage.value,
        translateY: [-10, 10],
        duration: 2000,
        direction: 'alternate',
        loop: true,
        easing: 'easeInOutQuad'
    });

    // Fade in for woman image
    anime({
        targets: womanImage.value,
        opacity: [0, 1],
        duration: 1000,
        easing: 'easeOutSine'
    });
});
</script>
