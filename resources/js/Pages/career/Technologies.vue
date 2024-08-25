<template>
    <AppLayout>
        <StickySidebar :title="occupation.title">
            <!-- Other components -->
            <JobLayout :items="technologySkills" />
            <div class="text-2xl font- font-thin p-6 pt-9">Up Next</div>
            <UpNext
                :title="`Understand The Key Tasks You Will Be Performing Beforehand `"
                description=""                ctaText="Find out more about it "
                backgroundColor="#006ea5"
                imageSrc="https://www.careerexplorer.com/static/compiled/images/up-next-blobs/1-vert.svg"
                :CtaLink="``"
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
    technologySkills: {
        type: Array,
        required: true,
    },

});


// Method to get background image

</script>

<style scoped>
/* Add your styles here */
</style>
