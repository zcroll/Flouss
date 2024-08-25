<template>
    <AppLayout>
        <StickySidebar :title="occupation.title">
            <!-- Other components -->
            <JobLayout :items="tasks" />

            <UpNext
                :title="`See All Related Jobs`"
                description=""                ctaText="Find out more about it "
                backgroundColor="#c1ae03"
                imageSrc="https://www.careerexplorer.com/static/compiled/images/up-next-blobs/6-vert.svg"
                :CtaLink="``"
            />
        </StickySidebar>
    </AppLayout>
</template>

<script setup>
import {defineProps} from 'vue';
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
    tasks: {
        type: Array,
        required: true,
    },

});


// Method to get background image

</script>

<style scoped>
/* Add your styles here */
</style>
