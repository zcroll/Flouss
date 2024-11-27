<template>
    <AppLayout>
        <div class="career-page career-page--personality layout--sidebar">
            <StickySidebar
                :slug="degree.slug"
                :title="degree.name"
                :id="degree.id"
                :image="degree.image"
                type="degree"
                :degreeLevel="degree.degree_level"
                :satisfaction="
                    degree.satisfaction || __('degreeOverview.notAvailable')
                "
                :isFavorited="degree.is_favorited"
            >
                <div
                    class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow-2xl"
                >
                <Breadcrumbs 
            :items="[
              { name: 'Home', route: 'dashboard' },
              { name: 'Degrees', route: 'degrees.index' },
              { name: degree.name, route: 'degree.index', params: { slug: degree.slug } },
              { name: 'Overview' }
            ]"
                    />
                    <!-- Main Degree Description Section -->
                    <section class="space-y-8">
                        <h2 class="heading-type">
                            {{
                                __("degreeOverview.whatIs", {
                                    name: degree.name,
                                })
                            }}
                        </h2>

                        <div class="space-y-6">
                            <div class="space-y-4">
                                <p
                                    class="text-md text-gray-700 leading-relaxed"
                                >
                                    {{ degreeDescription.main_description }}
                                </p>
                            </div>

                            <div class="space-y-4">
                                <p
                                    class="text-md text-gray-700 leading-relaxed"
                                >
                                    {{
                                        degreeDescription.secondary_description
                                    }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <aside id="table-of-contents-container" class="block">
                        <div
                            id="table-of-contents"
                            class="table-of-contents rounded-lg my-4 w-full bg-[#F8EDE3] text-gray-900 relative p-9"
                            role="directory"
                            tabindex="0"
                            :title="__('degreeOverview.tableOfContents')"
                        >
                            <p class="custom-heading">
                                {{ __("degreeOverview.inThisArticle") }}
                            </p>
                            <ol
                                class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight"
                            >
                                <li
                                    class="relative mb-0 text-xl leading-10 hover:underline"
                                >
                                    <a href="#skills" class="trait-type">{{
                                        __("degreeOverview.skillsGained")
                                    }}</a>
                                </li>
                                <li
                                    class="relative mb-0 text-xl leading-10 hover:underline"
                                >
                                    <a
                                        href="#related-jobs"
                                        class="trait-type"
                                        >{{
                                            __("degreeOverview.relatedJobs")
                                        }}</a
                                    >
                                </li>
                            </ol>
                        </div>
                    </aside>

                    <!-- Skills Section -->
                    <section id="skills" class="space-y-8 mt-12">
                        <h2
                            class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200"
                        >
                            {{ __("degreeOverview.skillsGained") }}
                        </h2>

                        <ul class="list-custom">
                            <li
                                v-for="skill in degreeSkills"
                                :key="skill.id"
                                class="text-l text-gray-700 pt-3"
                            >
                                {{ skill.skill_description }}
                            </li>
                        </ul>
                    </section>

                    <!-- Related Jobs Section -->
                    <section v-if="degreeJobs.length>1" id="related-jobs" class="space-y-8 mt-12">
                        <h2

                            class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200"
                        >
                            {{ __("degreeOverview.relatedJobs") }}
                        </h2>

                        <div
                            v-for="job in degreeJobs"
                            :key="job.id"
                            class="space-y-4"
                        >
                            <h3 class="text-l font-black text-gray-800">
                                {{ job.job_title }}
                            </h3>
                            <p class="text-lg text-gray-700">
                                {{ job.job_description }}
                            </p>
                        </div>

                    </section>
                    <BackToTop />
                </div>
            </StickySidebar>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps } from "vue";
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue'

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
@import '/public/css/items_description.css';

</style>
