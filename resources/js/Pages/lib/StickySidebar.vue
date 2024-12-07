<template>
    <div class="mt-[-40px] flex flex-col-reverse lg:container lg:mx-auto lg:flex-row">
        <div class="w-full lg:w-2/3">
            <div class="relative  rounded-t-3xl p-1 lg:p-0 lg:mx-0 lg:bg-none lg:rounded-none overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <!-- Gradient orbs -->
                    <div class="absolute top-0 left-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute top-1/4 right-0 w-40 h-40 bg-purple-500/10 rounded-full blur-2xl transform translate-x-1/2"></div>
                    <!-- Subtle grid -->
                    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.03)_1px,transparent_1px)] bg-[size:32px_32px] opacity-20"></div>
                </div>
                
                <!-- Content with glass effect -->
                <div class="relative border rounded-lg overflow-hidden">
                    <slot />
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 lg:mb-0 lg:pl-8 pb-5 pt-8">
            <!-- Mobile Navigation Bar (Fixed Bottom) -->
            <div v-if="isSmallScreen"
                 class="fixed top-0 left-0 right-0 bg-[#353535] text-white shadow-lg z-50 rounded-b-2xl">
                <div class="flex items-center justify-between px-3 py-2 border-b border-gray-700">
                    <img :src="image" alt="Icon" class="w-8 h-8 rounded-md"/>

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
                <div class="w-full overflow-x-auto scrollbar-hide">
                    <div class="flex w-full px-1 py-2">
                        <Link
                            v-for="(link, index) in links"
                            :key="index"
                            :href="link.url"
                            :class="{
                                'bg-slate-950 hover:bg-slate-900 active:bg-slate-900': $page.url === link.url,
                                'bg-stone-700 hover:bg-slate-900 active:bg-slate-900': $page.url !== link.url
                            }"
                            class="flex-1 min-w-[60px] flex flex-col items-center justify-center px-1 py-1.5 rounded-xl text-sm font-medium transition-all mx-0.5"
                        >
                            <component 
                                :is="getLinkIcon(link.text)" 
                                class="w-5 h-5 mb-0.5"
                            />
                            <span class="text-[10px] text-center leading-tight">{{ 
                                getShortLabel(link.text)
                            }}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Desktop Sticky Sidebar -->
            <div v-else class="sticky top-[100px] bg-card text-card-foreground rounded-xl border shadow-sm">
                <div class="p-6 relative z-10">
                    <div class="flex items-center space-x-4 pb-6 border-b">
                        <div class="h-16 w-16 overflow-hidden rounded-2xl ring-2 ring-muted">
                            <img :src="image" alt="Icon" class="h-full w-full object-cover"/>
                        </div>
                        <h3 class="text-lg font-semibold leading-none truncate flex-1">
                            {{ title }}
                        </h3>
                        <FavoriteButton
                            :model-id="id"
                            :model-type="type"
                            :initial-is-favorited="isFavorited"
                            :show-label="true"
                            :key="id"
                        />
                    </div>


                    <nav class="mt-6">
                        <div class="space-y-1">
                            <Link
                                v-for="(link, index) in links"
                                :key="index"
                                :href="link.url"
                                :class="[
                                    'w-full flex items-center py-2 px-3 text-sm font-medium rounded-lg transition-colors',
                                    $page.url === link.url 
                                        ? 'bg-primary text-primary-foreground hover:bg-primary/90'
                                        : 'hover:bg-muted'
                                ]"
                            >
                                {{ __(`stickybar.${link.text.toLowerCase().replace(" ", "_")}`) }}
                            </Link>
                        </div>
                    </nav>
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
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
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
    }
});

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
