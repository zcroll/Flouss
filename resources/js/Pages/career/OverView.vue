<template>
    <AppLayout>

        <div class="layout--sidebar__body__main">


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


            <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow-2xl">


                <Breadcrumbs 
            :items="[
              { name: 'Home', route: 'dashboard' },
              { name: 'Jobs', route: 'jobs.index' },
              { name: occupation.name, route: 'career', params: { id: occupation.id } },
              { name: 'Overview' }
            ]"
          />
                <!-- Main Role Description Section -->
                <section class="space-y-8">
                    <h2 class="heading-type">{{ __('career.what_is_a') }} {{occupation.name}} ?</h2>

                    <div v-for="(info, index) in jobInfoDetail" :key="info.id" class="space-y-6">
                        <div class="space-y-4">
                            <p class="text-md text-gray-700 leading-relaxed">{{ info.role_description_main }}</p>
                        </div>

                        <div class="space-y-4">
                            <p class="text-md text-gray-700 leading-relaxed">{{ info.role_description_secondary }}</p>
                        </div>
                    </div>
                </section>

                <aside id="table-of-contents-container" class="block">
                    <div
                        id="table-of-contents"
                        class="table-of-contents rounded-lg my-4 w-full bg-[#F8EDE3] text-gray-900 relative p-9"
                        role="directory"
                        tabindex="0"
                        title="Table of contents"
                    >
                        <p class="custom-heading">
                            {{ __('career.in_this_article') }}
                        </p>
                        <ol class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight">
                            <li class="relative mb-0 text-xl leading-10 hover:underline">
                                <a
                                    href="#responsibilities-duties"
                                    class="trait-type"
                                >
                                    {{ __('career.responsibilities_and_duties') }}
                                </a>
                            </li>
                            <li class="relative mb-0 text-xl leading-10 hover:underline">
                                <a
                                    href="#related-jobs"
                                    class="trait-type"
                                >
                                    {{ __('career.related_jobs') }}
                                </a>
                            </li>
                        </ol>
                    </div>
                </aside>


                <!-- Duties and Responsibilities Section -->
                <section id="responsibilities-duties" class="space-y-8 mt-12">
                    <h2 class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                        {{ __('career.core_duties_and_responsibilities') }}
                    </h2>

                    <ul class="list-custom">
                        <li
                            v-for="(duty, index) in jobInfoDuties"
                            :key="duty.id"
                            class="text-l text-gray-700 pt-3"
                        >
                            {{ duty.duty_description }}
                        </li>
                    </ul>
                </section>


                <!-- Job Types Section -->
                <section class="space-y-8 mt-12">
                    <h2 id="related-jobs" class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                        {{ __('career.types_of') }} {{occupation.name}}
                    </h2>

                    <div v-for="(jobType, index) in jobInfoTypes" :key="jobType.id" class="space-y-4">
                        <h3 class="text-l font-black text-gray-800">{{ jobType.type_name }}</h3>
                        <p class="text-md text-gray-700">{{ jobType.type_description }}</p>
                    </div>
                </section>

                <!-- Conclusion Section -->
                <!-- <section class="mt-12 space-y-4 text-center">
                    <p class="text-xl text-gray-600">Interested in learning more about this career? Explore our <a href="#" class="text-blue-600 hover:text-blue-500 underline">Career Guide</a>.</p>
                </section> -->
                <BackToTop />
            </div>



        </StickySidebar>
    </div>
    </AppLayout>
</template>

<script setup>
import {ref, computed} from 'vue';
import {defineProps} from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/vue3';
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue'
defineProps({
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

const jobInfo = computed(() => props.jobInfoDetail.description);

</script>

<style scoped>
/* add your styles here */
@import '/public/css/items_description.css';
</style>
