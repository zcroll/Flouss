<template>
    <div class="container mx-auto p-4   flex flex-col-reverse lg:flex-row">
        <!-- Main Content Section -->
        <div class="w-full lg:w-2/3">
            <slot/>
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 mb-8 lg:mb-0 lg:pl-8">
            <div
                class="sticky top-4 bg-white shadow-2xl rounded-3xl p-6 pt-[-30px]">
<!--                <h3 class="text-xl font-light mb-6 text-gray-800 fancy-font">{{ sidebarTitle }}</h3>-->
<!--                <p class="text-sm text-gray-600 mb-4 fancy-font">{{ sidebarDescription }}</p>-->

                <!-- Main Content for Large Screens -->
                <div v-if="!isSmallScreen" class="rounded-3xl">
                    <div class="grid grid-cols-3 gap-4 items-center mb-10">
                        <div
                            class="col-span-1 w-20 h-20 bg-gray-200 rounded-2xl flex items-center justify-center justify-self-center">
                            <img src="{{image}}" alt="Icon" class="w-6 h-6">
                        </div>
                        <h3 class="col-span-2 text-lg font-light font-serif text-gray-700 fancy-font">
                            {{ title }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-purple-500 text-white h-3/4 rounded-lg text-center flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold fancy-font">Salary</h4>
                            <p class="text-sm fancy-font">$67K</p>
                        </div>
                        <div
                            class="bg-teal-600 text-white h-3/4 rounded-lg text-center flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold fancy-font">personality</h4>
                            <p class="text-sm fancy-font">N/A</p>

                        </div>
                        <div
                            class="bg-gray-800 text-white h-3/4 rounded-lg text-center flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold fancy-font">Tasks</h4>
                            <p class="text-sm fancy-font">Very Low</p>

                        </div>
                    </div>
                    <ul class="space-y-4 text-gray-700">
                        <li>
                            <Link
                                :href="`${baseUrl}`"
                                :class="{
                  'active': $page.url === `${baseUrl}`,
                  'disabled-link': $page.url === `${baseUrl}`
                }"
                                :aria-disabled="$page.url === `${baseUrl}`"
                            >
                                <span class="font-serif text-2xl">Overview</span>
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/workEnvironments`"
                                :class="{
                  'active': $page.url === `${baseUrl}/workEnvironments`,
                  'disabled-link': $page.url === `${baseUrl}/workEnvironments`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/workEnvironments`"
                            >
                                <span class="font-serif text-2xl">workEnvironments</span>
                            </Link>
                        </li>

                        <li>
                            <Link
                                :href="`${baseUrl}/personality`"
                                :class="{
                  'active': $page.url === `${baseUrl}/personality`,
                  'disabled-link': $page.url === `${baseUrl}/personality`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/personality`"
                            >
                                <span
                                    class="font-serif text-2xl">personality</span>

                            </Link>
                        </li>

                    </ul>
                </div><!-- Horizontal Scroller for Small Screens -->
                <div v-else class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <img src="/icon.png" alt="Icon" class="w-6 h-6">
                        </div>
                        <h3 class="ml-4 text-base font-light text-gray-700 text-center fancy-font">{{
                                title
                            }}</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                        <div
                            class="bg-purple-500 text-white p-4 rounded-lg">
                            <p class="text-sm fancy-font">Avg knowledge</p>
                            <h4 class="text-base font-bold fancy-font">$67K</h4>
                        </div>
                        <div
                            class="bg-teal-600 text-white p-4 rounded-lg">
                            <p class="text-sm fancy-font">personality</p>
                            <h4 class="text-base font-bold fancy-font">N/A</h4>
                        </div>
                    </div>
                    <ul class="flex overflow-x-auto space-x-4 text-gray-700 justify-center">
                        <li>
                            <Link
                                :href="`${baseUrl}/workEnvironments`"
                                :class="{
                  'active': $page.url === `${baseUrl}/workEnvironments`,
                  'disabled-link': $page.url === `${baseUrl}/workEnvironments`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/workEnvironments`"
                            >
                                workEnvironments
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/work-activities`"
                                :class="{
                  'active': $page.url === `${baseUrl}/work-activities`,
                  'disabled-link': $page.url === `${baseUrl}/work-activities`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/work-activities`"
                            >
                                work-activities
                            </Link>
                        </li>

                            <li>
                            <Link
                                :href="`${baseUrl}/personality`"
                                :class="{
                  'active': $page.url === `${baseUrl}/personality`,
                  'disabled-link': $page.url === `${baseUrl}/personality`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/personality`"
                            >
                                personality
                            </Link>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3';

export default {
    components: {
        Link
    },
    props: {
        sidebarTitle: String,
        sidebarDescription: String,
        title: String,
    },
    computed: {
        isSmallScreen() {
            return window.innerWidth < 1024;
        },
        baseUrl() {
            return `/career/${this.title.split(' ').join('-')}`;
        }
    }
};
</script>

<style scoped>
.fancy-font {
    font-family: 'Great Vibes', cursive; /* Example of a fancy font */
}

.stickySidebar {
    position: sticky;
    top: -44rem;
}

.active {
    color: #4A4A4A; /* Adjust the active color as needed */
    font-weight: bold;
}

.disabled-link {
    color: #D1D5DB; /* Tailwind's gray-300 color for less visibility */
    cursor: not-allowed;
}
</style>
