<template>
    <!-- SVG background with morphing shapes -->
    <div class="background-animation">
        <svg width="100%" height="100%" viewBox="0 0 1200 400" preserveAspectRatio="none">
            <path class="morph-path" fill="rgba(0,0,0,0.1)" d="M0,0 C150,100 300,0 450,100 C600,200 750,100 900,100 L900,400 L0,400 Z" />
            <path class="morph-path2" fill="rgba(0,0,0,0.05)" d="M0,100 C200,150 400,50 600,150 C800,250 1000,150 1200,200 L1200,400 L0,400 Z" />
        </svg>
    </div>
</template>

<script setup>
import anime from 'animejs/lib/anime.es.js';
import { onMounted } from 'vue';

onMounted(() => {
    // Morphing background animation
    anime({
        targets: '.morph-path',
        d: [
            { value: 'M0,0 C150,100 300,0 450,100 C600,200 750,100 900,100 L900,400 L0,400 Z' },
            { value: 'M0,50 C150,150 300,50 450,150 C600,250 750,150 900,150 L900,400 L0,400 Z' },
            { value: 'M0,100 C150,0 300,100 450,0 C600,100 750,0 900,0 L900,400 L0,400 Z' }
        ],
        duration: 8000,
        easing: 'easeInOutSine',
        loop: true,
        direction: 'alternate'
    });

    anime({
        targets: '.morph-path2',
        d: [
            { value: 'M0,100 C200,150 400,50 600,150 C800,250 1000,150 1200,200 L1200,400 L0,400 Z' },
            { value: 'M0,150 C200,50 400,150 600,50 C800,150 1000,50 1200,150 L1200,400 L0,400 Z' },
            { value: 'M0,50 C200,200 400,100 600,200 C800,100 1000,200 1200,100 L1200,400 L0,400 Z' }
        ],
        duration: 10000,
        delay: 100,
        easing: 'easeInOutSine',
        loop: true,
        direction: 'alternate'
    });
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

.morph-path, .morph-path2 {
    transform-origin: center;
    will-change: d;
}
</style>
