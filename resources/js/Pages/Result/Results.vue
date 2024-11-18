<template>
    <AppLayout title="Results">
        <div class="DashboardPage">
            <section class="section">
                <Folder
                    :archetype="Archetype"
                    :archetypeJobs="ArchetypeJobs"
                    :archetypeDiscovery="archetypeDiscovery"
                    :userId="userId"
                />
            </section>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2 class="text-[35px] text-gray-900" tabindex="0">
                            {{ __('results.top_careers') }}
                        </h2>
                    </div>
                    <span style="display: flex; gap: 8px"
                        ><button
                            class="Button DashboardPage__button DashboardPage__button--share"
                            data-on-page-nav="false"
                            data-on-page-nav-offset="0"
                        >
                            <span class="Button-icon"
                                ><svg
                                    aria-hidden="true"
                                    focusable="false"
                                    data-prefix="fad"
                                    data-icon="share-from-square"
                                    class="svg-inline--fa fa-share-from-square"
                                    role="img"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512"
                                >
                                    <g class="fa-duotone-group">
                                        <path
                                            class="fa-secondary"
                                            fill="currentColor"
                                            d="M416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z"
                                        ></path>
                                        <path
                                            class="fa-primary"
                                            fill="currentColor"
                                            d="M568.9 176.5l-150.9 138.2C404.8 326.8 384 316.1 384 298.2V223.1C256 224 186.7 252.2 227 380.4c4.473 14.22-12.8 25.24-24.94 16.46C163.2 368.8 128 314.9 128 260.6C128 126.1 241.2 97.63 384 96V21.84c0-18.8 20.81-28.61 34.02-16.52l150.9 138.2C578.4 152.2 578.4 167.8 568.9 176.5z"
                                        ></path>
                                    </g></svg></span
                            ><span class="DashboardPage__button__copy"
                                >{{ __('results.share') }}</span
                            ></button
                        ><a
                            data-track="mixpanel"
                            data-target="View All careers"
                            data-link-type="Dashboard"
                            class="Button DashboardPage__button DashboardPage__button--share"
                            href="/careers/"
                            data-on-page-nav="false"
                            data-on-page-nav-offset="0"
                            ><span>{{ __('results.View_All_careers') }}</span></a
                        ></span
                    >
                </div>
                <div class="DashboardPage__careers">
                    <div
                        v-for="job in displayedJobs"
                        :key="job.slug"
                        class="HorizontalCard HorizontalCard--wide"
                        :aria-labelledby="`HorizontalCard-${job.slug}`"
                        @click="selectedJob = job"
                    >
                        <img
                            class="HorizontalCard-image"
                            :src="job.image"
                            :alt="job.career"
                            role="presentation"
                            aria-hidden="true"
                        />
                        <div class="HorizontalCard-wrap">
                            <div
                                :id="`HorizontalCard-${job.slug}`"
                                class="HorizontalCard-name"
                            >
                                <Link
                                    :href="`/career/${job.slug}`"
                                    class="HorizontalCard-name-link"
                                    tabindex="0"
                                    aria-label="Click here to view your career details."
                                >
                                    {{ job.name }}
                                </Link>
                            </div>
                        </div>
                        <svg
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fas"
                            data-icon="chevron-right"
                            class="svg-inline--fa fa-chevron-right fa-sm HorizontalCard-arrow"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512"
                        >
                            <path
                                fill="currentColor"
                                d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z"
                            ></path>
                        </svg>
                    </div>
                    <div class="DashboardPage__careers__buttons">
                        <button
                            v-if="!showAllJobs && jobs && jobs.length > displayedJobs.length"
                            class="Button DashboardPage__button DashboardPage__button--careers"
                            data-on-page-nav="false"
                            data-on-page-nav-offset="0"
                            @click="toggleShowMore"
                        >
                            <span class="Button-icon"
                                ><svg
                                    aria-hidden="true"
                                    focusable="false"
                                    data-prefix="fas"
                                    data-icon="chevron-down"
                                    class="svg-inline--fa fa-chevron-down"
                                    role="img"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z"
                                    ></path></svg></span
                            >{{ __("results.See_more_matches") }}
                        </button>

                    </div>
                </div>
            </section>

            <!-- <section class="section"></section> -->
            <section class="section"><DataShare /></section>
            <section class="section"></section>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import { router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import UpNext from "@/Components/UpNext.vue";
import Archetype from "@/Components/Archetype.vue";
import Model from "@/Components/Result/Model.vue";
import Folder from "@/Components/Result/Folder.vue";
import DataShare from "@/Components/Result/DataShare.vue";
import __ from "@/lang";

export default defineComponent({
    components: {
        Archetype,
        UpNext,
        AppLayout,
        Link,
        Model,
        Folder,
        DataShare,
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
        Archetype: {
            type: Object,
            required: true,
        },
        ArchetypeJobs: {
            type: Array,
            required: true,
        },
        userId: {
            type: Number,
            required: true,
        },
        combinedJobs: {
            type: Array,
            required: true,
        },
        archetypeDiscovery: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            isCollapsed: {},
            selectedJob: null,
            showAllJobs: false,
            cards: [
                {
                    id: 9883,
                    title: "Set Designer",
                    link: "/careers/set-designer/reports/#match-insights",
                    imageUrl:
                        "https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_240,q_auto,w_240/v1689805064/bj5p1zcs3farfo74zagh.jpg",
                    rating: 5,
                },
                {
                    id: 9879,
                    title: "Food Stylist",
                    link: "/careers/food-stylist/reports/#match-insights",
                    imageUrl:
                        "https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_240,q_auto,w_240/v1705638257/m7whij0rzsinyuodnete.jpg",
                    rating: 5,
                },
            ],
            showArchetypeModel: false,
        };
    },
    computed: {
        displayedJobs() {
            if (!this.jobs) {
                return [];
            }
            if (this.showAllJobs) {
                return this.jobs;
            }
            return this.jobs.slice(0, Math.ceil(this.jobs.length / 2));
        }
    },
    methods: {
        toggleCollapse(category) {
            this.isCollapsed[category] = !this.isCollapsed[category];
        },
        toggleShowMore() {
            this.showAllJobs = true;
        },
        visitJob(job) {
            const formattedJob = job.replace(/ /g, "-");
            router.visit(`/career/${formattedJob}`, { preserveScroll: false });
        },

        showModel(job) {
            this.selectedJob = job;
        },

        closeModel() {
            this.selectedJob = null;
        },
        openArchetypeModel() {
            this.showArchetypeModel = true;
        },
    },
});
</script>
