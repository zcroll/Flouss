<script>
import { defineComponent } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
    components: {
        AppLayout,
    },
    props: {
        closestJobs: {
            type: Array,
            default: () => [],
        },
    },
    setup(props) {
        return {
            // You can add any reactive data or computed properties here
        };
    },
});
</script>

<template>
    <app-layout title="Activity Summary">
        <div class="max-w-3xl mx-auto mt-10 px-4 sm:px-0">
            <h2 class="text-2xl font-bold mb-6 text-white">Your Results</h2>
            
            <div v-if="closestJobs && closestJobs.length > 0">
                <h3 class="text-xl font-semibold mb-4 text-white">Closest Matching Jobs</h3>
                <ul class="list-disc list-inside mb-4">
                    <li v-for="(job, index) in closestJobs" :key="index" class="text-gray-300 mb-2">
                        {{ job.title }} (Match: {{ job.match_percentage }}%)
                    </li>
                </ul>
            </div>
            
            <div v-else>
                <p class="text-gray-300">No matching jobs found. Please try again or contact support.</p>
            </div>
        </div>
    </app-layout>
</template>