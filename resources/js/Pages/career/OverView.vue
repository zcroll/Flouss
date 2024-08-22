<script setup>
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";

const {props} = usePage();
const occupation = computed(() => props.occupation);
const knowledge = computed(() => props.knowledge);
const abilities = computed(() => props.abilities);
const technology = computed(() => props.activities);
const {description, title} = occupation.value;
</script>

<template>
    <StickySidebar :title="title">

    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">{{ title }}</h1>
        <p class="text-lg mb-6">{{ description }}</p>

        <div>
            <h2 class="text-2xl font-semibold mb-2">Knowledge</h2>
            <div v-for="item in knowledge" :key="item.element_name" class="mb-4 rounded-lg bg-blue-50 p-4 shadow">
                <h3 class="text-xl font-medium">{{ item.element_name }}</h3>

            </div>
        </div>

        <div>

            <h2 class="text-2xl font-semibold mb-2">abilities</h2>
            <div v-for="item in abilities" :key="item.element_id" class="mb-4 rounded-lg bg-green-50 p-4 shadow">
                <h3 class="text-xl font-medium">{{ item.element_name }}</h3>

            </div>

        </div>
    </div>
    </StickySidebar>

</template>

<style scoped>

</style>
