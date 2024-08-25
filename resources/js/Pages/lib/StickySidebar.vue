<template>
    <div class="container mx-auto p-4 flex flex-col-reverse lg:flex-row">
        <!-- Main Content Section -->
        <div class="w-full lg:w-2/3">
            <slot/>
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 mb-8 lg:mb-0 lg:pl-8">
            <div
                class="sticky top-9 bg-white shadow-2xl rounded-3xl p-6 transform transition-all duration-300 hover:scale-105">
                <h3 class="text-2xl font-bold mb-6 text-gray-800">{{ sidebarTitle }}</h3>
                <p class="text-md text-gray-600 mb-4">{{ sidebarDescription }}</p>

                <!-- Main Content for Large Screens -->
                <div v-if="!isSmallScreen" class=" rounded-3xl animate-slide-in"><div class="grid grid-cols-3 gap-4 items-center mb-10">
                    <div class="col-span-1 w-20 h-20 bg-gray-200 rounded-2xl flex items-center justify-center justify-self-center">
                        <img src="/icon.png" alt="Icon" class="w-6 h-6">
                    </div>
                    <h3 class="col-span-2 text-xl font-bold font-serif text-gray-700 animate-fade-in">
                        {{ title }}
                    </h3>
                </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-purple-500 text-white h-3/4 rounded-lg text-center hover:scale-105 transition-transform duration-300 flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold">Salary</h4>
                            <p class="text-sm">$67K</p>
                        </div>
                        <div
                            class="bg-teal-600 text-white h-3/4 rounded-lg text-center hover:scale-105 transition-transform duration-300 flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold">Technologies</h4>
                            <p class="text-sm">N/A</p>

                        </div>
                        <div
                            class="bg-gray-800 text-white h-3/4 rounded-lg text-center hover:scale-105 transition-transform duration-300 flex flex-col justify-center items-center p-4">
                            <h4 class="text-l font-bold">Tasks</h4>
                            <p class="text-sm">Very Low</p>

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
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300">Overview</span>
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/abilities`"
                                :class="{
                  'active': $page.url === `${baseUrl}/abilities`,
                  'disabled-link': $page.url === `${baseUrl}/abilities`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/abilities`"
                            >
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300">Abilities</span>
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
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300">work-activities</span>
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/knowledge`"
                                :class="{
                  'active': $page.url === `${baseUrl}/knowledge`,
                  'disabled-link': $page.url === `${baseUrl}/knowledge`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/knowledge`"
                            >
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300">knowledge</span>
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/technologies`"
                                :class="{
                  'active': $page.url === `${baseUrl}/technologies`,
                  'disabled-link': $page.url === `${baseUrl}/technologies`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/technologies`"
                            >
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300 ">Technologies</span>

                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/tasks`"
                                :class="{
                  'active': $page.url === `${baseUrl}/tasks`,
                  'disabled-link': $page.url === `${baseUrl}/tasks`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/tasks`"
                            >
                                <span class="font-serif text-3xl hover:p-5 transition-all duration-300">tasks</span>
                            </Link>
                        </li>
                    </ul>
                </div><!-- Horizontal Scroller for Small Screens -->
                <div v-else class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <img src="/icon.png" alt="Icon" class="w-6 h-6">
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-700 animate-fade-in text-center">{{
                                title
                            }}</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                        <div
                            class="bg-purple-500 text-white p-4 rounded-lg hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Avg knowledge</p>
                            <h4 class="text-xl font-bold">$67K</h4>
                        </div>
                        <div
                            class="bg-teal-600 text-white p-4 rounded-lg hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">technologies</p>
                            <h4 class="text-xl font-bold">N/A</h4>
                        </div>
                    </div>
                    <ul class="flex overflow-x-auto space-x-4 text-gray-700 justify-center">
                        <li>
                            <Link
                                :href="`${baseUrl}/abilities`"
                                :class="{
                  'active': $page.url === `${baseUrl}/abilities`,
                  'disabled-link': $page.url === `${baseUrl}/abilities`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/abilities`"
                            >
                                Abilities
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
                                :href="`${baseUrl}/knowledge`"
                                :class="{
                  'active': $page.url === `${baseUrl}/knowledge`,
                  'disabled-link': $page.url === `${baseUrl}/knowledge`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/knowledge`"
                            >
                                knowledge
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/technologies`"
                                :class="{
                  'active': $page.url === `${baseUrl}/technologies`,
                  'disabled-link': $page.url === `${baseUrl}/technologies`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/technologies`"
                            >
                                technologies
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="`${baseUrl}/tasks`"
                                :class="{
                  'active': $page.url === `${baseUrl}/tasks`,
                  'disabled-link': $page.url === `${baseUrl}/tasks`
                }"
                                :aria-disabled="$page.url === `${baseUrl}/tasks`"
                            >
                                tasks
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
.stickySidebar {
    position: sticky;
    top: 4rem;
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
