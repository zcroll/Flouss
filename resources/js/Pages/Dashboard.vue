<template>
    <AppLayout head-title="A new way to find your path." head-sub-title="Embark on a journey of scholarly discovery and professional growth with our expert guidance.">
        <div class="py-12 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-8">

                    <!-- First Row: Left (4 columns) and Right (8 columns) -->
                    <div class="col-span-12 lg:col-span-4  cursor-pointer text-blue-600 rounded-xl flex justify-center items-center">
                        <div class="relative bg-black shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                            <!-- Image Container -->
                            <div class="relative">
                                <img
                                    src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80"
                                    alt="Aptitude Assessment"
                                    class="w-full h-48 object-cover"
                                />
                            </div>

                            <!-- Content Section -->
                            <div class="p-6">
                                <!-- Link at the Top -->
                                <div class="flex items-center gap-x-4 text-xs mb-4">
                                    <Link
                                        href="/activities"
                                        class="rounded-full bg-purple-100 px-3 py-1.5 font-medium text-purple-600 hover:bg-purple-200"
                                    >
                                        Aptitude Assessment
                                    </Link>
                                </div>

                                <!-- Heading -->
                                <h3 class="text-xl font-semibold text-gray-100 mb-3">
                                    <Link
                                        :href="hasResult ? '/results' : '/activities'"
                                        class="hover:underline"
                                    >
                                        {{ hasResult
                                        ? 'Analyze Your Assessment Results'
                                        : 'Evaluate Your Academic and Professional Aptitudes' }}
                                    </Link>
                                </h3>

                                <!-- Description -->
                                <p class="text-sm text-gray-300 mb-6 leading-relaxed">
                                    {{ hasResult
                                    ? 'Review your comprehensive assessment results to gain insights into your academic strengths and professional inclinations.'
                                    : 'Participate in our rigorous aptitude assessment to identify your academic strengths and professional competencies.' }}
                                </p>

                                <!-- Button at the Bottom -->
                                <div class="flex justify-end">
                                    <Link :href="hasResult ? '/results' : '/activities'">
                                        <PrimaryButton
                                            class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded"
                                        >
                                            {{ hasResult ? 'View Assessment Results' : 'Begin Assessment' }}
                                        </PrimaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-span-12 lg:col-span-8 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Career Opportunities" class="w-full h-48 object-cover" />
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-x-4 text-xs mb-4">
                                    <Link href="/categories/job-search" class="rounded-full bg-yellow-100 px-3 py-1.5 font-medium text-yellow-700 hover:bg-yellow-200">Career Exploration</Link>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                    <Link href="/job-search" class="hover:underline">
                                        Discover Career Opportunities
                                    </Link>
                                </h3>
                                <p class="text-sm text-gray-600 mb-6">Explore our curated database of career opportunities tailored to your academic background and professional aspirations. Embrace the chance to explore new horizons and make informed decisions about your future. Our platform is designed to support you in finding the right career fit, empowering you to take confident steps towards achieving your professional dreams.</p>
                                <div class="flex justify-end">
                                    <PrimaryButton @click="goToJobSearch" class="bg-[#fcba01] hover:bg-yellow-500 text-white font-semibold py-2 px-4 rounded">
                                        Explore Opportunities
                                    </PrimaryButton>
                                </div>
                            </div>

                    </div>
                    <!-- Second Row: 6 columns and 6 columns -->
                    <div class="col-span-12 lg:col-span-6 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Academic Advancement" class="w-full h-48 object-cover" />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-x-4 text-xs mb-4">
                                <Link href="/categories/education" class="rounded-full bg-green-100 px-3 py-1.5 font-medium text-green-600 hover:bg-green-200">Academic Advancement</Link>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                <Link href="/education" class="hover:underline">
                                    Enhance Your Academic Credentials
                                </Link>
                            </h3>
                            <p class="text-sm text-gray-600 mb-6">Examine a comprehensive array of academic degrees and professional certifications.</p>
                            <div class="flex justify-end">
                                <PrimaryButton @click="goToEducation" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                                    Explore Programs
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-6 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Aptitude Assessment" class="w-full h-48 object-cover" />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-x-4 text-xs mb-4">
                                <Link href="/etablissement" class="rounded-full bg-sky-200 px-3 py-1.5 font-medium text-sky-800 hover:bg-sky-300">Aptitude Assessment</Link>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                <span  class="hover:underline">
                                Quick look at Campus
                                </span>
                            </h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Schools and universities where you can nurture your passion.
                            </p>
                            <div class="flex justify-end">
                                <Link href="/etablissement">
                                    <PrimaryButton class="bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 px-4 rounded">
                                        Explore Schools
                                    </PrimaryButton>
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {defineProps} from 'vue';

const props = defineProps({
    hasResult: Boolean,
});

const goToJobSearch = () => {
    Inertia.visit('/job-search');
};

const goToEducation = () => {
    Inertia.visit('/activities');
};

const imageSrc = "https://www.jobteaser.com/_next/image/img.jpg?url=https://assets-cf.jobteaser.com/next-jobteaser/production/_next/static/media/document.1d9dcfb8.png&w=384&q=75";
const imageSrcSet = "/_next/image/img.jpg?url=https%3A%2F%2Fassets-cf.jobteaser.com%2Fnext-jobteaser%2Fproduction%2F_next%2Fstatic%2Fmedia%2Fdocument.1d9dcfb8.png&w=256&q=75 1x, /_next/image/img.jpg?url=https%3A%2F%2Fassets-cf.jobteaser.com%2Fnext-jobteaser%2Fproduction%2F_next%2Fstatic%2Fmedia%2Fdocument.1d9dcfb8.png&w=384&q=75 2x";
const altText = "Document";

</script>
