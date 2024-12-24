<template>
    <div class="relative flex flex-col-reverse lg:container lg:mx-auto lg:flex-row lg:gap-8">
        <!-- Main Content Area -->
        <div class="w-full lg:w-2/3">
            <div class="relative rounded-t-3xl p-6 lg:p-8">
                <!-- Background Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <!-- Gradient Orbs -->
                    <div :class="[
                        'absolute top-0 left-0 w-32 h-32 rounded-full blur-2xl transform -translate-x-1/2 -translate-y-1/2',
                        classes.background
                    ]"></div>
                    <div :class="[
                        'absolute bottom-1/4 right-0 w-40 h-40 rounded-full blur-2xl transform translate-x-1/2',
                        classes.background
                    ]"></div>
                    
                    <!-- Subtle Grid Pattern -->
                    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.03)_1px,transparent_1px)] bg-[size:32px_32px] opacity-20"></div>
                </div>

                <!-- Content with Glass Effect -->
                <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm p-8">
                    <!-- Title Section -->
                    <div class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                            {{ title }}
                        </h1>
                        <p class="mt-4 text-gray-600 text-lg">
                            <slot name="description" />
                        </p>
                    </div>

                    <!-- Main Content -->
                    <div class="space-y-6">
                        <slot />
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 lg:mb-0">
            <!-- Mobile Navigation Bar -->
            <div v-if="isSmallScreen"
                 class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl shadow-lg z-50 rounded-b-2xl border-b border-white/20">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-white/50 p-2 backdrop-blur-sm border border-white/20">
                            <img :src="image" :alt="title" class="w-full h-full object-contain"/>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 truncate">{{ title }}</h3>
                    </div>
                    <FavoriteButton
                        :model-id="id"
                        :model-type="type"
                        :initial-is-favorited="isFavorited"
                        :show-label="false"
                        class="ml-2"
                    />
                </div>

                <!-- Mobile Navigation Links -->
                <div class="w-full overflow-x-auto scrollbar-hide">
                    <div class="flex w-full px-2 py-2 gap-1">
                        <Link
                            v-for="(link, index) in links"
                            :key="index"
                            :href="link.url"
                            class="flex flex-col items-center justify-center px-3 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="[
                                $page.url === link.url 
                                    ? classes.active
                                    : 'bg-white/50 text-gray-600 hover:bg-white/80'
                            ]"
                        >
                            <component 
                                :is="getLinkIcon(link.text)" 
                                class="w-5 h-5 mb-1"
                            />
                            <span class="text-[11px] text-center leading-tight">
                                {{ getShortLabel(link.text) }}
                            </span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Desktop Sticky Sidebar -->
            <div v-else 
                 class="sticky top-8 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm overflow-hidden">
                <div class="p-6 relative">
                    <!-- Header -->
                    <div class="flex items-center gap-4 pb-6 border-b border-gray-200/50">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white/50 p-3 backdrop-blur-sm border border-white/20">
                            <img :src="image" :alt="title" class="w-full h-full object-contain"/>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">
                                {{ title }}
                            </h3>
                        </div>
                        <FavoriteButton
                            :model-id="id"
                            :model-type="type"
                            :initial-is-favorited="isFavorited"
                            :show-label="true"
                        />
                    </div>

                    <!-- Navigation Links -->
                    <nav class="mt-6">
                        <div class="space-y-1">
                            <Link
                                v-for="(link, index) in links"
                                :key="index"
                                :href="link.url"
                                class="group w-full flex items-center gap-3 py-2.5 px-4 text-sm font-medium rounded-xl transition-all duration-300"
                                :class="[
                                    $page.url === link.url 
                                        ? classes.active
                                        : 'text-gray-600 hover:bg-white/80'
                                ]"
                            >
                                <component 
                                    :is="getLinkIcon(link.text)" 
                                    class="w-5 h-5 transition-colors duration-300"
                                    :class="[
                                        $page.url === link.url 
                                            ? 'text-white' 
                                            : classes.icon + ' group-hover:text-gray-600'
                                    ]"
                                />
                                {{ __(`stickybar.${link.text.toLowerCase().replace(" ", "_")}`) }}
                            </Link>
                        </div>
                    </nav>
                </div>

                <!-- Decorative Background -->
                <div class="absolute inset-0 -z-10">
                    <div :class="['absolute inset-0', classes.gradient]"></div>
                    <div :class="[
                        'absolute top-0 left-0 w-40 h-40 rounded-full blur-2xl transform -translate-x-1/2 -translate-y-1/2',
                        classes.background
                    ]"></div>
                    <div :class="[
                        'absolute bottom-0 right-0 w-40 h-40 rounded-full blur-2xl transform translate-x-1/2 translate-y-1/2',
                        classes.background
                    ]"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import __ from '@/lang';
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
import { useArchetypeTheme } from '@/composables/useArchetypeTheme';
import { 
    HomeIcon, 
    BriefcaseIcon, 
    UserIcon, 
    AcademicCapIcon,
    BuildingOfficeIcon,
    DocumentTextIcon,
    BookOpenIcon,
    ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline';

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
    degreeLevel: Number,
    duration: String,
    jobType: String,
    workplaceType: String,
    disableStepsLink: Boolean,
    isFavorited: {
        type: Boolean,
    },
    archetype: {
        type: String,
        default: 'Creator'
    }
});

// Initialize theme
const { themeColors, classes } = useArchetypeTheme(ref(props.archetype));

watch(() => props.isFavorited, (newValue) => {
    console.log('isFavorited changed:', newValue);
});

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

const getLinkIcon = (text) => {
    switch (text.toLowerCase()) {
        case 'overview':
            return HomeIcon;
        case 'work_environments':
            return BuildingOfficeIcon;
        case 'personality':
            return UserIcon;
        case 'how_to_become':
        case 'how_to_obtain':
            return AcademicCapIcon;
        case 'requirements':
            return ClipboardDocumentListIcon;
        case 'similar_jobs':
            return BriefcaseIcon;
        default:
            return DocumentTextIcon;
    }
};

const getShortLabel = (text) => {
    // Get the translation first
    const translated = __(`stickybar.${text.toLowerCase().replace(" ", "_")}`);
    // Return the first word
    return translated.split(' ')[0];
};

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

/* Modern gradient animation */
@keyframes gradient-shift {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

.bg-gradient-to-b {
    position: relative;
}

.bg-gradient-to-b::before {
    content: '';
    position: absolute;
    inset: -50%;
    background: radial-gradient(circle at center, 
        rgba(245, 158, 11, 0.05) 0%,
        rgba(168, 85, 247, 0.05) 45%,
        transparent 70%
    );
    animation: gradient-shift 15s linear infinite;
    pointer-events: none;
}
</style>
