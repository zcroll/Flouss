<template>
    <AppLayout :headTitle="occupation.name">
        <StickySidebar :slug="occupation.slug" :title="occupation.name" :image="occupation.image">
            <div class="w-full lg:w-1/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">

                <p class="custom-heading">What personality traits do doctors have?</p>

                <aside id="table-of-contents-container" class="block">
                    <div
                        id="table-of-contents"
                        class="table-of-contents rounded-lg my-4 w-full bg-[#f2e1d5] text-gray-900 relative p-9"
                        role="directory"
                        tabindex="0"
                        title="Table of contents"
                    >
                        <p class="custom-heading" >
                            In this article:
                        </p>
                        <ol class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight">
                            <li class="relative mb-0 text-xl leading-10 hover:underline sm:text-base md:text-lg lg:text-xl">

                            <a
                                    href="#holland-codes"
                                    class="trait-type"
                                >
                                    Primary interests (Holland Codes)
                                </a>
                            </li>
                            <li class="relative mb-0 text-xl leading-10 hover:underline sm:text-base md:text-lg lg:text-xl">
                                <a
                                    href="#big-five"
                                    class="trait-type"
                                >
                                    Broad personality traits (Big 5)
                                </a>
                            </li>
                        </ol>
                    </div>
                </aside>

                <!-- Holland Codes Section -->
                <section id="holland-codes">
                    <div class="relative flex" id="holland-codes">
                        <!-- Button with SVG icon to trigger the popup -->
                        <button @click="toggleDialogHolands" class=" text-xl relative cursor-pointer text-gray-700 font-bold flex items-center">
                            Holland Codes
                            <i class="ml-1">
                                <svg
                                    class="w-4 h-4"
                                    aria-hidden="true"
                                    focusable="false"
                                    role="img"
                                    viewBox="0 0 512 512"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 400c-18 0-32-14-32-32s13.1-32 32-32c17.1 0 32 14 32 32S273.1 400 256 400zM325.1 258L280 286V288c0 13-11 24-24 24S232 301 232 288V272c0-8 4-16 12-21l57-34C308 213 312 206 312 198C312 186 301.1 176 289.1 176h-51.1C225.1 176 216 186 216 198c0 13-11 24-24 24s-24-11-24-24C168 159 199 128 237.1 128h51.1C329 128 360 159 360 198C360 222 347 245 325.1 258z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </i>
                        </button>

                        <!-- Dialog / Popup -->
                        <div v-if="isDialogVisibleHolands" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <!-- Background overlay -->
                            <div class="fixed inset-0 bg-black opacity-50" @click="toggleDialogHolands"></div>

                            <!-- Dialog content -->

                            <div class="bg-white rounded-3xl p-6 shadow-lg max-h-[91vh] w-full max-w-lg relative overflow-auto">

                                <button
                                    class="absolute top-4 right-4 bg-white border-none rounded-full shadow-lg p-2 flex justify-center items-center hover:shadow-md focus:outline-none transition-transform duration-200"
                                    @click="toggleDialogHolands"
                                    title="Close"
                                >
                                    <svg
                                        class="w-5 h-5 text-black"
                                        aria-hidden="true"
                                        focusable="false"
                                        role="img"
                                        viewBox="0 0 320 512"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M315.3 411.3c-6.253 6.253-16.37 6.253-22.63 0L160 278.6l-132.7 132.7c-6.253 6.253-16.37 6.253-22.63 0c-6.253-6.253-6.253-16.37 0-22.63L137.4 256L4.69 123.3c-6.253-6.253-6.253-16.37 0-22.63c6.253-6.253 16.37-6.253 22.63 0L160 233.4l132.7-132.7c6.253-6.253 16.37-6.253 22.63 0c6.253 6.253 6.253 16.37 0 22.63L182.6 256l132.7 132.7C321.6 394.9 321.6 405.1 315.3 411.3z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                </button>


                                <h1 class="text-2xl font-light text-black mb-4">Holland Codes</h1>


                                <p class="text-base text-gray-700">
                                    Holland Codes are a set of traits that measure your interest in broadly defined groups of activities. Because
                                    they’re broad, and because they’re specifically designed for career selection, Holland Codes are a good
                                    high-level indicator for the kinds of careers you would enjoy. For more information on Holland Codes, visit the
                                    related <a href="https://www.careerexplorer.com/faqs/assessment-science/what-are-holland-codes/" class="font-bold text-gray-900 underline">FAQs page</a>.
                                </p>
                            </div>

                        </div>
                    </div>
                    <div v-for="(trait, index) in hollandCodeTraits" :key="trait.id" class="Box block py-4 bg-transparent border-b border-white/18">
                        <div class="grid grid-cols-[minmax(0,1fr)_2fr] items-center gap-x-4">
                            <div class="text-gray-700 cursor-context-menu font-light leading-[17px] min-w-[170px] pr-5">
                                {{ trait.trait_name }}
                            </div>
                            <figure class="relative">
                                <span
                                    class="block h-[34px] rounded-full bg-gradient-to-r from-yellow-600 via-purple-500 to-purple-800 shadow-md"
                                    :style="{ width: `${trait.trait_score * 100 || 0}%` }"
                                >
                                    <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                        {{ trait.trait_score ? (trait.trait_score * 100).toFixed(0) : 'N/A' }}%
                                    </span>
                                </span>
                            </figure>
                        </div>
                    </div>
                </section>


                <section id="big-five">
                    <div class="relative flex">
                        <!-- Button with SVG icon to trigger the popup -->
                        <button @click="toggleDialogBigFive" class=" text-xl relative cursor-pointer text-gray-700 font-bold flex items-center">
                            Big Five
                            <i class="ml-1">
                                <svg
                                    class="w-4 h-4"
                                    aria-hidden="true"
                                    focusable="false"
                                    role="img"
                                    viewBox="0 0 512 512"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 400c-18 0-32-14-32-32s13.1-32 32-32c17.1 0 32 14 32 32S273.1 400 256 400zM325.1 258L280 286V288c0 13-11 24-24 24S232 301 232 288V272c0-8 4-16 12-21l57-34C308 213 312 206 312 198C312 186 301.1 176 289.1 176h-51.1C225.1 176 216 186 216 198c0 13-11 24-24 24s-24-11-24-24C168 159 199 128 237.1 128h51.1C329 128 360 159 360 198C360 222 347 245 325.1 258z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </i>
                        </button>

                        <!-- Dialog / Popup -->
                        <div v-if="isDialogVisibleBigFive" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <!-- Background overlay -->
                            <div class="fixed inset-0 bg-black opacity-50" @click="toggleDialogBigFive"></div>

                            <!-- Dialog content -->
                            <div class="bg-white rounded-3xl p-6 shadow-lg max-h-[91vh] w-full max-w-lg relative overflow-auto">
                                <!-- Close button -->
                                <button
                                    class="absolute top-4 right-4 bg-white border-none rounded-full shadow-lg p-2 flex justify-center items-center hover:shadow-md focus:outline-none transition-transform duration-200"
                                    @click="toggleDialogBigFive"
                                    title="Close"
                                >
                                    <svg
                                        class="w-5 h-5 text-black"
                                        aria-hidden="true"
                                        focusable="false"
                                        role="img"
                                        viewBox="0 0 320 512"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M315.3 411.3c-6.253 6.253-16.37 6.253-22.63 0L160 278.6l-132.7 132.7c-6.253 6.253-16.37 6.253-22.63 0c-6.253-6.253-6.253-16.37 0-22.63L137.4 256L4.69 123.3c-6.253-6.253-6.253-16.37 0-22.63c6.253-6.253 16.37-6.253 22.63 0L160 233.4l132.7-132.7c6.253-6.253 16.37-6.253 22.63 0c6.253 6.253 6.253 16.37 0 22.63L182.6 256l132.7 132.7C321.6 394.9 321.6 405.1 315.3 411.3z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                </button>

                                <!-- Dialog title -->
                                <h1 class="text-2xl font-light text-black mb-4" id="big-five">Big Five</h1>

                                <!-- Dialog body content -->
                                <p class="text-base text-gray-700">
                                    The Big Five are measures of your temperament (as opposed to interests or values) across five broad traits. The Big Five is the most widely accepted measure of personality in the scientific and psychological community. It is theorized that most other stable personality traits we can think of, such as warmth or modesty can largely be predicted with these more-broad traits. The Big Five is the basis for many other psychological inventories. For more information on the Big Five, visit the related  <a href="https://en.wikipedia.org/wiki/Big_Five_personality_traits" class="font-bold text-gray-900 underline">Wiki Page</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-for="(trait, index) in bigFiveTraits" :key="trait.id" class="Box block py-4 bg-transparent border-b border-white/18">
                        <div class="grid grid-cols-[minmax(0,2fr)_2fr] items-center gap-x-4">
                            <div class="text-gray-700 cursor-context-menu font-light leading-[17px] min-w-[170px] pr-5">
                                {{ trait.trait_name }}
                            </div>
                            <figure class="relative">
                                <span
                                    class="block h-[34px] rounded-full bg-gradient-to-r from-yellow-600 via-purple-500 to-purple-800 shadow-md"
                                    :style="{ width: `${trait.trait_score * 100 || 0}%` }"
                                >
                                    <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                        {{ trait.trait_score ? (trait.trait_score * 100).toFixed(0) : 'N/A' }}%
                                    </span>
                                </span>
                            </figure>
                        </div>
                    </div>
                </section>

                <!-- Personality Details Section -->
                <h2 class="text-2xl font-semibold text-gray-800">Personality Details:</h2>
                <ul>
                    <li v-for="(detail, index) in personalityDetails" :key="index" class="text-gray-700 pt-4">
                        {{ detail.description }}
                    </li>
                </ul>

            </div>
        </StickySidebar>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

// Define props using defineProps
const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    personalityTraits: {
        type: Array,
        required: true,
    },
    personalityDetails: {
        type: Array,
        required: true,
    },
});

// Reactive data using ref
const isDialogVisibleBigFive = ref(false);
const isDialogVisibleHolands = ref(false);

// Computed properties
const hollandCodeTraits = computed(() => {
    return props.personalityTraits.filter(trait => trait.trait_type === 'holland_codes');
});

const bigFiveTraits = computed(() => {
    return props.personalityTraits.filter(trait => trait.trait_type === 'big_five');
});

// Methods
const toggleDialogBigFive = () => {
    isDialogVisibleBigFive.value = !isDialogVisibleBigFive.value;
};
const toggleDialogHolands = () => {
    isDialogVisibleHolands.value = !isDialogVisibleHolands.value;
};
</script>
<style>
.custom-heading {
    box-sizing: border-box;
    margin: 0px 0px 10px;
    font-weight: 300;
    padding-top: 0px;
    letter-spacing: -0.3px;
    font-size: 32px;
    line-height: 36px;
    margin-bottom: 30px;
    font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;
}

.trait-type{
    box-sizing: border-box;
    background-color: transparent;
    text-decoration: none;
    transition:
        color 0.2s ease-in-out, border-bottom 0.2s ease-in-out;
    border-bottom: 0px;
    color: rgb(36, 36, 36);
    font-weight: 300;
    font-family:
        aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}


</style>
