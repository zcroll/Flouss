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
         

          

            <!-- Title -->
            <h1 class="text-4xl font-light my-4 text-shadow-md">You are a {{archetype.name}}</h1>

            <!-- Description -->
            <p class="text-lg text-shadow-sm">{{archetype.adjectives}}</p>

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
{{archetype.description}}                        </p>
                     
                    </div>
                    <!-- Scores Section -->
                    <div class="bg-gradient-to-b from-black to-black/100 p-6 mt-0">
                        <h3 class="Discovery__related__lil-subheading text-white/50 uppercase tracking-wider border-b border-white/18 pb-2 text-xs font-light">
                            Your scores
                        </h3>

                        <div class="grid gap-y-4 p-4">
                            <div v-for="(score, trait) in scores" :key="trait" class="Box block py-4 bg-transparent border-b border-white/18">
                                <div class="grid grid-cols-[minmax(0,1fr)_2fr] items-center gap-x-4">
                                    <div class="text-white text-sm font-light leading-[17px] min-w-[170px] pr-5">
                                        {{ trait }}
                                    </div>
                                    <figure class="relative">
                                        <span
                                            class="block h-[34px] rounded-full bg-gradient-to-r from-yellow-400 via-purple-500 to-purple-800 shadow-md"
                                            :style="{ width: `${score}%` }"
                                        >
                                            <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                                {{ score }}%
                                            </span>
                                        </span>
                                    </figure>
                                </div>
                            </div>
                            <PrimaryButton class="block text-center mt-5">
                                <Link :href="`results/${userId}/personality`" class="block w-full">
                                    read more
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
