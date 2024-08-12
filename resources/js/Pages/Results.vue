<template>
    <app-layout title="Results">
        <div class="max-w-3xl mx-auto mt-10">
            <h1 class="text-2xl font-bold mb-6">Results</h1>
            <div class="mb-6">
                <h2 class="text-xl font-bold">Scores</h2>
                <ul>
                    <li v-for="(score, category) in scores" :key="category">{{ category }}: {{ score }}</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold">Closest Jobs</h2>
                <ul>
                    <li v-for="job in closest_jobs" :key="job">{{ job }}</li>
                </ul>
            </div>
            <div v-if="top_3_jobs_in_zone && top_3_jobs_in_zone.length">
                <h2 class="text-xl font-bold">Top 3 Jobs in Zone</h2>
                <ul>
                    <li v-for="job in top_3_jobs_in_zone" :key="job">{{ job }}</li>
                </ul>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    components: {AppLayout},
    props: {
        scores: Object,
        closest_jobs: Array,
        top_3_jobs_in_zone: Array,
    },

};
</script>

<style scoped>
</style>
