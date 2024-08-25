<template>
    <AppLayout>
        <StickySidebar :title="occupation.title">
            <!-- Other components -->
            <JobLayout :items="abilities" />
            <div class="text-2xl font- font-thin p-6 pt-9">Up Next</div>
            <UpNext
                :title="`Curious About What Will You Do As a ${occupation.title} ?` "
                description=""
                ctaText="Find out more about it "
                backgroundColor="black"

            />
        </StickySidebar>
    </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import UpNext from "@/Components/UpNext.vue";
import JobLayout from "@/Components/JobLayout.vue";


// Props
const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    abilities: {
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
