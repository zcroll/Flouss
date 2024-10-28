<template>
    <link rel="stylesheet" href="/css/Home.css" />
    <section id="content" class="home__splash wf-section">
        <div class="home__splashinner">
            <div class="home__splash__image">
                <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd8f835171252525f449e0_5e7b93af68aaa819e1033dee_hopme-phone%20(1).png" 
                     alt="Mockup of the career test assessment" 
                     loading="lazy" 
                     class="home__splash__image__img">
                <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd900138e2fcf93c9453af_5e7b93afd4a2af6597564b30_home-bg-p-1080.jpeg" 
                     alt="Unlock the future you" 
                     loading="eager" 
                     id="hero-preload" 
                     class="home__splash__image__bkg w-node-_83b9c443-2bd2-273a-4c48-42013d1edf0e-1b06e9c1">
            </div>
            <div class="home__splash__callout">
                <div class="home__spash__callout__inner">
                    <h1 class="home__splash__callout__h1">Unlock the future you</h1>
                    <p class="home__splash__callout__p">Say hello to CareerExplorer, the world's leading career advancement platform.<br></p>
                    <div class="dynabutton">
                        <div class="dynabutton__embed w-embed">
                            <a href="/members/register/deferred/" 
                               class="alansbutt w-button" 
                               data-dialog-target="dialog-sign-up">Get Started</a>
                        </div>
                    </div>
                    <a href="/career-test/" class="alanslink">Learn More About CareerExplorer</a>
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
export default {
    components: {
        Steps,
        HowItWork,
        Gardien
    }
}
</script>
