<template>
    <AppLayout :name-exist="true" :it-is-visible="true">
        <div class="mx-auto max-w-full px-4 lg:max-w-7xl lg:px-8 pt-20 pb-40">
            <h2 class="text-center text-base/7 font-semibold text-indigo-600">{{ __('dashboard.orientation_process') }}</h2>
            <p class="mx-auto mt-2  text-center text-4xl font-semibold tracking-tight text-gray-950 sm:text-5xl">{{ __('dashboard.welcome') }} {{ $page.props.auth.user.name }} !</p>
            <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                <div class="relative lg:row-span-2">
                    <div class="absolute inset-px rounded-lg bg-stone-950 lg:rounded-l-[2rem]" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                        <div class="px-4 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                            <p class="mt-2 text-lg font-medium tracking-tight text-stone-200 max-lg:text-center">
                                <span
                                    class="hover:underline">
                                        {{ hasResult ? __('dashboard.view_results') : __('dashboard.take_assessment') }}
                                </span>
                            </p>
                            <p class="mt-2 max-w-lg text-sm/6 text-stone-200 max-lg:text-center">
                                {{ hasResult ? __('dashboard.review_assessment') : __('dashboard.participate_assessment') }}
                            </p>
                            <Link :href="hasResult ? '/results' : '/main-test'">
                                <PrimaryButton
                                    class="bg-stone-500 hover:bg-stone-700 text-white font-semibold py-2 px-4 rounded mt-2"
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
                    <div class="absolute inset-px rounded-lg bg-stone-950 max-lg:rounded-t-[2rem]" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                        <div class="px-4 pt-8 sm:px-10 sm:pt-10">
                            <p class="text-lg font-medium tracking-tight text-stone-200 max-lg:text-center">{{ __('dashboard.liked_jobs') }}</p>

                            <!-- Scrollable Row Section for Liked Jobs -->
                            <div class="mt-4 max-h-52 overflow-y-auto">
                                <ul role="list" class="mt-3 space-y-4">
                                    <li v-for="job in favoriteJobs" :key="job.id" class="flex rounded-md shadow-sm">

                                        <div class="flex flex-1 items-center justify-between truncate rounded-md py-2 border-stone-400 bg-stone-700">
                                            <div class="flex-1 truncate px-4 py-2 text-sm">
                                                <Link :href="`/career/${job.slug}`" class="font-light text-stone-200 text-[20px]">
                                                    {{ job.name }}
                                                </Link>
                                            </div>
                                            <div class="flex-shrink-0 pr-2">
                                                <button @click="removeFavorite(job.id)" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 hover:text-gray-500">


                                                <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
<svg width="50px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6.99486 7.00636C6.60433 7.39689 6.60433 8.03005 6.99486 8.42058L10.58 12.0057L6.99486 15.5909C6.60433 15.9814 6.60433 16.6146 6.99486 17.0051C7.38538 17.3956 8.01855 17.3956 8.40907 17.0051L11.9942 13.4199L15.5794 17.0051C15.9699 17.3956 16.6031 17.3956 16.9936 17.0051C17.3841 16.6146 17.3841 15.9814 16.9936 15.5909L13.4084 12.0057L16.9936 8.42059C17.3841 8.03007 17.3841 7.3969 16.9936 7.00638C16.603 6.61585 15.9699 6.61585 15.5794 7.00638L11.9942 10.5915L8.40907 7.00636C8.01855 6.61584 7.38538 6.61584 6.99486 7.00636Z" fill="#0F0F0F"/>
</svg>

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
                    <div class="absolute inset-px rounded-lg bg-stone-950" />
                    <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
                        <div class="px-4 pt-8 sm:px-10 sm:pt-10">
                            <p class="text-lg font-medium tracking-tight text-stone-200 max-lg:text-center">{{ __('dashboard.liked_formations') }}</p>

                            <!-- Scrollable Row Section for Liked Formations -->
                            <div class="mt-4 max-h-52 overflow-y-auto">
                                <ul role="list" class="mt-3 space-y-4">
                                    <li v-for="degree in favoriteDegrees" :key="degree.id" class="flex rounded-md shadow-sm">

                                        <div class="flex flex-1 items-center justify-between truncate rounded-md py-2 border-stone-400 bg-stone-700">
                                            <div class="flex-1 truncate px-4 py-2 text-sm">
                                                <Link :href="`/degree/${degree.slug}`" class="font-light text-stone-200 text-[20px] ">
                                                    {{ degree.name }}
                                                </Link>
                                            </div>
                                            <div class="flex-shrink-0 pr-2">
                                                <button @click="removeFavorite(degree.id)" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 hover:text-gray-500">
                                                    <svg width="50px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6.99486 7.00636C6.60433 7.39689 6.60433 8.03005 6.99486 8.42058L10.58 12.0057L6.99486 15.5909C6.60433 15.9814 6.60433 16.6146 6.99486 17.0051C7.38538 17.3956 8.01855 17.3956 8.40907 17.0051L11.9942 13.4199L15.5794 17.0051C15.9699 17.3956 16.6031 17.3956 16.9936 17.0051C17.3841 16.6146 17.3841 15.9814 16.9936 15.5909L13.4084 12.0057L16.9936 8.42059C17.3841 8.03007 17.3841 7.3969 16.9936 7.00638C16.603 6.61585 15.9699 6.61585 15.5794 7.00638L11.9942 10.5915L8.40907 7.00636C8.01855 6.61584 7.38538 6.61584 6.99486 7.00636Z" fill="#0F0F0F"/>
</svg>
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
                            <p class="mt-2 text-3xl font-medium tracking-tight text-stone-300 max-lg:text-center">{{ __('dashboard.orientation_ai_assistant') }}</p>
                            <p class="mt-2 max-w-lg text-sm/6 text-gray-100 max-lg:text-center">{{ __('dashboard.best_decision') }}</p>
                        </div>
                        <div class="relative min-h-[30rem] w-full grow">
                            <div class="absolute bottom-0 left-10 right-0 top-10 overflow-hidden rounded-tl-xl bg-stone-950 shadow-2xl">
                                <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                                    <div class="-mb-px flex text-sm/6 font-medium text-gray-400">
                                        <div class="border-b border-r border-b-stone-300/20 border-r-white/10 bg-white/5 px-4 py-2 text-stone-300">Generating...</div>
                                        <div class="border-r border-stone-600/10 px-4 py-2"></div>
                                    </div>
                                </div>
                                <div class="px-6 pb-14 pt-6 text-stone-300">
                                    <!-- Your code example -->

                                    <span class="text-sm font-medium ">{{ displayedText }}</span>

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
