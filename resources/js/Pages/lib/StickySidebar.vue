<template>
    <div class="container mx-auto mt-[-20px] flex flex-col-reverse lg:flex-row">
        <div class="w-full lg:w-2/3">
            <slot />
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 lg:mb-0 lg:pl-8 pb-5 pt-8">
            <!-- Mobile Navigation Bar (Fixed Bottom) -->
            <div v-if="isSmallScreen" 
                 class="fixed bottom-0 left-0 right-0 bg-[#353535] text-white shadow-lg z-50 px-4 py-3">
                <div class="flex items-center justify-between">
                    <img :src="image" alt="Icon" class="w-10 h-10 rounded-full"/>
                    <h3 class="text-sm font-bold ml-2 flex-1 truncate">{{ title }}</h3>
                    <FavoriteButton
                        :model-id="id"
                        :model-type="type"
                        :initial-is-favorited="isFavorited"
                        :show-label="false"
                        class="ml-2"
                        :key="id"
                    />
                </div>
                <div class="overflow-x-auto scrollbar-hide mt-3">
                    <div class="flex space-x-2 pb-2">
                        <Link
                            v-for="(link, index) in links"
                            :key="index"
                            :href="link.url"
                            :class="{
                                'bg-amber-500': $page.url === link.url,
                                'bg-stone-700': $page.url !== link.url
                            }"
                            class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all"
                        >
                            {{ __(
                                `stickybar.${link.text.toLowerCase().replace(" ", "_")}`
                            ) }}
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Desktop Sticky Sidebar -->
            <div v-else
                 class="sticky top-[100px] text-white rounded-2xl p-6 overflow-hidden bg-[#353535]">
                <div class="relative z-10">
                    <div class="rounded-3xl">
                        <div class="flex items-center mb-10">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mr-4">
                                <img :src="image" alt="Icon" class="w-16 h-16 rounded-full"/>
                            </div>

                            <div class="flex flex-col">
                                <h3 class="text-2xl font-bold text-white">
                                    {{ title }}
                                </h3>
                                <FavoriteButton
                                    :model-id="id"
                                    :model-type="type"
                                    :initial-is-favorited="isFavorited"
                                    :show-label="true"
                                    class="favorite-button"
                                    :key="id"
                                />
                            </div>
                        </div>

                        <ul class="space-y-4">
                            <li v-for="(link, index) in links" :key="index">
                                <Link
                                    :href="link.url"
                                    :class="{
                                        'bg-amber-500': $page.url === link.url,
                                        'hover:bg-stone-700': $page.url !== link.url
                                    }"
                                    class="block py-2 px-4 rounded-lg transition-colors duration-200"
                                >
                                    <span class="text-xl font-semibold">
                                        {{ __(
                                            `stickybar.${link.text.toLowerCase().replace(" ", "_")}`
                                        ) }}
                                    </span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Decorative background elements -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full transform translate-x-1/2 translate-y-1/2"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import __ from '@/lang';
import FavoriteButton from '@/Components/FavoriteButton.vue';

const props = defineProps({
    type: {
        type: String,
        default: "career",
        validator: (value) => ["career", "degree", "job"].includes(value),
    },
    id: Number,
    slug: String,
    title: String,
    image: String,
    salary: Number,
    personality: String,
    satisfaction: String,
    degreeLevel: String,
    duration: String,
    jobType: String,
    workplaceType: String,
    disableStepsLink: Boolean,
    isFavorited: {
        type: Boolean,
    }
});

watch(() => props.isFavorited, (newValue) => {
    console.log('isFavorited changed:', newValue);
});

console.log('Initial isFavorited:', props.isFavorited);

const isSmallScreen = ref(false);

const updateScreenSize = () => {
    isSmallScreen.value = window.innerWidth < 1024;
};

updateScreenSize();
window.addEventListener("resize", updateScreenSize);

const baseUrl = computed(() => `/${props.type}/${props.slug}`);

const links = computed(() => {
    if (props.type === "career") {
        return [
            { text: "overview", url: baseUrl.value },
            { text: "work_environments", url: `${baseUrl.value}/workEnvironments` },
            { text: "personality", url: `${baseUrl.value}/personality` },
            { text: "how_to_become", url: `${baseUrl.value}/how-to-become`, disabled: props.disableStepsLink },
        ];
    } else if (props.type === "degree") {
        return [
            { text: "overview", url: baseUrl.value },
            { text: "how_to_obtain", url: `${baseUrl.value}/how-to-obtain` },
        ];
    } else {
        return [
            { text: "overview", url: baseUrl.value },
            { text: "requirements", url: `${baseUrl.value}/requirements` },
            { text: "similar_jobs", url: `${baseUrl.value}/similar-jobs` },
        ];
    }
});

const getFirstBoxTitle = computed(() => {
    if (props.type === "career") return "salary";
    if (props.type === "degree") return "degree_level";
    return "job_type";
});

const getFirstBoxContent = computed(() => {
    if (props.type === "career") return "N/A";
    if (props.type === "degree") return props.degreeLevel;
    return props.jobType;
});

const getSecondBoxTitle = computed(() => {
    if (props.type === "career") return "personality";
    if (props.type === "degree") return "duration";
    return "workplace";
});

const getSecondBoxContent = computed(() => {
    if (props.type === "career") return props.personality;
    if (props.type === "degree") return props.duration;
    return props.workplaceType;
});

const isAnimating = ref(false);
</script>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.favorite-button {
    @apply flex items-center mt-2 transition-all duration-300;
}
</style>
