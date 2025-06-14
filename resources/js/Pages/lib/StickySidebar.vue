<template>
    <div class="relative flex flex-col-reverse lg:flex-row lg:gap-10 rounded">
        <!-- Main Content Area -->
        <div class="flex-1 rounded">
            <!-- Add Breadcrumbs component here -->
            
            <div class="relative rounded-lg border shadow-sm p-4" :class="[
                themeStore.isDarkMode
                ? [`bg-gray-800/40 border-${themeStore.currentTheme.primary}-950`]
                : [`bg-white/60 backdrop-blur-xl border-${themeStore.currentTheme.primary}-300`]
            ]">
                <!-- Title Section -->
                <div class="mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold" :class="[
                        themeStore.isDarkMode
                        ? `text-${themeStore.currentTheme.hover}`
                            : `text-${themeStore.currentTheme.button}`
                        ]">
                        {{ modelProps.title }}
                    </h1>
                    <p class="mt-3 text-base" :class="[
                        themeStore.isDarkMode 
                        ? `text-${themeStore.currentTheme.border}`
                        : `text-${themeStore.currentTheme.button}`
                    ]">
                        <slot name="description" />
                    </p>
                </div>
                
                <!-- Main Content -->
                <div>
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                    <slot />
                </div>

                <!-- Decorative Background -->
                <div class="absolute inset-0 -z-10">
                    <div :class="[
                        'absolute inset-0',
                        themeStore.isDarkMode ? 'bg-gray-900/30 backdrop-blur-xl' : '',
                        themeStore.getThemeClasses('base')
                    ]"></div>
                    <GradientCircle position="top-left" />
                    <GradientCircle position="bottom-right" />
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <aside class="w-full lg:w-1/4">
            <!-- Mobile Navigation -->
            <MobileNavBar v-show="isSmallScreen" :model-props="modelProps" :model-id="props.model.id" :links="links"
                :type="type" @favorite-toggled="handleFavoriteToggle" />

            <!-- Desktop Sidebar -->
            <DesktopSidebar v-show="!isSmallScreen" :model-props="modelProps" :model-id="props.model.id" :links="links"
                :type="type" @favorite-toggled="handleFavoriteToggle" />
        </aside>

        <!-- Toast Container with fixed positioning -->
        <Toaster position="left" />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useThemeStore } from '@/stores/theme/themeStore';
import { useToast } from "@/Components/ui/toast/use-toast";
import Toaster from "@/Components/ui/toast/Toaster.vue";
import __ from '@/lang';
import { useScreenSize } from '@/composables/useScreenSize';
import { useSidebarLinks } from '@/composables/useSidebarLinks';
import MobileNavBar from './components/MobileNavBar.vue';
import DesktopSidebar from './components/DesktopSidebar.vue';
import GradientCircle from './components/GradientCircle.vue';
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';

const themeStore = useThemeStore();
const toast = useToast();

// Props definition
const props = defineProps({
    type: {
        type: String,
        default: "career",
        validator: (value) => ["career", "degree", "job"].includes(value),
    },
    model: {
        type: Object,
        required: true,
        validator: (obj) => {
            if (!obj) return false;
            const requiredProps = ['id', 'slug', 'name', 'image'];
            return requiredProps.every(prop => prop in obj);
        }
    },
    disableStepsLink: Boolean,
});

// Composables
const { isSmallScreen } = useScreenSize();
const { links } = useSidebarLinks(props);

// Computed properties
const modelProps = computed(() => ({
    title: props.model?.name || '',
    image: props.model?.image || '',
    salary: props.model?.salary,
    personality: props.model?.personality || 'N/A',
    satisfaction: props.model?.satisfaction || 'N/A',
    archetype: props.model?.personality,
    isFavorited: props.model?.is_favorited ?? false
}));

// Watch for personality changes to update theme
watch(() => props.model?.personality, (newValue) => {
    if (newValue) {
        themeStore.setArchetype(newValue);
    }
}, { immediate: true });

// Handle favorite toggle
const handleFavoriteToggle = (isFavorited) => {
    // Update local state if needed
    modelProps.value.isFavorited = isFavorited;
};

// Add breadcrumbs computed property
const breadcrumbs = computed(() => usePage().props.breadcrumbs || []);
</script>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Performance optimizations */
.sticky {
    transform: translateZ(0);
    will-change: transform;
}

.transition-all {
    transition-property: transform, opacity;
    transition-duration: 300ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
