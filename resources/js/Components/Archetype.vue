<template>
    <div>

        <div
            :class="[
                'Dialog-discovery bg-cover bg-center-bottom min-h-[370px] flex flex-col justify-end items-center text-center pb-8 px-8 relative text-white',
                showReadMore ? 'rounded-t-3xl' : 'rounded-3xl'
            ]"            :style="{
                backgroundImage: 'url(https://res.cloudinary.com/demo/image/upload/v1689803100/ai/hiker.jpg)',
                marginBottom: '-1px'
            }"
        >
            <!-- Buttons Section -->
            <div class="absolute top-5 right-5 flex space-x-2">
                <!-- Share Button -->
                <button
                    class="border border-gray-300 bg-white text-gray-900 text-sm font-medium px-4 py-2 rounded-lg flex items-center transition duration-200 hover:bg-gray-100"
                    aria-label="Click here to share your discovery on social media."
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        class="h-4 mr-2"
                    >
                        <path
                            d="M568.9 143.5l-150.9-138.2C404.8-6.773 384 3.039 384 21.84V96C241.2 97.63 128 126.1 128 260.6c0 54.3 35.2 108.1 74.08 136.2c12.14 8.781 29.42-2.238 24.94-16.46C186.7 252.2 256 224 384 223.1v74.2c0 18.82 20.84 28.59 34.02 16.51l150.9-138.2C578.4 167.8 578.4 152.2 568.9 143.5zM416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z"
                            fill="currentColor"
                        />
                    </svg>
                    Share
                </button>
            </div>

            <!-- Discovery Rarity Tag -->
            <span class="bg-black bg-opacity-50 rounded-md px-3 py-1 text-xs font-bold uppercase tracking-wide mt-16 flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="h-4 mr-2 text-yellow-500"
                >
                    <path
                        d="M378.7 32H133.3L256 182.7L378.7 32zM512 192l-107.4-141.3L289.6 192H512zM107.4 50.67L0 192h222.4L107.4 50.67zM244.3 474.9C247.3 478.2 251.6 480 256 480s8.653-1.828 11.67-5.062L510.6 224H1.365L244.3 474.9z"
                        fill="currentColor"
                    />
                </svg>
                ULTRA RARE—0.9% of Users
            </span>

            <!-- Title -->
            <h1 class="text-4xl font-light my-4 text-shadow-md">You are a {{archetype.toString()}}</h1>

            <!-- Description -->
            <p class="text-lg text-shadow-sm">Practical, Constructive, Persistent</p>

            <button @click="toggleReadMore" class="bg-gray-100 text-gray-900 text-sm font-medium rounded-3xl flex items-center justify-center h-9 w-9 transition duration-200 hover:bg-gray-100 absolute right-5 bottom-5">
                <i class="transition-transform duration-300 transform text-xl" :class="{ 'rotate-180': showReadMore }">&#9660;</i>
            </button>
        </div>

        <!-- Discovery Read More Section -->
        <transition name="slide-fade">
            <div v-show="showReadMore" class="bg-gray-600 rounded-b-3xl">
                <div class="Discovery__related__readmore shadow-[0px_-20px_6px_-1px_rgba(0,0,0,0.1)] text-white text-sm leading-[21px] overflow-hidden relative flex flex-col mt-0 rounded-3xl">
                    <div class="p-6 bg-gradient-to-t from-black/100 to-transparent">
                        <p class="Discovery__related__description mb-6 text-white/90 text-base leading-[25px] font-serif">
                            Innovators are best at using mindful action in their work. They like to work with a hands-on approach prefaced by deep thought...
                        </p>
                        <h3 class="Discovery__related__lil-subheading text-white/50 uppercase tracking-wider mb-2 mt-8 text-xs font-light">
                            The Science
                        </h3>
                        <p class="Discovery__related__description mb-6 text-white/100 text-base leading-[25px] font-serif">
                            A Realistic person feels most comfortable in the physical world...
                        </p>
                    </div>

                    <!-- Scores Section -->
                    <div class="bg-gradient-to-b from-black to-black/100 p-6 mt-0">
                        <h3 class="Discovery__related__lil-subheading text-white/50 uppercase tracking-wider border-b border-white/18 pb-2 text-xs font-light">
                            Your scores
                        </h3>

                        <div class="grid gap-y-4 p-4">
                            <!-- Score 1 -->
                            <div class="Box block py-4 bg-transparent border-b border-white/18">
                                <div class="grid grid-cols-[minmax(0,1fr)_2fr] items-center gap-x-4">
                                    <div class="text-white text-sm font-light leading-[17px] min-w-[170px] pr-5">
                                        {{ first_trait }}
                                    </div>
                                    <figure class="relative">
                                        <span
                                        class="block h-[34px] rounded-full bg-gradient-to-r from-yellow-400 via-purple-500 to-purple-800 shadow-md"
                                        :style="{ width: `${score2}%` }"
                                        >
                                            <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                                {{score1 }}%
                                            </span>
                                        </span>
                                    </figure>
                                </div>
                            </div>

                            <!-- Score 2 -->
                            <div class="Box block py-4 bg-transparent border-b border-white/18">
                                <div class="grid grid-cols-[minmax(0,1fr)_2fr] items-center gap-x-4">
                                    <div class="text-white text-sm font-light leading-[17px] min-w-[170px] pr-5">
                                        {{ second_trait }}
                                    </div>
                                    <figure class="relative">
                                        <span
                                            class="block h-[34px] rounded-full bg-gradient-to-r from-yellow-400 via-purple-500 to-purple-800 shadow-md"
                                            :style="{ width: `${score2}%` }"
                                        >
                                            <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                                {{ score2 }}%
                                            </span>
                                        </span>
                                    </figure>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';

const props = defineProps({
    archetype: {
        type: String,
        required: true,
        default: 'Default Title'
    },
    first_trait: {
        type: String,
        required: true,
        default: 'Default Title'
    },
    second_trait: {
        type: String,
        required: true,
        default: 'Default Title'
    },
    score1: {
        type: Array,
        required: true,
        default: 'Default Title'
    },
    score2: {
        type: Array,
        required: true,
        default: 'Default Title'
    }


})

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
        transform: translateY(-50px)
        ;
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
    animation: slide-in 0.5s ease forwards ;
}

.slide-fade-leave-active {
    animation: slide-out 0.5s ease backwards;
}
</style>
