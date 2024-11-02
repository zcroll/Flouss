<template>
    <AppLayout :name-exist="true">
<!--        <div class="py-12 sm:py-16">
            <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-8">

                    &lt;!&ndash; First Row: Left (4 columns) and Right (8 columns) &ndash;&gt;
                    <div class="col-span-12 lg:col-span-4 cursor-pointer text-blue-600 rounded-xl flex justify-center items-center">
                        <div class="relative bg-black shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                            &lt;!&ndash; Image Container &ndash;&gt;
                            <div class="relative">
                                <img
                                    src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80"
                                    alt="Aptitude Assessment"
                                    class="w-full h-48 object-cover"
                                />
                            </div>

                            &lt;!&ndash; Content Section &ndash;&gt;
                            <div class="p-4">
                                <div class="flex items-center gap-x-4 text-xs mb-4">
                                    <Link
                                        :href="'/test-preview'"
                                        class="rounded-full bg-purple-100 px-3 py-1.5 font-medium text-purple-600 hover:bg-purple-200"
                                    >
                                        {{ __('dashboard.aptitude_assessment') }}
                                    </Link>
                                </div>

                                &lt;!&ndash; Heading &ndash;&gt;
                                <h2 class="text-xl font-semibold text-gray-100 mb-3">
                                    <span
                                        :href="hasResult ? '/results' : '/test-preview'"
                                        class="hover:underline"
                                    >
                                        {{ hasResult ? __('dashboard.view_results') : __('dashboard.take_assessment') }}
                                    </span>
                                </h2>

                                &lt;!&ndash; Description &ndash;&gt;
                                <p class="text-sm text-gray-300 mb-6 leading-relaxed">
                                    {{ hasResult
                                    ? __('dashboard.review_assessment')
                                    : __('dashboard.participate_assessment') }}
                                </p>

                                <div class="flex justify-end">
                                    <Link :href="hasResult ? '/results' : '/main-test'">
                                        <PrimaryButton
                                            class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded"
                                        >
                                            {{ hasResult ? __('dashboard.view_assessment_results') : __('dashboard.begin_assessment') }}
                                        </PrimaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    &lt;!&ndash; Right Column &ndash;&gt;
                    <div class="col-span-12 lg:col-span-8 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Career Opportunities" class="w-full h-48 object-cover" />
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-x-4 text-xs mb-4">
                                    <Link href="/categories/job-search" class="rounded-full bg-yellow-100 px-3 py-1.5 font-medium text-yellow-700 hover:bg-yellow-200">{{ __('dashboard.career_exploration') }}</Link>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-3">
                                    <span class="hover:underline">
                                        {{ __('dashboard.discover_opportunities') }}
                                    </span>
                                </h2>
                                <p class="text-sm text-gray-600 mb-6">{{ __('dashboard.explore_careers') }}</p>
                                <div class="flex justify-end">
                                    <Link href="/jobs" class="inline-block">
                                        <PrimaryButton class="bg-amber-400 hover:bg-yellow-500 text-white font-semibold py-2 px-4 rounded">
                                            {{ __('dashboard.explore_opportunities') }}
                                        </PrimaryButton>
                                    </Link>
                                </div>
                            </div>

                    </div>
                    &lt;!&ndash; Second Row: 6 columns and 6 columns &ndash;&gt;
                    <div class="col-span-12 lg:col-span-6 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Academic Advancement" class="w-full h-48 object-cover" />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-x-4 text-xs mb-4">
                                <Link href="/categories/education" class="rounded-full bg-green-100 px-3 py-1.5 font-medium text-green-600 hover:bg-green-200">{{ __('dashboard.academic_advancement') }}</Link>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">
                                <span class="hover:underline">
                                    {{ __('dashboard.enhance_credentials') }}
                                </span>
                            </h2>
                            <p class="text-sm text-gray-600 mb-6">{{ __('dashboard.explore_degrees') }}</p>
                            <div class="flex justify-end">
                                <Link href="/degrees">
                                    <PrimaryButton class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                                        {{ __('dashboard.explore_programs') }}
                                    </PrimaryButton>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-6 bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:-translate-y-1">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" alt="Aptitude Assessment" class="w-full h-48 object-cover" />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-x-4 text-xs mb-4">
                                <Link href="/etablissement" class="rounded-full bg-sky-200 px-3 py-1.5 font-medium text-sky-800 hover:bg-sky-300">{{ __('dashboard.aptitude_assessment') }}</Link>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">
                                <span  class="hover:underline">
                                {{ __('dashboard.quick_look_campus') }}
                                </span>
                            </h2>
                            <p class="text-sm text-gray-600 mb-6">
                                {{ __('dashboard.nurture_passion') }}
                            </p>
                            <div class="flex justify-end">
                                <Link href="/etablissement">
                                    <PrimaryButton class="bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 px-4 rounded">
                                        {{ __('dashboard.explore_schools') }}
                                    </PrimaryButton>
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>-->


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

              <!-- New Scrollable Row Section for Liked Jobs -->
              <div class="mt-4 max-h-52 overflow-y-auto">
                  <ul role="list" class="mt-3 space-y-4">
                      <li v-for="project in projects" :key="project.name" class="flex rounded-md shadow-sm">
                          <div :class="[project.bgColor, 'flex w-16 flex-shrink-0 items-center justify-center rounded-l-md text-sm font-medium text-white']">{{ project.initials }}</div>
                          <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                              <div class="flex-1 truncate px-4 py-2 text-sm">
                                  <a :href="project.href" class="font-medium text-gray-900 hover:text-gray-600">{{ project.name }}</a>
                                  <p class="text-gray-500">{{ project.members }} Members</p>
                              </div>
                              <div class="flex-shrink-0 pr-2">
                                  <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                      <span class="sr-only">Open options</span>
                                      <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                  </button>
                              </div>
                          </div>
                      </li>
                  </ul>
              </div>
              <!-- End of New Scrollable Row Section for Liked Jobs -->
            </div>
      
          </div>
          <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-t-[2rem]" />
        </div>
        <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
          <div class="absolute inset-px rounded-lg bg-white" />
          <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
            <div class="px-4 pt-8 sm:px-10 sm:pt-10">
              <p class="text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">Liked Formations</p>


              <!-- New Scrollable Row Section for Liked Formations -->
              <div class="mt-4 max-h-52 overflow-y-auto">
                  <ul role="list" class="mt-3 space-y-4">
                      <li v-for="formation in formations" :key="formation.name" class="flex rounded-md shadow-sm">
                          <div :class="[formation.bgColor, 'flex w-16 flex-shrink-0 items-center justify-center rounded-l-md text-sm font-medium text-white']">{{ formation.initials }}</div>
                          <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                              <div class="flex-1 truncate px-4 py-2 text-sm">
                                  <a :href="formation.href" class="font-medium text-gray-900 hover:text-gray-600">{{ formation.name }}</a>
                                  <p class="text-gray-500">{{ formation.members }} Members</p>
                              </div>
                              <div class="flex-shrink-0 pr-2">
                                  <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                      <span class="sr-only">Open options</span>
                                      <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                  </button>
                              </div>
                          </div>
                      </li>
                  </ul>
              </div>
              <!-- End of New Scrollable Row Section for Liked Formations -->
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
import {Link} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import __ from '@/lang';
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid';
import { ref, onMounted } from 'vue';

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

const projects = [
    { name: 'Graph API', initials: 'GA', href: '#', members: 16, bgColor: 'bg-pink-600' },
    { name: 'Component Design', initials: 'CD', href: '#', members: 12, bgColor: 'bg-purple-600' },
    { name: 'Templates', initials: 'T', href: '#', members: 16, bgColor: 'bg-yellow-500' },
    { name: 'React Components', initials: 'RC', href: '#', members: 8, bgColor: 'bg-green-500' },
    { name: 'User Authentication', initials: 'UA', href: '#', members: 10, bgColor: 'bg-blue-600' },
    { name: 'Data Visualization', initials: 'DV', href: '#', members: 5, bgColor: 'bg-red-600' },
    { name: 'API Integration', initials: 'AI', href: '#', members: 20, bgColor: 'bg-indigo-600' },
    { name: 'Mobile App Development', initials: 'MAD', href: '#', members: 15, bgColor: 'bg-teal-600' },
    { name: 'E-commerce Platform', initials: 'EP', href: '#', members: 25, bgColor: 'bg-yellow-400' },
    { name: 'Machine Learning', initials: 'ML', href: '#', members: 7, bgColor: 'bg-gray-600' },
];

const formations = [
    { name: 'Data Science Bootcamp', initials: 'DSB', href: '#', members: 30, bgColor: 'bg-blue-500' },
    { name: 'Web Development Course', initials: 'WDC', href: '#', members: 25, bgColor: 'bg-green-500' },
    { name: 'Digital Marketing', initials: 'DM', href: '#', members: 20, bgColor: 'bg-yellow-500' },
    { name: 'Graphic Design', initials: 'GD', href: '#', members: 15, bgColor: 'bg-red-500' },
    { name: 'Project Management', initials: 'PM', href: '#', members: 10, bgColor: 'bg-purple-500' },
    { name: 'Cybersecurity Essentials', initials: 'CE', href: '#', members: 5, bgColor: 'bg-indigo-500' },
];

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
</script>
