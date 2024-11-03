<template>
    <link rel="stylesheet" href="/css/Home.css" />
    <section id="content" class="home__splash wf-section">
        <div class="home__splashinner">
            <div class="home__splash__image">
                <img src="/landingPage/home/home_list_jobs_phone.png" 
                     alt="Mockup of the career test assessment" 
                     loading="lazy" 
                     class="home__splash__image__img" >
                <img src="/landingPage/home/woman_home.jpeg" 
                     alt="Unlock the future you" 
                     loading="eager" 
                     id="hero-preload" 
                     class="home__splash__image__bkg w-node-_83b9c443-2bd2-273a-4c48-42013d1edf0e-1b06e9c1">
            </div>
            <div class="home__splash__callout">
                <div class="home__spash__callout__inner">
                    <h1 class="home__splash__callout__h1">{{ __('navigation.unlock_future') }}</h1>
                    <p class="home__splash__callout__p">{{ __('navigation.splash_callout') }}<br></p>
                    <div class="dynabutton">
                        <div class="dynabutton__embed w-embed">
                            <Link :href="route('main-test')" class="alansbutt w-button">{{ __('navigation.get_started') }}</Link>
                        </div>
                    </div>
                    <a href="/career-test/" class="alanslink">{{ __('navigation.learn_more') }}</a>
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
    }
}
</script>
