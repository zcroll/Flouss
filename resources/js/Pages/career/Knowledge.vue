<template>
    <AppLayout>
        <StickySidebar :title="occupation.title">
            <!-- Other components -->
            <JobLayout :items="knowledge" />
            <div class="text-2xl font- font-thin p-6 pt-9">Up Next</div>
            <UpNext
                :title="`All The Technologies You Will Need To Grasp As ${occupation.title} ?` "
                description=""                ctaText="Find out more about it "
                backgroundColor="#bc7700"
                imageSrc="https://www.careerexplorer.com/static/compiled/images/up-next-blobs/7-vert.svg"
                :CtaLink="``"
            />
        </StickySidebar>
    </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

import JobLayout from "@/Components/JobLayout.vue";
import UpNext from "@/Components/UpNext.vue";

// Props
const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    knowledge: {
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
