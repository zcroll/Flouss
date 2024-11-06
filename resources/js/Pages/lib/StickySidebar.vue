<template>
    <div class="container mx-auto p-4 flex flex-col-reverse lg:flex-row">
        <div class="w-full lg:w-2/3">
            <slot />
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 mb-8 lg:mb-0 lg:pl-8">
            <div
                class="sticky top-[120px] text-white rounded-2xl p-6 overflow-hidden bg-[#353535]"
            >
                <div class="relative z-10">
                    <!-- Main Content for Large Screens -->
                    <div v-if="!isSmallScreen" class="rounded-3xl">
                        <div class="flex items-center mb-10">
                            <div
                                class="w-20 h-20 bg-white rounded-full flex items-center justify-center mr-4"
                            >
                                <img
                                    :src="image"
                                    alt="Icon"
                                    class="w-16 h-16 rounded-full"
                                />
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
                                />
                            </div>
                        </div>

<!--                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div
                                class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg"
                            >
                                <h4 class="text-lg font-bold">
                                    {{
                                        __(
                                            `stickybar.${getFirstBoxTitle.toLowerCase()}`
                                        )
                                    }}
                                </h4>
                                <p class="text-sm">{{ getFirstBoxContent }}</p>
                            </div>
                            <div
                                class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg"
                            >
                                <h4 class="text-lg font-bold">
                                    {{
                                        __(
                                            `stickybar.${getSecondBoxTitle.toLowerCase()}`
                                        )
                                    }}
                                </h4>
                                <p class="text-sm">{{ getSecondBoxContent }}</p>
                            </div>
                            <div
                                class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg"
                            >
                                <h4 class="text-lg font-bold">
                                    {{ __("stickybar.satisfaction") }}
                                </h4>
                                <p class="text-sm">{{ satisfaction }}</p>
                            </div>
                        </div>-->
                        <ul class="space-y-4">
                            <li v-for="(link, index) in links" :key="index">
                                <Link
                                    :href="link.url"
                                    :class="{
                                        active: $page.url === link.url,
                                        'disabled-link': $page.url === link.url,
                                    }"
                                    :aria-disabled="$page.url === link.url"
                                    class="block py-2 px-4 rounded-lg transition-colors duration-200 hover:bg-white hover:bg-opacity-20"
                                >
                                    <span class="text-xl font-semibold">{{
                                        __(
                                            `stickybar.${link.text
                                                .toLowerCase()
                                                .replace(" ", "_")}`
                                        )
                                    }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <!-- Centered Content for Small Screens -->
                    <div v-else class="flex flex-col items-center">
                        <div
                            class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4"
                        >
                            <img
                                :src="image"
                                alt="Icon"
                                class="w-16 h-16 rounded-full"
                            />
                        </div>
                        <h3 class="text-xl font-bold mb-6">{{ title }}</h3>
                        <div class="grid grid-cols-3 gap-4 mb-6 w-full">
                            <!-- Add small screen content here if needed -->
                        </div>
                        <div class="flex flex-wrap justify-center gap-2">
                            <Link
                                v-for="(link, index) in links"
                                :key="index"
                                :href="link.url"
                                :class="{
                                    active: $page.url === link.url,
                                    'disabled-link': $page.url === link.url,
                                }"
                                :aria-disabled="$page.url === link.url"
                                class="py-2 px-4 bg-white bg-opacity-20 rounded-lg text-center transition-colors duration-200 hover:bg-opacity-30"
                            >
                                <span class="text-sm font-semibold">{{
                                    __(
                                        `stickybar.${link.text
                                            .toLowerCase()
                                            .replace(" ", "_")}`
                                    )
                                }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Decorative background elements -->
                <div
                    class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10"
                >
                    <div
                        class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2"
                    ></div>
                    <div
                        class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full transform translate-x-1/2 translate-y-1/2"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
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
        default: false
    }
});

const isSmallScreen = ref(false);

const updateScreenSize = () => {
    isSmallScreen.value = window.innerWidth < 1024;
};

// Call once to set initial value
updateScreenSize();

// Add event listener for resize
window.addEventListener("resize", updateScreenSize);

const baseUrl = computed(() => `/${props.type}/${props.slug}`);

const links = computed(() => {
    if (props.type === "career") {
        return [
            { text: "overview", url: baseUrl.value },
            {
                text: "work_environments",
                url: `${baseUrl.value}/workEnvironments`,
            },
            { text: "personality", url: `${baseUrl.value}/personality` },
            {
                text: "how_to_become",
                url: `${baseUrl.value}/how-to-become`,
                disabled: props.disableStepsLink,
            },
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

// Remove the existing saveFavorite function and favoriteButtonClasses since we're using the FavoriteButton component now

</script>

<style scoped>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-bounce {
    animation: bounce 0.5s ease-in-out;
}

/* Add this new style for the favorite button */
.favorite-button {
    @apply flex items-center mt-2 transition-all duration-300;
}
</style>
