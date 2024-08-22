<template>
    <app-layout title="Results" preserveScroll>

        <div class="relative max-w-7xl mx-auto mt-10">
            <div class="flex flex-col lg:flex-row">
                <!-- Main Content Section -->
                <div class="w-full lg:w-3/4 space-y-12">
                    <!-- Title Section -->
                    <div class="text-left">
                        <h1 class="ml-1 text-4xl font-extrabold text-gray-800 mb-4">Your Compatibility Results</h1>
                        <p class="text-lg text-gray-600">Below are your scores and best-fit career matches.</p>
                    </div>

                    <!-- Scores Section -->
                    <div class="bg-white p-8 rounded-lg shadow-lg space-y-6" v-if="Object.keys(scores).length > 0">
                        <h2 class="text-2xl font-bold text-gray-700">Scores by Category</h2>

                        <div class="space-y-6">
                            <div v-for="(score, category) in scores" :key="category">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xl font-semibold text-gray-800">{{ category }}</span>
                                    <span class="text-xl font-semibold text-gray-800">{{ score }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-8">
                                    <div
                                        :class="getCategoryColor(category)"
                                        class="h-8 rounded-full"
                                        :style="{ width: score * 10 + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Scores Available -->
                    <div v-else
                         class="flex flex-col items-center justify-center bg-gradient-to-r from-purple-400 to-pink-600 p-8 rounded-lg shadow-md text-white">
                        <h3 class="text-2xl font-bold mb-4">No Scores Available</h3>
                        <p class="text-center text-lg">It seems like you haven't taken the compatibility test yet. Take
                            a moment to complete the test and discover your best-fit career matches.</p>
                        <button @click="takeTest"
                                class="mt-6 bg-white text-purple-600 font-bold py-2 px-4 rounded-full hover:bg-gray-200">
                            Take the Test
                        </button>
                    </div>

                    <!-- Closest Jobs Section -->
                    <div v-if="Object.keys(jobs).length > 0" class="bg-transparent p-6 rounded-lg shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Closest Jobs</h2>
                        <ul role="list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <li v-for="(job, index) in jobs" :key="index" class="bg-white p-6 rounded-lg shadow-lg">
                                <div class="w-full h-40 bg-gray-200 rounded-t-lg overflow-hidden">
                                    <img
                                        src="https://via.placeholder.com/160x160"
                                        alt="Job Image"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <a @click="visitJob(job)"
                                   class="block mt-4 text-center text-xl font-semibold text-gray-800 cursor-pointer">
                                    {{ job }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- No Jobs Available -->
                    <div v-else
                         class="flex flex-col items-center justify-center bg-gradient-to-r from-teal-500 to-green-700 p-8 rounded-lg shadow-md text-white">
                        <h3 class="text-2xl font-bold mb-4">No Jobs Found</h3>
                        <p class="text-center text-lg">We couldn't find any job matches for you yet. Please try again
                            later or take the test to improve your results.</p>
                    </div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import {defineComponent} from "vue";
import {router, Link} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import StickySidebar from "@/Pages/lib/StickySidebar.vue";

export default defineComponent({
    components: {
        StickySidebar,
        AppLayout,
        Link,
    },
    props: {
        scores: {
            type: Object,
            required: true,
        },
        jobs: {
            type: Object,
            required: true,
        },
    },
    methods: {
        getCategoryColor(category) {
            const colors = {
                Realistic: "bg-gradient-to-r from-teal-600 to-emerald-800",
                Investigative: "bg-gradient-to-r from-teal-600 to-emerald-800",
                Artistic: "bg-gradient-to-r from-teal-700 to-emerald-900",
                Social: "bg-gradient-to-r from-teal-600 to-emerald-800",
                Enterprising: "bg-gradient-to-r from-teal-700 to-emerald-800",
                Conventional: "bg-gradient-to-r from-teal-700 to-emerald-600",
            };
            return colors[category] || "bg-gray-500";
        },
        takeTest() {
            router.visit('/activities', {preserveScroll: true});
        },
        visitJob(job) {
            router.visit(`/career/${job}`, {preserveScroll: false});
        },
    },
});
</script>

<style scoped>
body {
    font-family: "Inter", sans-serif;
}

h1, h2, h3 {
    font-family: "Poppins", sans-serif;
}

p {
    font-family: "Inter", sans-serif;
}
</style>
