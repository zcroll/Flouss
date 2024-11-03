<template>
    <AppLayout :name-exist="true">
        <div class="mx-auto max-w-full px-4 lg:max-w-7xl lg:px-8 pb-40">
            <h2 class="text-center text-base/7 font-semibold text-indigo-600">Orientation is a Process, not a Decision .</h2>
            <p class="mx-auto mt-2 max-w-lg text-balance text-center text-4xl font-semibold tracking-tight text-gray-950 sm:text-5xl">welcome {{ $page.props.auth.user.name }} !</p>
            <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                <div class="relative lg:row-span-2">
                    <div class="absolute inset-px rounded-lg bg-white lg:rounded-l-[2rem]" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                        <div class="px-4 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                            <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                <span
                                    class="hover:underline">
                                        {{ hasResult ? __('dashboard.view_results') : __('dashboard.take_assessment') }}
                                </span>
                            </p>
                            <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">
                                {{ hasResult ? __('dashboard.review_assessment') : __('dashboard.participate_assessment') }}
                            </p>
                            <Link :href="hasResult ? '/results' : '/main-test'">
                                <PrimaryButton
                                    class="bg-[#a854b7] hover:bg-[#9e3cb2] text-white font-semibold py-2 px-4 rounded mt-2"
                                >
                                    {{ hasResult ? __('dashboard.view_assessment_results') : __('dashboard.begin_assessment') }}
                                </PrimaryButton>
                            </Link>
                        </div>
                        <div class="relative min-h-[30rem] w-full grow [container-type:inline-size] max-lg:mx-auto max-lg:max-w-sm">
                            <div class="absolute inset-x-10 bottom-0 top-10 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-700 bg-gray-900 shadow-2xl">
                                <img class="size-full object-cover object-top" src="/results_dasboard.png" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 lg:rounded-l-[2rem]" />
                </div>
                <div class="relative max-lg:row-start-1">
                    <div class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem]" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                        <div class="px-4 pt-8 sm:px-10 sm:pt-10">
                            <p class="text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">Liked Jobs</p>

                            <!-- Scrollable Row Section for Liked Jobs -->
                            <div class="mt-4 max-h-52 overflow-y-auto">
                                <ul role="list" class="mt-3 space-y-4">
                                    <li v-for="job in favoriteJobs" :key="job.id" class="flex rounded-md shadow-sm">
                                        <div class="flex w-16 flex-shrink-0 items-center justify-center rounded-l-md text-sm font-medium text-white bg-purple-600">
                                            {{ job.name.substring(0, 2).toUpperCase() }}
                                        </div>
                                        <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                                            <div class="flex-1 truncate px-4 py-2 text-sm">
                                                <Link :href="`/job/${job.slug}`" class="font-medium text-gray-900 hover:text-gray-600">
                                                    {{ job.name }}
                                                </Link>
                                            </div>
                                            <div class="flex-shrink-0 pr-2">
                                                <button @click="removeFavorite(job.id)" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Remove</span>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-t-[2rem]" />
                </div>
                <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                    <div class="absolute inset-px rounded-lg bg-white" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
                        <div class="px-4 pt-8 sm:px-10 sm:pt-10">
                            <p class="text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">Liked Formations</p>

                            <!-- Scrollable Row Section for Liked Formations -->
                            <div class="mt-4 max-h-52 overflow-y-auto">
                                <ul role="list" class="mt-3 space-y-4">
                                    <li v-for="degree in favoriteDegrees" :key="degree.id" class="flex rounded-md shadow-sm">
                                        <div class="flex w-16 flex-shrink-0 items-center justify-center rounded-l-md text-sm font-medium text-white bg-green-600">
                                            {{ degree.name.substring(0, 2).toUpperCase() }}
                                        </div>
                                        <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                                            <div class="flex-1 truncate px-4 py-2 text-sm">
                                                <Link :href="`/degree/${degree.slug}`" class="font-medium text-gray-900 hover:text-gray-600">
                                                    {{ degree.name }}
                                                </Link>
                                            </div>
                                            <div class="flex-shrink-0 pr-2">
                                                <button @click="removeFavorite(degree.id)" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Remove</span>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5" />
                </div>
                <div class="relative lg:row-span-2">
                    <div class="absolute inset-px rounded-lg max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]" style="background-image:url(https://res.cloudinary.com/hnpb47ejt/image/upload/v1591305946/rm6cdoxhlhtormnrlamm)" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                        <div class="px-4 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                            <p class="mt-2 text-3xl font-medium tracking-tight text-gray-50 max-lg:text-center">Your Orientation AI Assistant</p>
                            <p class="mt-2 max-w-lg text-sm/6 text-gray-100 max-lg:text-center">Make the Best Décision For a Bright Future.</p>
                        </div>
                        <div class="relative min-h-[30rem] w-full grow">
                            <div class="absolute bottom-0 left-10 right-0 top-10 overflow-hidden rounded-tl-xl bg-gray-900 shadow-2xl">
                                <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                                    <div class="-mb-px flex text-sm/6 font-medium text-gray-400">
                                        <div class="border-b border-r border-b-white/20 border-r-white/10 bg-white/5 px-4 py-2 text-white">Generating...</div>
                                        <div class="border-r border-gray-600/10 px-4 py-2"></div>
                                    </div>
                                </div>
                                <div class="px-6 pb-14 pt-6 text-white">
                                    <!-- Your code example -->

                                    <span class="text-lg font-medium ">{{ displayedText }}</span> 

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import {Link, router} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import __ from '@/lang';
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid';
import { ref, onMounted } from 'vue';

const props = defineProps({
    hasResult: Boolean,
    favoriteJobs: {
        type: Array,
        required: true
    },
    favoriteDegrees: {
        type: Array,
        required: true
    }
});

const goToJobSearch = () => {
    Inertia.visit('/job-search');
};

const goToEducation = () => {
    Inertia.visit('/activities');
};

const fullText = `Academic Orientation Report for 
Personality & Career Insights:
Personality Type: Enterprising, Social
Key Traits: Leadership, Collaboration, Initiative
Suggested Holland Code: ESC (Enterprising, Social, Conventional)
Big Five Traits Highlighted:
Extraversion: High
Agreeableness: Moderate
Conscientiousness: High
You enjoy working in dynamic environments where you can take charge, work closely with others, and achieve practical goals. You excel in roles that require communication, persuasion, and organization.`;

const displayedText = ref('');

const typingEffect = (text) => {
    let index = 0;
    const interval = setInterval(() => {
        if (index < text.length) {
            displayedText.value += text.charAt(index);
            index++;
        } else {
            clearInterval(interval);
        }
    }, 50); // Adjust typing speed here
};

onMounted(() => {
    typingEffect(fullText);
});

const removeFavorite = (id) => {
    router.delete(`/favorites/${id}`);
};
</script>
