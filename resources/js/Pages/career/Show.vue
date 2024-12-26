<template>
    <div class="relative min-h-screen">
        <!-- Background with theme -->
        <div class="absolute inset-0 -z-10" :class="[themeStore.getThemeClasses('base')]">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/80"></div>
        </div>

        <!-- Main Content -->
        <div class="relative container mx-auto px-4 py-8 space-y-8">
            <!-- Header Section -->
            <div class="flex items-start justify-between">
                <div class="space-y-4">
                    <h1 class="text-4xl font-bold" :class="[`text-${themeStore.currentTheme.primary}-900`]">
                        {{ career.name }}
                    </h1>
                    <p class="text-lg text-gray-600">
                        {{ career.description }}
                    </p>
                </div>

                <!-- Favorite Button -->
                <FavoriteButton :model-id="career.id" model-type="career" :initial-is-favorited="career.is_favorite"
                    :class="[`text-${themeStore.currentTheme.primary}-600`]" />
            </div>

            <!-- Career Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Stats Card -->
                <div class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white/20 shadow-sm p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <CurrencyDollarIcon class="w-5 h-5"
                                :class="[`text-${themeStore.currentTheme.primary}-500`]" />
                            <span class="text-sm font-medium text-gray-600">Average Salary</span>
                        </div>
                        <p class="text-2xl font-bold" :class="[`text-${themeStore.currentTheme.primary}-900`]">
                            {{ career.salary || 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Other career details cards -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme/themeStore';
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
import { CurrencyDollarIcon } from '@heroicons/vue/24/outline';
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({ layout: MainLayout });

const themeStore = useThemeStore();

const props = defineProps({
    career: {
        type: Object,
        required: true,
        validator: (obj) => {
            return ['id', 'name', 'description', 'salary', 'is_favorite'].every(prop => prop in obj);
        }
    }
});
</script>

<style scoped>
.backdrop-blur-xl {
    backdrop-filter: blur(24px);
}

/* Add smooth transitions */
.transition-all {
    @apply transition-all duration-300 ease-in-out;
}

/* Add hover effects */
.hover-effect {
    @apply hover:scale-105 hover:shadow-lg transition-all duration-300;
}
</style>
