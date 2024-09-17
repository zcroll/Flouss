<template>
    <div class="w-full relative" :class="{ 'cursor-not-allowed': comingSoon }">
        <!-- Coming Soon Overlay -->


        <div
            :class="[
                'Dialog-discovery bg-cover bg-center-bottom min-h-[370px] sm:min-h-[400px] md:min-h-[450px] flex flex-col justify-end items-center text-center pb-8 px-4 sm:px-6 md:px-8 relative text-white',
                showReadMore ? 'rounded-t-3xl' : 'rounded-3xl',
                comingSoon ? 'opacity-50' : ''
            ]"
            :style="{
                backgroundImage: 'url(https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_400,q_auto,w_640/v1558730393/e2cmhbjek730smx9odcv.jpg)',
                marginBottom: '-1px'
            }"
        >
        <div v-if="comingSoon" class="absolute inset-0 z-10 flex items-center justify-center">
            <div class="text-red-600 font-bold text-4xl  border-4 border-red-600 p-4 bg-white bg-opacity-70">
                COMING SOON
            </div>
        </div>
            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-light my-2 sm:my-3 md:my-4 text-shadow-md">
                {{ comingSoon ? 'Your Broad Interests' : `You are a ${archetype.name}` }}
            </h1>

            <!-- Description -->
            <p :class="['text-base sm:text-lg md:text-xl text-shadow-sm', { 'blur-sm': comingSoon }]">
                {{ archetype.adjectives }}
            </p>

            <button @click="toggleReadMore" class="bg-gray-100 text-gray-900 text-sm font-medium rounded-full flex items-center justify-center h-8 w-8 sm:h-9 sm:w-9 transition duration-200 hover:bg-gray-200 absolute right-3 sm:right-4 md:right-5 bottom-3 sm:bottom-4 md:bottom-5">
                <i class="transition-transform duration-300 transform text-lg sm:text-xl" :class="{ 'rotate-180': showReadMore }">&#9660;</i>
            </button>
        </div>

        <!-- Discovery Read More Section -->
        <transition name="slide-fade">
            <div v-show="showReadMore" class="bg-gray-600 rounded-b-3xl">
                <div class="Discovery__related__readmore shadow-[0px_-20px_6px_-1px_rgba(0,0,0,0.1)] text-white text-sm leading-[21px] overflow-hidden relative flex flex-col mt-0 rounded-3xl">
                    <div class="p-4 sm:p-5 md:p-6 bg-gradient-to-t from-black/100 to-transparent">
                        <p :class="['Discovery__related__description mb-4 sm:mb-5 md:mb-6 text-white/90 text-sm sm:text-base leading-[22px] sm:leading-[25px] font-serif', { 'blur-sm': comingSoon }]">
                            {{ archetype.description }}
                        </p>
                    </div>
                    <!-- Scores Section -->
                    <div class="bg-gradient-to-b from-black to-black/100 p-4 sm:p-5 md:p-6 mt-0">
                        <h3 class="Discovery__related__lil-subheading text-white/50 uppercase tracking-wider border-b border-white/18 pb-2 text-xs font-light">
                            Your scores
                        </h3>

                        <div class="grid gap-y-3 sm:gap-y-4 p-2 sm:p-3 md:p-4">
                            <div v-for="(score, trait) in scores" :key="trait" class="Box block py-3 sm:py-4 bg-transparent border-b border-white/18">
                                <div class="grid grid-cols-[minmax(0,1fr)_2fr] items-center gap-x-2 sm:gap-x-3 md:gap-x-4">
                                    <div :class="['text-white text-xs sm:text-sm font-light leading-[15px] sm:leading-[17px] min-w-[120px] sm:min-w-[150px] md:min-w-[170px] pr-2 sm:pr-3 md:pr-5', { 'blur-sm': comingSoon }]">
                                        {{ trait }}
                                    </div>
                                    <figure class="relative">
                                        <span
                                            class="block h-[28px] sm:h-[32px] md:h-[34px] rounded-full bg-gradient-to-r from-yellow-400 via-purple-500 to-purple-800 shadow-md"
                                            :style="{ width: `${score}%` }"
                                        >
                                            <span :class="['text-gray-200 font-medium text-sm sm:text-base md:text-lg leading-[28px] sm:leading-[32px] md:leading-[34px] ml-2 absolute top-1/2 transform -translate-y-1/2', { 'blur-sm': comingSoon }]">
                                                {{ score }}%
                                            </span>
                                        </span>
                                    </figure>
                                </div>
                            </div>
                            <PrimaryButton class="block text-center mt-4 sm:mt-5">
                                <Link :href="comingSoon ? '#' : `results/${userId}/personality`" class="block w-full">
                                    {{ 'Coming Soon'  }}
                                </Link>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import {Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    archetype: {
        type: Object,
        required: true,
    },
    scores: {
        type: Object,
        required: true,
    },
    userId: {
        type: Number,
        required: true,
    },
    comingSoon: {
        type: Boolean,
        default: false
    }
});

const showReadMore = ref(false);

const toggleReadMore = () => {
    showReadMore.value = !showReadMore.value;
};
</script>

<style scoped>
.text-shadow-md {
    text-shadow: rgba(0, 0, 0, 0.06) 0px 1px 7px;
}

.text-shadow-sm {
    text-shadow: rgba(0, 0, 0, 0.06) 0px 1px 5px;
}

.Dialog-discovery {
    margin-bottom: -20px;
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0px);
    }
}

@keyframes slide-out {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-1px);
    }
}

.slide-fade-enter-active {
    animation: slide-in 0.5s ease forwards;
}

.slide-fade-leave-active {
    animation: slide-out 0.5s ease backwards;
}

.blur-sm {
    filter: blur(4px);
}

.opacity-50 {
    opacity: 0.5;
}

.cursor-not-allowed {
    cursor: not-allowed;
}

.cursor-not-allowed * {
    pointer-events: none;
}
</style>
