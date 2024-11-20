<template>
    <section id="content" class="home__splash wf-section">
        <BackgroundAnimation />
        <div class="home__splashinner">
            <HeroImages />
            <HeroCallout />
        </div>
    </section>
</template>

<script setup>
import BackgroundAnimation from './Hero/BackgroundAnimation.vue';
import HeroImages from './Hero/HeroImages.vue';
import HeroCallout from './Hero/HeroCallout.vue';
</script>

<style scoped>
@import '/public/css/Home.css';
</style>