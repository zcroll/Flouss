<template>
    <StickySidebar :title="occupation.title">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ occupation.title }} - Work Activities</h1>
            <div v-if="workActivities.length">
                <ul class="space-y-2">
                    <li v-for="activity in workActivities" :key="activity.element_id">
                        <p>{{ activity.element_name }}</p>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>No work activities data available.</p>
            </div>
        </div>
    </StickySidebar>
</template>

<script setup>
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";

const props = defineProps({
    occupation: {
        type: Object,
        required: true
    },
    workActivities: {
        type: Array,
        required: true
    }
});
</script>

<style scoped>
/* Add your styles here */
</style>
