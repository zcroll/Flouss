<template>
    <app-layout title="Results" head-title="Your Results" preserveScroll>
        <div class="relative max-w-7xl mx-auto mt-10">

            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-4/4 space-y-20">
                    <div v-if="Object.keys(jobs).length > 0 " class="text-left mt-10">
                        <h1 class="ml-1 text-4xl font-serif text-gray-100 mt-10 ">Your Compatibility Results</h1>
                        <p class="ml-3 mt-2 text-lg text-slate-300">Below are your scores and best-fit career matches.</p>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Archetype
                            :userId="userId"
                            :archetype="Archetype"
                            :scores="scores"
                        />
                        <Archetype
                            :userId="userId"
                            :archetype="Archetype"
                            :scores="scores"
                            :comingSoon="true"
                            backgroud_pic="url('https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_400,q_auto,w_640/v1558730393/e2cmhbjek730smx9odcv.jpg')"
                        />
                    </div>




                    <!-- Closest Jobs Section -->
                    <div v-if="Object.keys(jobs).length > 0" class="text-left mb-6">
                        <h1 class="ml-1 text-4xl font-serif text-gray-100">Your Best Fit Career Matches.</h1>
                        <p class="ml-3 mt-2 text-lg text-slate-300">
                            Based on your responses, here are some jobs that you can excel in more than everyone else.
                        </p>
                    </div>
                    <div v-if="Object.keys(jobs).length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-20">
                        <div
                            v-for="(job, index) in jobs"
                            :key="index"
                            class="relative flex items-center overflow-hidden text-sm bg-white rounded-xl transition-all duration-300 hover:shadow-lg hover:transform hover:translate-y-[-5px]"
                            style="box-shadow: 0 2px 16px 0 rgba(0,0,0,0.09); font-size: 14px;"
                        >
                            <div class="flex-shrink-0 p-4">
                                <img
                                    class="h-12 w-12 rounded-full"
                                    :src="job.image || 'https://via.placeholder.com/160x160'"
                                    alt="Job Image"
                                />
                            </div>
                            <div class="min-w-0 flex-1 p-4">
                                <Link :href="`/career/${job.slug}`" class="block focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <p class="text-base font-medium text-gray-900">{{ job.name }}</p>
                                    <div class="flex items-center mt-1">
                                        <template v-for="star in 5" :key="star">
                                            <svg
                                                :class="star <= job.rating ? 'text-yellow-400' : 'text-gray-300'"
                                                class="h-4 w-4 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </template>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from "vue";
import {router, Link ,} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import UpNext from "@/Components/UpNext.vue";
import Archetype from "@/Components/Archetype.vue";

export default defineComponent({
    components: {
        Archetype,
        UpNext,
        AppLayout,
        Link,

    },
    props: {
        scores: {
            type: Object,
            required: true,
        },
        jobs: {
            type: Object,
            required: true,
        },
        Archetype: {
            type: Object,
            required: true,
        },

        userId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            isCollapsed: {}, // Tracks which categories are collapsed
        };
    },
    methods: {


        toggleCollapse(category) {
            this.isCollapsed[category] = !this.isCollapsed[category];
        },

        visitJob(job) {
            const formattedJob = job.replace(/ /g, '-');
            router.visit(`/career/${formattedJob}`, {preserveScroll: false});
        },
    },
});
</script>

<style scoped>
body {
    font-family: "Inter", sans-serif;
}

h1, h2, h3 {
    font-family: "Poppins", sans-serif;
}

p {
    font-family: "Inter", sans-serif;
}

</style>
