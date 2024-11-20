<template>
    <div class="home__splash__callout" ref="callout">
        <div class="home__spash__callout__inner">
            <h1 class="home__splash__callout__h1" ref="title">{{ __('navigation.unlock_future') }}</h1>
            <p class="home__splash__callout__p" ref="text">{{ __('navigation.splash_callout') }}<br></p>
            <div class="dynabutton">
                <div class="dynabutton__embed w-embed">
                    <Link :href="route('main-test')" class="alansbutt w-button" ref="button">{{ __('navigation.get_started') }}</Link>
                </div>
            </div>
            <Link :href="route('Career-Test')" class="alanslink" ref="link">{{ __('navigation.learn_more') }}</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import anime from 'animejs/lib/anime.es.js';
import { onMounted, ref } from 'vue';
import __ from '@/lang';

const title = ref(null);
const text = ref(null);
const button = ref(null);
const link = ref(null);

onMounted(() => {
    // Staggered text animations
    anime.timeline({
        duration: 800,
        easing: 'easeOutSine'
    })
    .add({
        targets: title.value,
        translateY: [20, 0],
        opacity: [0, 1],
    })
    .add({
        targets: text.value,
        translateY: [20, 0],
        opacity: [0, 1],
    }, '-=600')
    .add({
        targets: [button.value, link.value],
        translateY: [20, 0],
        opacity: [0, 1],
        delay: anime.stagger(100)
    }, '-=400');

    // Button hover animation
    button.value.addEventListener('mouseenter', () => {
        anime({
            targets: button.value,
            scale: 1.05,
            duration: 200,
            easing: 'easeOutElastic(1, .5)'
        });
    });

    button.value.addEventListener('mouseleave', () => {
        anime({
            targets: button.value,
            scale: 1,
            duration: 200,
            easing: 'easeOutElastic(1, .5)'
        });
    });
});
</script>
