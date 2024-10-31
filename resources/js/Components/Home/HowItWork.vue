<template>
    <link rel="stylesheet" href="/css/Home.css" />
    <div class="home">
        <section class="home__howitworks wf-section">
            <h2 class="home__howitworks__h">{{ __('how_it_works.title') }}</h2>
            <div class="scrollframe">
                <div class="scrollframe__nav">
                    <div class="scrollframe__nav__container">
                        <div class="scrollframe__nav__progress">
                            <div class="scrollframe__nav__progress-container">
                                <h2 class="scrollframe__nav__progress-h">{{ __('how_it_works.title') }}</h2>
                                <div class="scrollframe__nav__progress-bar progressbar">
                                    <div class="progressbar__track"></div>
                                    <div class="progressbar__track progressbar__track--progression"
                                         :style="{ height: progress + '%', transition: 'height 0.8s ease-in-out' }">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul role="list" class="scrollframe__nav__list">
                            <li v-for="(item, index) in contentItems"
                                :key="index"
                                class="scrollframe__nav__list-item">
                                <div class="scrollframe__nav__list-num">
                                    {{(index + 1).toString().padStart(2, "0")}}
                                </div>
                                <a :href="'#how-it-' + (index + 1)"
                                   @click.prevent="setCurrentStep(index + 1)"
                                   class="scollframe__nav__list-link w-inline-block"
                                   :class="{'w--current': currentStep === index + 1}">
                                    <div class="scrollframe__nav__list-label">{{ __(item.label) }}</div>
                                    <div class="scrollframe__nav__list-label scrollframe__nav__list-label--hidden">
                                        {{ __(item.hiddenLabel) }}
                                    </div>
                                    <p class="scrollframe__nav__list-desc">{{ __(item.description) }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="scrollframe__content" @scroll="handleScroll" ref="scrollContent">
                    <div v-for="(item, index) in contentItems"
                         :key="index"
                         :id="'how-it-' + (index + 1)"
                         class="scrollframe__content__block"
                         ref="contentBlocks">
                        <div class="scrollframe__nav__list-item scrollframe__nav__mobile-block-title">
                            <span class="scrollframe__nav__list-num">
                                {{(index + 1).toString().padStart(2, "0")}}
                            </span>
                            <span class="scrollframe__nav__list-label scrollframe__nav__list-label--hidden">
                                {{ __(item.hiddenLabel) }}
                            </span>
                        </div>
                        <p class="scrollframe__nav__list-desc scrollframe__nav__mobile-block-desc">
                            {{ __(item.description) }}
                        </p>
                        <img :src="item.image"
                             :alt="item.alt"
                             :width="item.width"
                             loading="lazy"
                             :sizes="item.sizes"
                             :srcset="item.srcset"
                             class="scrollframe__content__block-image lazy-loaded-image" />
                    </div>
                </div>
                <div class="scrollframe__script-embed w-embed w-script"></div>
            </div>
        </section>
    </div>
</template>

<script>
import {ref , onMounted} from "vue";
import __ from '@/lang';

export default {
    name: "ScrollFrame",
    setup() {
        const currentStep = ref(1);
        const progress = ref(33.33);
        const scrollContent = ref(null);
        const contentBlocks = ref([]);

        const setCurrentStep = (step) => {
            currentStep.value = step;
            progress.value = Math.round((step / 3) * 100);

            // Scroll to the selected content block
            const targetBlock = contentBlocks.value[step - 1];
            if (targetBlock) {
                targetBlock.scrollIntoView({ behavior: 'smooth' });
            }
        };

        const handleScroll = () => {
            if (!scrollContent.value) return;

            const scrollPosition = scrollContent.value.scrollTop;
            const containerHeight = scrollContent.value.clientHeight;

            // Find which block is most visible
            contentBlocks.value.forEach((block, index) => {
                const rect = block.getBoundingClientRect();
                const blockVisibility = rect.top + (rect.height / 2);

                if (blockVisibility > 0 && blockVisibility < containerHeight) {
                    currentStep.value = index + 1;
                    progress.value = Math.round(((index + 1) / 3) * 100);
                }
            });
        };

        const contentItems = ref([
            {
                label: 'how_it_works.answer_label',
                hiddenLabel: 'how_it_works.answer_hidden_label',
                description: 'how_it_works.answer_description',
                image: '/test_home.png',
                alt: 'The assessment',
                width: 1000,
                sizes: '(max-width: 479px) 100vw, (max-width: 767px) 93vw, (max-width: 991px) 100vw, 50vw',
                srcset: '/test_home.png 500w, /test_home.png 800w, /test_home.png 1080w, /test_home.png 2000w',
            },
            {
                label: 'how_it_works.discover_label',
                hiddenLabel: 'how_it_works.discover_hidden_label',
                description: 'how_it_works.discover_description',
                image: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456dabcf7670358c5c28a2_02%20(2).png',
                alt: 'Insights and discoveries',
                width: 700,
                sizes: '(max-width: 479px) 100vw, (max-width: 767px) 92vw, (max-width: 991px) 700px, 50vw',
                srcset: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456dabcf7670358c5c28a2_02%20(2)-p-500.png 500w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456dabcf7670358c5c28a2_02%20(2)-p-800.png 800w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456dabcf7670358c5c28a2_02%20(2)-p-1080.png 1080w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456dabcf7670358c5c28a2_02%20(2).png 2000w',
            },
            {
                label: 'how_it_works.explore_label',
                hiddenLabel: 'how_it_works.explore_hidden_label',
                description: 'how_it_works.explore_description',
                image: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456e2bb6bb1f3459fcc9d4_03%20(1).png',
                alt: 'Search our listings',
                width: 700,
                sizes: '(max-width: 479px) 100vw, (max-width: 767px) 92vw, (max-width: 991px) 700px, 50vw',
                srcset: 'https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456e2bb6bb1f3459fcc9d4_03%20(1)-p-500.png 500w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456e2bb6bb1f3459fcc9d4_03%20(1)-p-800.png 800w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5f456e2bb6bb1f3459fcc9d4_03%20(1).png 2000w',
            }
        ]);

        return {
            contentItems,
            currentStep,
            progress,
            setCurrentStep,
            handleScroll,
            scrollContent,
            contentBlocks
        };
    }
};
</script>

<style>
.scrollframe__content {
    overflow-y: auto;
    height: 100vh;
    scroll-behavior: smooth;
    scroll-snap-type: y mandatory;
    transition: all 1.2s ease-in-out;
}

.scrollframe__content__block {
    scroll-snap-align: start;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: all 1.2s ease-in-out;
}
</style>
