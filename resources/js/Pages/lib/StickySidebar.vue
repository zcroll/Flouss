<template>
    <div class="container mx-auto p-4 flex flex-col-reverse lg:flex-row">
        <!-- Main Content Section -->
        <div class="w-full lg:w-2/3">
<!--            <slot/>-->
            <slot/>

        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 mb-8 lg:mb-0 lg:pl-8">
            <div class="sticky top-4 bg-white shadow-xl rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6 text-gray-800">{{ sidebarTitle }}</h3>
                <p class="text-md text-gray-600 mb-4">{{ sidebarDescription }}</p>

                <!-- Main Content for Large Screens -->
                <div v-if="!isSmallScreen" class="bg-gray-50 p-6 rounded-lg shadow-sm animate-slide-in">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <img src="/icon.png" alt="Icon" class="w-6 h-6">
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-700 animate-fade-in">{{ title }}</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-purple-500 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Avg Salary</p>
                            <h4 class="text-xl font-bold">$67K</h4>
                        </div>
                        <div
                            class="bg-teal-600 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Growth</p>
                            <h4 class="text-xl font-bold">N/A</h4>
                        </div>
                        <div
                            class="bg-gray-800 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Satisfaction</p>
                            <h4 class="text-xl font-bold">Very Low</h4>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-700">
                        <li v-for="item in items" :key="item.text">
                            <Link :href="`${baseUri}/${item.text.toLowerCase().replace(/\s+/g, '-')}`"
                                  :class="{'hover:text-purple-600 cursor-pointer hover:animate-pulse': `${baseUri}/${item.text.toLowerCase().replace(/\s+/g, '-')}` !== baseUri, 'text-gray-500 cursor-not-allowed': `${baseUri}/${item.text.toLowerCase().replace(/\s+/g, '-')}` === baseUri}"
                                  :aria-disabled="`${baseUri}/${item.text.toLowerCase().replace(/\s+/g, '-')}` === baseUri">
                                {{ item.text }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Horizontal Scroller for Small Screens -->
                <div v-else class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <img src="/icon.png" alt="Icon" class="w-6 h-6">
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-700 animate-fade-in">Account Manager</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-purple-500 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Avg Salary</p>
                            <h4 class="text-xl font-bold">$67K</h4>
                        </div>
                        <div
                            class="bg-teal-600 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Growth</p>
                            <h4 class="text-xl font-bold">N/A</h4>
                        </div>
                        <div
                            class="bg-gray-800 text-white p-4 rounded-lg text-center hover:scale-105 transition-transform duration-300">
                            <p class="text-sm">Satisfaction</p>
                            <h4 class="text-xl font-bold">Very Low</h4>
                        </div>
                    </div>

                    <!-- Turn List Items into Cards on Small Screens -->
                    <div class="flex overflow-x-auto space-x-4">
                        <div v-for="item in items" :key="item.text"
                             class="flex-shrink-0 bg-white p-4 rounded-lg shadow-md text-center min-w-card hover:scale-105 transition-transform duration-300">
                            <Link :href="`${baseUri}/${item.text.toLowerCase().replace(/\s+/g, '-')}`">
                                <p :class="[item.text === 'Overview' ? 'font-bold text-black' : 'hover:text-purple-600 cursor-pointer hover:animate-pulse']">
                                    {{ item.text }}
                                </p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link, usePage} from '@inertiajs/vue3';
import {computed} from "vue";



const page = usePage()

const baseUri = computed(() => page.props.baseUri)

export default {
    components: {
        Link
    },
    props: {
        sidebarTitle: {
            type: String,
            required: true
        },
        sidebarDescription: {
            type: String,
            required: true
        },

    },
    data() {
        return {
            isSmallScreen: false,
            items: [
                {text: 'Your Compatibility'},
                {text: 'Overview'},
                {text: 'Abilities'},
                {text: 'How to Become'},
                {text: 'Knowledge'},
                {text: 'Personality'},
                {text: 'Technologies'},
                {text: 'Work Environment'},

            ],
            baseUri: baseUri,
        };
    },
    mounted() {
        this.checkScreenSize();
        window.addEventListener('resize', this.checkScreenSize);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.checkScreenSize);
    },
    methods: {
        checkScreenSize() {
            this.isSmallScreen = window.innerWidth < 1024;
        }
    },
};
</script>
