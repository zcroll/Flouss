<template>
    <app-layout :title="__('results.title')" :head-title="__('results.head_title')" preserveScroll>
        <div class="relative max-w-7xl mx-auto mt-10">

            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-4/4 space-y-20">
                    <div v-if="Object.keys(jobs).length > 0 " class="text-left mt-10">
                        <h1 class="ml-1 text-4xl font-serif text-gray-100 mt-10 ">{{ __('results.your_compatibility_results') }}</h1>
                        <p class="ml-3 mt-2 text-lg text-slate-300">{{ __('results.scores_and_matches') }}</p>
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
                    <div v-if="ArchetypeJobs.length > 0" class="text-left mb-6">
                        <h1 class="ml-1 text-4xl font-serif text-gray-100">{{ __('results.best_fit_career_matches') }}</h1>
                        <p class="ml-3 mt-2 text-lg text-slate-300">
                            {{ __('results.excel_in_careers') }}
                        </p>
                    </div>
                    <div v-if="ArchetypeJobs.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-20">
                        <div
                            v-for="(job, index) in ArchetypeJobs"
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
                                    <p class="text-base font-medium text-gray-900">{{ job.career }}</p>
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
import __ from '@/lang';

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
        ArchetypeJobs: {
            type: Array,
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
