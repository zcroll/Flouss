<template>
    <AppLayout>
        <StickySidebar :title="occupation.title">
            <!-- Other components -->
            <JobLayout :items="workActicities" />
        </StickySidebar>
    </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JobLayout from "@/Components/JobLayout.vue";

// Props
const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    workActicities: {
        type: Array,
        required: true,
    },
    baseUri: {
        type: String,
        required: true,
    }
});



// Method to get background image

</script>

<style scoped>
/* Add your styles here */
</style>
