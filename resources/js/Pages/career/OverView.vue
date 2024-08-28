<script setup lang="ts">
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

interface Occupation {
    name: string;
    // description: string;
    image: string;
    slug: string;
}

interface Props {
    occupation: Occupation;
}

const {props} = usePage<Props>();
const occupation = computed(() => props.occupation);
const name = computed(() => occupation.value.name);
const image = computed(() => occupation.value.image);
const slug = computed(() => occupation.value.slug);
</script>

<template>
    <appLayout>
        <StickySidebar :slug="slug" :title="name" :image="image">

            <div class="w-full lg:w-4/4 space-y-1">
                <div class="p-8 max-w-5xl mx-auto space-y-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">What is a {{ name }}</h1>
                    <!-- <p class="text-lg text-gray-700 leading-relaxed">{{ occupation.description }}</p> -->
                    <div class="w-3/4">
                        <iframe class="rounded-xl" width="950" height="570"
                                src="https://www.youtube.com/embed/5YDVJaItmaY"
                                title="WEB DEVELOPMENT explained in 10 minutes (2020)" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </StickySidebar>
    </appLayout>
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

.text-gray-900 {
    color: #1a202c;
}

.text-gray-700 {
    color: #4a5568;
}


</style>
