<template>
    <link rel="stylesheet" href="/css/Home.css" />
    <section id="content" class="home__splash wf-section">
        <!-- SVG background with morphing shapes -->
     

        <div class="home__splashinner">
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
            
            <div class="home__splash__callout" ref="callout">
              
                <div class="home__spash__callout__inner">
                    <h1 class="home__splash__callout__h1" ref="title">{{ __('navigation.unlock_future') }}</h1>
                    <p class="home__splash__callout__p" ref="text">{{ __('navigation.splash_callout') }}<br></p>
                    <div class="dynabutton">
                        <div class="dynabutton__embed w-embed">
                            <Link :href="route('main-test')" class="alansbutt w-button" ref="button">{{ __('navigation.get_started') }}</Link>
                        </div>
                    </div>
                    <a href="/career-test/" class="alanslink" ref="link">{{ __('navigation.learn_more') }}</a>
                </div>
            </div>
        </div>
    </section>
    <Steps />
    <HowItWork />
    <Gardien />
</template>
<script>
import Steps from '@/Components/Home/Steps.vue';
import HowItWork from '@/Components/Home/HowItWork.vue';
import Gardien from '@/Components/Home/Gardien.vue';
import { Link } from '@inertiajs/vue3';
import anime from 'animejs/lib/anime.es.js';
import { onMounted, ref } from 'vue';

export default {
    components: {
        Steps,
        HowItWork,
        Gardien,
        Link
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean
    },
    setup() {
        const phoneImage = ref(null);
        const womanImage = ref(null);
        const title = ref(null);
        const text = ref(null);
        const button = ref(null);
        const link = ref(null);

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

        return {
            phoneImage,
            womanImage,
            title,
            text,
            button,
            link
        };
    }
}
</script>
