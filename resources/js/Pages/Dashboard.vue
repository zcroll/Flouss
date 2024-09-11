<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-indigo-100 to-purple-100 py-12 sm:py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-12">
          <h2 class="text-3xl font-serif font-bold tracking-tight text-indigo-900 sm:text-4xl">Academic and Career Exploration</h2>
          <p class="mt-4 text-lg leading-7 text-indigo-700">Embark on a journey of scholarly discovery and professional growth with our expert guidance.</p>
        </div>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <article class="bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Career Opportunities" class="w-full h-48 object-cover" />
            </div>
            <div class="p-6">
              <div class="flex items-center gap-x-4 text-xs mb-4">
                <Link href="/categories/job-search" class="rounded-full bg-blue-100 px-3 py-1.5 font-medium text-blue-600 hover:bg-blue-200">Career Exploration</Link>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                <Link href="/job-search" class="hover:underline">
                  Discover Career Opportunities
                </Link>
              </h3>
              <p class="text-sm text-gray-600 mb-6">Explore our curated database of career opportunities tailored to your academic background and professional aspirations. Analyze various industries, roles, and organizations to identify the optimal career path that aligns with your scholarly pursuits and professional goals.</p>
              <div class="flex justify-end">
                <PrimaryButton @click="goToJobSearch" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                  Explore Opportunities
                </PrimaryButton>
              </div>
            </div>
          </article>

          <article class="bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
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
              <p class="text-sm text-gray-600 mb-6">Examine a comprehensive array of academic degrees and professional certifications. Identify programs that complement your intellectual interests and career objectives, spanning from traditional scholarly paths to specialized professional qualifications.</p>
              <div class="flex justify-end">
                <PrimaryButton @click="goToEducation" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                  Explore Programs
                </PrimaryButton>
              </div>
            </div>
          </article>

          <article class="bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Aptitude Assessment" class="w-full h-48 object-cover" />
            </div>
            <div class="p-6">
              <div class="flex items-center gap-x-4 text-xs mb-4">
                <Link href="/activities" class="rounded-full bg-purple-100 px-3 py-1.5 font-medium text-purple-600 hover:bg-purple-200">Aptitude Assessment</Link>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                <Link :href="hasResult ? '/results' : '/activities'" class="hover:underline">
                  {{ hasResult ? 'Analyze Your Assessment Results' : 'Evaluate Your Academic and Professional Aptitudes' }}
                </Link>
              </h3>
              <p class="text-sm text-gray-600 mb-6">
                {{ hasResult 
                  ? 'Review your comprehensive assessment results to gain insights into your academic strengths and professional inclinations. Discover career paths that align with your intellectual capabilities and scholarly interests.' 
                  : 'Participate in our rigorous aptitude assessment to identify your academic strengths and professional competencies. Let our expert analysis guide you towards a fulfilling career path that leverages your unique intellectual talents and scholarly pursuits.' }}
              </p>
              <div class="flex justify-end">
                <Link :href="hasResult ? '/results' : '/activities'">
                  <PrimaryButton class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded">
                    {{ hasResult ? 'View Assessment Results' : 'Begin Assessment' }}
                  </PrimaryButton>
                </Link>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';

const props = defineProps({
  hasResult: Boolean,
});

const goToJobSearch = () => {
  Inertia.visit('/job-search');
};

const goToEducation = () => {
  Inertia.visit('/activities');
};

const goToCareerTest = () => {
  Inertia.visit('/career-test');
};
</script>