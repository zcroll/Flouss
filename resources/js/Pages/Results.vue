<template>
    <app-layout title="Results" preserveScroll>
        <div class="relative max-w-7xl mx-auto mt-10">
            <div class="flex flex-col lg:flex-row">
                <!-- Main Content Section -->
                <div class="w-full lg:w-4/4 space-y-20">
                    <!-- Title Section -->
                    <div v-if="Object.keys(jobs).length > 0 " class="text-left mt-10">
                        <h1 class="ml-1 text-4xl font-serif text-gray-800 mt-10 ">Your Compatibility Results</h1>
                        <p class="ml-3 mt-2 text-lg text-gray-600">Below are your scores and best-fit career matches.</p>
                    </div>

                    <!-- Scores Section -->
                    <div v-if="Object.keys(scores).length > 0">
                        <div
                            v-for="(score, category) in scores"
                            :key="category"
                            class="relative bg-white p-4 rounded-lg shadow-sm border border-gray-200 m-5"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-base font-semibold text-gray-800">{{ category }}</span>
                                <button
                                    @click="toggleCollapse(category)"
                                    class="relative flex items-center text-xs font-semibold text-left transition-all ease-in cursor-pointer text-gray-700 group"
                                >
                                    <i
                                        class="ml-auto transition-transform duration-300 transform"
                                        :class="{ 'rotate-180': isCollapsed[category] }"
                                    >
                                        &#9660; <!-- Simple down arrow icon -->
                                    </i>
                                </button>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-5 mt-2">
                                <div
                                    :class="getCategoryColor(category)"
                                    class="h-5 rounded-full"
                                    :style="{ width: score * 10 + '%' }"
                                ></div>
                            </div>
                            <div v-if="isCollapsed[category]" class="transition-all duration-300 ease-in-out mt-3">
                                <div class="text-sm text-gray-600 leading-relaxed">
                                    {{ getCategoryDescription(category) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Scores Available -->
                    <div v-else class="flex flex-col items-center justify-center">
                        <UpNext
                            title="No Scores Available"
                            description="It seems like you haven't taken the compatibility test yet. Take
                            a moment to complete the test and discover your best-fit career matches."
                            ctaText="Take the Test"
                            ctaLink="/activities"
                            image-src="https://www.careerexplorer.com/static/compiled/images/up-next-blobs/11-vert.svg"
                            backgroundColor="#C27A36"
                        />
                    </div>

                    <!-- Closest Jobs Section -->
                    <div v-if="Object.keys(jobs).length > 0 " class="text-left ">
                        <h1 class="ml-1 text-4xl font-serif text-gray-800  ">Your best-fit career matches.</h1>
                        <p class="ml-3 mt-2 text-lg text-gray-600">
                            Based on your responses, here are some jobs that you can excel in more than everyone else.
                        </p>
                    </div>
                    <div v-if="Object.keys(jobs).length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 bg-transparent rounded-lg shadow-sm">

                        <div
                            v-for="(job, index) in jobs"
                            :key="index"
                            class="relative flex items-center space-x-4 rounded-lg border border-gray-300 bg-white p-5 shadow-sm hover:border-indigo-500 transition duration-200"
                        >
                            <div class="flex-shrink-0">
                                <img
                                    class="h-12 w-12 rounded-full"
                                    :src="job.imageUrl || 'https://via.placeholder.com/160x160'"
                                    alt="Job Image"
                                />
                            </div>
                            <div class="min-w-0 flex-1">
                                <a @click="visitJob(job)" class="block focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <p class="text-base font-medium text-gray-900">{{ job }}</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- No Jobs Available -->
                    <div v-else class="flex flex-col items-center justify-center">
                        <UpNext
                            title="No Jobs Found"
                            description="We couldn't find any job matches for you yet. Please try again
                            later or take the test to improve your results."
                            image-src="https://www.careerexplorer.com/static/compiled/images/up-next-blobs/1-vert.svg"
                            backgroundColor="#005C46"
                        />
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
import UpNext from "@/Components/UpNext.vue";

export default defineComponent({
    components: {
        UpNext,
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
    data() {
        return {
            isCollapsed: {}, // Tracks which categories are collapsed
        };
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
        toggleCollapse(category) {
            this.isCollapsed[category] = !this.isCollapsed[category];
        },
        getCategoryDescription(category) {
            const descriptions = {
                Realistic: "A realistic person is someone who is very body-oriented. This individual enjoys using their hands and eyes to solve practical problems. They like doing outdoor, mechanical, and physical activities. It’s very natural for a realistic person to relate to the physical world—this type of person usually does not deal with problems concerning ideas, data, or people, but rather, they like to concentrate on problems they can solve with their hands.",
                Investigative: "An investigative person is someone who lives in the mind. To solve problems, they prefer reading and studying, books and text, rather than their using their hands. They tend to analyze situations before making decisions. Investigative people are independent thinkers that are both curious and insightful.",
                Artistic:  "An artistic person is someone who likes to be involved with forms, patterns, and designs in life. They like to use their minds and hands to create things. An artistic person enjoys unusual people, sight, sounds, and textures—they have a lot of spirit and enthusiasm. The enjoy using their own creativity and imagination.",
                Social: "A social person is someone who thrives in social situations. They like to work with other people and generally love helping others. They are empathetic, caring, and their sensitive nature makes them very good at understanding other people’s mood and feelings. Social people are good at generating positive energy for a good cause.",
                Enterprising: "An enterprising person is someone who makes an excellent leader. They are excellent problem solvers and enjoy sales and management roles. This type of person is extroverted, and while they may seem restless or irresponsible, their energy and ability to take risks is the reason many projects get started and stay successful.",
                Conventional: "A conventional person is someone who is careful, quiet, and pays close attention to detail. Following a set of rules appeals to these people as they like to feel secure and certain. They prefer to carry out tasks assigned by others rather than take on a leadership role. They are typically neat, tidy, and enjoy working with data in structured settings.",
            };
            return descriptions[category] || "No description available.";
        },
        visitJob(job) {
            const formattedJob = job.replace(/ /g, '-');
            router.visit(`/career/${formattedJob}`, {preserveScroll: false});
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
