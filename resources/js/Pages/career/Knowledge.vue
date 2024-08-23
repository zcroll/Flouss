<template>
    <StickySidebar :title="occupation.title">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ occupation.title }} - Knowledge</h1>
            <div v-if="knowledge.length">
                <ul class="space-y-2">
                    <li v-for="knowledgeItem in knowledge" :key="knowledgeItem.element_id">
                        <p>{{ knowledgeItem.element_name }} - {{ knowledgeItem.description }}</p>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>No knowledge data available.</p>
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
    knowledge: {
        type: Array,
        required: true
    },
    baseUri: {
        type: String,
        required: true
    }
});
</script>

<style scoped>
/* Add your styles here */
</style>
