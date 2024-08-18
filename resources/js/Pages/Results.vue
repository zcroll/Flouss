<template>
    <app-layout title="Results" preserveScroll>
        <div class="relative max-w-7xl mx-auto mt-10">
            <div class="flex">
                <!-- Main Content Section -->
                <div class="w-full lg:w-3/4 space-y-12">
                    <!-- Title Section -->
                    <div class="text-left">
                        <h1 class=" ml-1 text-4xl font-extrabold text-gray-800 mb-4">Your Compatibility Results</h1>
                        <p class="text-lg text-gray-600">Below are your scores and best-fit career matches.</p>
                    </div>

                    <!-- Scores Section -->
                    <div class="bg-white p-8 rounded-lg shadow-lg space-y-6">
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
                                        :style="{ width: score*10 + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Closest Jobs Section -->

                    <div class="bg-transparent p-6 rounded-lg shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Closest Jobs</h2>
                        <ul role="list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <!-- Iterate over transformed closest_jobs -->
                            <li v-for="(job, index) in jobs" :key="index" class="bg-white p-6 rounded-lg shadow-lg">
                                <div class="w-full h-40 bg-gray-200 rounded-t-lg overflow-hidden">
                                    <img
                                        src="https://via.placeholder.com/160x160"
                                        alt="Job Image"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <a @click="visitJob(job)" class="block mt-4 cursor-pointer">
                                    {{ job }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!--                    <div class="hidden lg:block">-->
                    <!--                        <div class="fixed top-24 right-12 bg-white p-6 rounded-lg shadow-xl space-y-4">-->
                    <!--                            <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Overview</h3>-->
                    <!--                            <div class="flex space-x-4">-->
                    <!--                                &lt;!&ndash; Mini Div 1 &ndash;&gt;-->
                    <!--                                <div class="flex-1 bg-gradient-to-r from-purple-950 to-purple-900 rounded-lg h-24 flex flex-col items-center justify-center text-white font-bold px-4">-->
                    <!--                                    <p>Overview</p>-->
                    <!--                                </div>-->
                    <!--                                &lt;!&ndash; Mini Div 2 &ndash;&gt;-->
                    <!--                                <div class="flex-1 bg-gradient-to-r from-teal-900 to-emerald-950 rounded-lg h-24 flex items-center justify-center text-white font-bold px-4">-->
                    <!--                                    <p>Compatibility</p>-->
                    <!--                                </div>-->
                    <!--                                &lt;!&ndash; Mini Div 3 &ndash;&gt;-->
                    <!--                                <div class="flex-1 bg-gradient-to-r from-red-900 to-amber-900 rounded-lg h-24 flex items-center justify-center text-white font-bold px-4">-->
                    <!--                                    <p>University</p>-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                </div>

                <!-- Fixed Card Section -->

            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from "vue";
import {router,Link} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";


export default defineComponent({
    components: {
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

        visitJob(job) {
            router.visit(`/career/${job}`, {preserveScroll: false});
        },

    }
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
