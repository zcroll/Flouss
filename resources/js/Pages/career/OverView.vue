<script setup>
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const {props} = usePage();
const occupation = computed(() => props.occupation);
const knowledge = computed(() => props.knowledge);
const abilities = computed(() => props.abilities);
const technology = computed(() => props.activities);
const {description, title} = occupation.value;
</script>

<template>
    <app-layout>
    <StickySidebar :title="title">

            <div class="w-full lg:w-4/4 space-y-1">
                <div class="p-8 max-w-5xl mx-auto space-y-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">What is a {{ title }}</h1>
                    <p class="text-lg text-gray-700 leading-relaxed">{{ description }}</p>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Knowledge</h2>
                        <div v-for="item in knowledge" :key="item.element_name" class="mb-4 p-6 bg-indigo-50 rounded-lg shadow-sm">
                            <h3 class="text-xl font-medium text-indigo-900">{{ item.element_name }}</h3>
                            <p class="text-gray-700 mt-2">{{ item.description }}</p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Abilities</h2>
                        <div v-for="item in abilities" :key="item.element_id" class="mb-4 p-6 bg-green-50 rounded-lg shadow-sm">
                            <h3 class="text-xl font-medium text-green-900">{{ item.element_name }}</h3>
                            <p class="text-gray-700 mt-2">{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </div>


    </StickySidebar>
    </app-layout>

</template>

<style scoped>
h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

h2 {
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
}

p {
    line-height: 1.6;
}

.bg-indigo-50 {
    background-color: #ebf4ff;
}

.bg-green-50 {
    background-color: #f0fff4;
}

.text-indigo-900 {
    color: #3c366b;
}

.text-green-900 {
    color: #22543d;
}

.text-gray-900 {
    color: #1a202c;
}

.text-gray-700 {
    color: #4a5568;
}

.space-y-8 > :not([hidden]) ~ :not([hidden]) {
    --tw-space-y-reverse: 0;
    margin-top: calc(2rem * calc(1 - var(--tw-space-y-reverse)));
    margin-bottom: calc(2rem * var(--tw-space-y-reverse));
}
</style>
