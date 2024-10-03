<template>
     <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">
        
        <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/user-results.3aa4bb301b9f.css">
    <app-layout  preserveScroll>
        <div class="relative max-w-7xl mx-auto">

            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-4/4 space-y-20">
                    <div v-if="Object.keys(jobs).length > 0 " class="text-left">
                        <h1 class="ml-1 text-4xl font-serif text-gray-100 mt-10 ">{{ __('results.your_compatibility_results') }}</h1>
                        <p class="ml-3 mt-2 text-lg text-slate-300">{{ __('results.scores_and_matches') }}</p>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <div
                                class="DiscoveryCard__image"
                                :style="[imageStyle, showArchetypeModel ? { backgroundColor: 'blue' } : {}]"
                                @click="openArchetypeModel"
                            >
                                <span>{{ Archetype.name.charAt(0) }}</span>
                            </div>
                        </div>
                        
                    
                    
                    </div>
                  




                    <!-- Closest Jobs Section -->
                 
                    <div class="DashboardPage__careers">
                        <div
                            v-for="job in combinedJobs"
                            :key="job.slug"
                            class="HorizontalCard HorizontalCard--wide"
                            :aria-labelledby="`HorizontalCard-${job.slug}`"
                            @click="selectedJob = job"
                        >
                            <img
                                class="HorizontalCard-image"
                                :src="job.image"
                                :alt="job.career"
                                role="presentation"
                                aria-hidden="true"
                            />
                            <div class="HorizontalCard-wrap">
                                <div :id="`HorizontalCard-${job.slug}`" class="HorizontalCard-name">
                                    <a :href="job.link" class="HorizontalCard-name-link">{{ job.career }}</a>
                                </div>
                            </div>
                            <img
                                src="/path/to/arrow-image.png"
                                alt="Right arrow"
                                class="HorizontalCard-arrow"
                            />
                        </div>
                    </div>
                    <Model v-if="selectedJob" :job="selectedJob" @close="selectedJob = null" />
                    <Archetype
                            v-if="showArchetypeModel"
                            :archetype="Archetype"
                            :archetypeJobs="ArchetypeJobs"
                            :archetypeDiscovery="archetypeDiscovery"
                            :userId="userId"
                            @close="showArchetypeModel = false"
                        />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue';
import {router, Link ,} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import UpNext from "@/Components/UpNext.vue";
import Archetype from "@/Components/Archetype.vue";
import Model from "@/Components/Result/Model.vue";
import __ from '@/lang';

export default defineComponent({
    components: {
        Archetype,
        UpNext,
        AppLayout,
        Link,
        Model,
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
        combinedJobs: {
            type: Array,
            required: true,
        },
        archetypeDiscovery: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            isCollapsed: {}, // Tracks which categories are collapsed
            selectedJob: null, // Add this line to track the selected job
            cards: [
                {
                    id: 9883,
                    title: 'Set Designer',
                    link: '/careers/set-designer/reports/#match-insights',
                    imageUrl: 'https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_240,q_auto,w_240/v1689805064/bj5p1zcs3farfo74zagh.jpg',
                    rating: 5
                },
                {
                    id: 9879,
                    title: 'Food Stylist',
                    link: '/careers/food-stylist/reports/#match-insights',
                    imageUrl: 'https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_240,q_auto,w_240/v1705638257/m7whij0rzsinyuodnete.jpg',
                    rating: 5
                },
                // Add more card data here
            ],
            showArchetypeModel: false,
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

        showModel(job) {
            this.selectedJob = job;
        },

        closeModel() {
            this.selectedJob = null;
        },
        openArchetypeModel() {
            this.showArchetypeModel = true;
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
