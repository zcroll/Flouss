<template>
    <StickySidebar :title="occupation.title">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ occupation.title }} - Tasks</h1>
            <div v-if="tasks.length">
                <ul class="space-y-2">
                    <li v-for="task in tasks" :key="task.task_id">
                        <p>{{ task.task }}</p>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>No tasks data available.</p>
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
    tasks: {
        type: Array,
        required: true
    }
});
</script>

<style scoped>
/* Add your styles here */
</style>
