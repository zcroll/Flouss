<template>
    <Head title="Career Overview" />
    <StickySidebar
        :slug="occupation.slug"
        :title="occupation.name"
        :image="occupation.image"
        type="career"
        :salary="occupation.salary"
        :personality="occupation.personality || 'N/A'"
        :satisfaction="occupation.satisfaction || 'N/A'"
        :id="occupation.id"
        :isFavorited="occupation.is_favorited"
    >
        <template #description>
            Discover detailed information about the {{ occupation.name }} career path and what it takes to succeed in this field.
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Breadcrumbs -->
            <Breadcrumbs 
                :items="[
                    { name: 'Home', route: 'dashboard' },
                    { name: 'Jobs', route: 'jobs.index' },
                    { name: occupation.name, route: 'career', params: { id: occupation.id } },
                    { name: 'Overview' }
                ]"
                class="mb-8"
            />

            <!-- Main Role Description Section -->
            <section class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('career.what_is_a') }} {{occupation.name}}?
                </h2>

                <div v-for="(info, index) in jobInfoDetail" 
                     :key="info.id" 
                     class="prose prose-lg max-w-none text-gray-600">
                    <p class="mb-4">{{ info.role_description_main }}</p>
                    <p>{{ info.role_description_secondary }}</p>
                </div>
            </section>

            <!-- Table of Contents -->
            <aside class="my-12">
                <div class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ __('career.in_this_article') }}
                    </h3>
                    <nav class="space-y-2">
                        <a href="#responsibilities-duties"
                           :class="[classes.hover, 'block text-gray-600 transition-colors']">
                            {{ __('career.responsibilities_and_duties') }}
                        </a>
                        <a href="#related-jobs"
                           :class="[classes.hover, 'block text-gray-600 transition-colors']">
                            {{ __('career.related_jobs') }}
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Duties and Responsibilities Section -->
            <section id="responsibilities-duties" class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('career.core_duties_and_responsibilities') }}
                </h2>

                <ul class="space-y-4">
                    <li v-for="duty in jobInfoDuties"
                        :key="duty.id"
                        class="flex items-start gap-3 text-gray-600">
                        <div :class="[`bg-${themeColors.primary}-400`, 'mt-1.5 h-2 w-2 rounded-full flex-shrink-0']"></div>
                        <p>{{ duty.duty_description }}</p>
                    </li>
                </ul>
            </section>

            <!-- Job Types Section -->
            <section id="related-jobs" class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('career.types_of') }} {{occupation.name}}
                </h2>

                <div class="grid gap-6">
                    <div v-for="jobType in jobInfoTypes" 
                         :key="jobType.id"
                         class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ jobType.type_name }}
                        </h3>
                        <p class="text-gray-600">
                            {{ jobType.type_description }}
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <BackToTop />
    </StickySidebar>
</template>

<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';
import MainLayout from "@/Layouts/MainLayout.vue";
import { useArchetypeTheme } from '@/composables/useArchetypeTheme';

defineOptions({ layout: MainLayout });

const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    jobInfoDetail: {
        type: Object,
        required: true,
    },
    jobInfoDuties: {
        type: Object,
        required: true,
    },
    jobInfoTypes: {
        type: Object,
        required: true,
    },
    workplaces: {
        type: Array,
        required: true,
    },
});

const archetype = computed(() => props.occupation.personality || 'Creator');
const { themeColors, classes } = useArchetypeTheme(archetype);
</script>

<style scoped>
/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Fade-in animation for sections */
section {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
