<template>
    <AppLayout>
        <div class="career-page career-page--personality layout--sidebar">
            <Deferred :data="['degree']">
                <template #fallback>
                    <div class="animate-pulse">
                        <div class="h-64 bg-gray-200 rounded-lg mb-4"></div>
                    </div>
                </template>

                <StickySidebar
                    :slug="degree.slug"
                    :title="degree.name"
                    :id="degree.id"
                    :image="degree.image"
                    type="degree"
                    :degreeLevel="degree.degree_level"
                    :satisfaction="degree.satisfaction || __('degreeOverview.notAvailable')"
                    :isFavorited="degree.is_favorited"
                >
                    <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow-2xl">
                        <nav class="flex items-center space-x-2 text-sm mb-8" style="font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                            <Link :href="route('dashboard')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] hover:text-[#53777a] hover:border-[#53777a] transition-all duration-200">{{ __("Home") }}</Link>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <Link :href="route('degrees.index')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] hover:text-[#53777a] hover:border-[#53777a] transition-all duration-200">{{ __("Degrees") }}</Link>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-[#53777a] font-medium border-b-2 border-[#53777a]">{{ degree.name }}</span>
                        </nav>

                        <!-- Main Degree Description Section -->
                        <Deferred data="degreeDescription">
                            <template #fallback>
                                <div class="animate-pulse space-y-4">
                                    <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-4 bg-gray-200 rounded"></div>
                                    <div class="h-4 bg-gray-200 rounded"></div>
                                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                </div>
                            </template>

                            <section class="space-y-8">
                                <h2 class="custom-heading">
                                    {{ __("degreeOverview.whatIs", { name: degree.name }) }}
                                </h2>

                                <div class="space-y-6">
                                    <div class="space-y-4">
                                        <p class="text-md text-gray-700 leading-relaxed">
                                            {{ degreeDescription.main_description }}
                                        </p>
                                    </div>

                                    <div class="space-y-4">
                                        <p class="text-md text-gray-700 leading-relaxed">
                                            {{ degreeDescription.secondary_description }}
                                        </p>
                                    </div>
                                </div>
                            </section>
                        </Deferred>

                        <aside id="table-of-contents-container" class="block">
                            <div id="table-of-contents" class="table-of-contents rounded-lg my-4 w-full bg-[#F8EDE3] text-gray-900 relative p-9" role="directory" tabindex="0" :title="__('degreeOverview.tableOfContents')">
                                <p class="custom-heading">
                                    {{ __("degreeOverview.inThisArticle") }}
                                </p>
                                <ol class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight">
                                    <li class="relative mb-0 text-xl leading-10 hover:underline">
                                        <a href="#skills" class="trait-type">{{ __("degreeOverview.skillsGained") }}</a>
                                    </li>
                                    <li class="relative mb-0 text-xl leading-10 hover:underline">
                                        <a href="#related-jobs" class="trait-type">{{ __("degreeOverview.relatedJobs") }}</a>
                                    </li>
                                </ol>
                            </div>
                        </aside>

                        <!-- Skills Section -->
                        <Deferred data="degreeSkills">
                            <template #fallback>
                                <div class="animate-pulse space-y-4">
                                    <div class="h-6 bg-gray-200 rounded w-1/2"></div>
                                    <div class="space-y-2">
                                        <div class="h-4 bg-gray-200 rounded"></div>
                                        <div class="h-4 bg-gray-200 rounded"></div>
                                        <div class="h-4 bg-gray-200 rounded"></div>
                                    </div>
                                </div>
                            </template>

                            <section id="skills" class="space-y-8 mt-12">
                                <h2 class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                                    {{ __("degreeOverview.skillsGained") }}
                                </h2>

                                <ul class="list-custom">
                                    <li v-for="skill in degreeSkills" :key="skill.id" class="text-l text-gray-700 pt-3">
                                        {{ skill.skill_description }}
                                    </li>
                                </ul>
                            </section>
                        </Deferred>

                        <!-- Related Jobs Section -->
                        <Deferred data="degreeJobs">
                            <template #fallback>
                                <div class="animate-pulse space-y-4">
                                    <div class="h-6 bg-gray-200 rounded w-1/2"></div>
                                    <div class="space-y-2">
                                        <div class="h-4 bg-gray-200 rounded"></div>
                                        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                    </div>
                                </div>
                            </template>

                            <section id="related-jobs" class="space-y-8 mt-12">
                                <h2 class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                                    {{ __("degreeOverview.relatedJobs") }}
                                </h2>

                                <div v-for="job in degreeJobs" :key="job.id" class="space-y-4">
                                    <h3 class="text-l font-black text-gray-800">
                                        {{ job.job_title }}
                                    </h3>
                                    <p class="text-lg text-gray-700">
                                        {{ job.job_description }}
                                    </p>
                                </div>
                                <BackToTop />
                            </section>
                        </Deferred>
                    </div>
                </StickySidebar>
            </Deferred>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps } from "vue";
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Deferred } from "@inertiajs/vue3";
import BackToTop from "@/Components/BackToTop.vue";

defineProps({
    degree: {
        type: Object,
        required: true,
    },
    degreeDescription: {
        type: Object,
        required: true,
    },
    degreeSkills: {
        type: Array,
        required: true,
    },
    degreeJobs: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
/* Use the same styles as in the OverView.vue file */

ul.list-custom {
    list-style: none;
    padding-left: 1.5rem;
}

ul.list-custom li {
    position: relative;
    padding-left: 2rem;
}

ul.list-custom li::before {
    content: "•";
    position: absolute;
    left: 0;
    font-size: 2.1rem;
    color: #a36fb2;
}
</style>
