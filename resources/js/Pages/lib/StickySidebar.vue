<template>
    <div class="container mx-auto p-4 flex flex-col-reverse lg:flex-row">
        <div class="w-full lg:w-2/3">
            <slot/>
        </div>

        <!-- Sticky Sidebar -->
        <div class="w-full lg:w-1/3 mb-8 lg:mb-0 lg:pl-8">
            <div class="sticky top-4 text-white shadow-2xl rounded-3xl p-6 overflow-hidden"
                 :class="[
                    $page.url === baseUrl ? 'bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500' :
                    $page.url === `${baseUrl}/workEnvironments` ? 'bg-gradient-to-br from-green-500 via-teal-500 to-blue-500' :
                    $page.url === `${baseUrl}/personality` ? 'bg-gradient-to-br from-red-500 via-orange-500 to-yellow-500' :
                    'bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500'
                 ]">
                <div class="relative z-10">
                    <!-- Main Content for Large Screens -->
                    <div v-if="!isSmallScreen" class="rounded-3xl">
                        <div class="flex items-center mb-10">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mr-4">
                                <img :src="image" alt="Icon" class="w-16 h-16 rounded-full">
                            </div>
                            <h3 class="text-2xl font-bold text-white">
                                {{ title }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg">
                                <h4 class="text-lg font-bold">Salary</h4>
                                <p class="text-sm">N/A</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg">
                                <h4 class="text-lg font-bold">Personality</h4>
                                <p class="text-sm">N/A</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg text-center p-4 backdrop-filter backdrop-blur-lg">
                                <h4 class="text-lg font-bold">Satisfaction</h4>
                                <p class="text-sm">Very Low</p>
                            </div>
                        </div>
                        <ul class="space-y-4">
                            <li v-for="(link, index) in links" :key="index">
                                <Link
                                    :href="link.url"
                                    :class="{
                                        'active': $page.url === link.url,
                                        'disabled-link': $page.url === link.url
                                    }"
                                    :aria-disabled="$page.url === link.url"
                                    class="block py-2 px-4 rounded-lg transition-colors duration-200 hover:bg-white hover:bg-opacity-20"
                                >
                                    <span class="text-xl font-semibold">{{ link.text }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <!-- Centered Content for Small Screens -->
                    <div v-else class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4">
                            <img :src="image" alt="Icon" class="w-16 h-16 rounded-full">
                        </div>
                        <h3 class="text-xl font-bold mb-6">{{ title }}</h3>
                        <div class="grid grid-cols-3 gap-4 mb-6 w-full">
                      
                        </div>
                        <div class="flex flex-wrap justify-center gap-2">
                            <Link
                                v-for="(link, index) in links"
                                :key="index"
                                :href="link.url"
                                :class="{
                                    'active': $page.url === link.url,
                                    'disabled-link': $page.url === link.url
                                }"
                                :aria-disabled="$page.url === link.url"
                                class="py-2 px-4 bg-white bg-opacity-20 rounded-lg text-center transition-colors duration-200 hover:bg-opacity-30"
                            >
                                <span class="text-sm font-semibold">{{ link.text }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Decorative background elements -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full transform translate-x-1/2 translate-y-1/2"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    components: {
        Link
    },
    props: {
        sidebarTitle: String,
        sidebarDescription: String,
        title: String,
        image: String,
    },
    setup(props) {
        const isSmallScreen = ref(false);

        const updateScreenSize = () => {
            isSmallScreen.value = window.innerWidth < 1024;
        };

        // Call once to set initial value
        updateScreenSize();

        // Add event listener for resize
        window.addEventListener('resize', updateScreenSize);

        const baseUrl = computed(() => `/career/${props.title.split(' ').join('-')}`);

        const links = [
            { text: 'Overview', url: baseUrl.value },
            { text: 'Work Environments', url: `${baseUrl.value}/workEnvironments` },
            { text: 'Personality', url: `${baseUrl.value}/personality` },
        ];

        return {
            isSmallScreen,
            baseUrl,
            links
        };
    }
};
</script>

<style scoped>
.active {
    @apply bg-white bg-opacity-30 font-bold;
}

.disabled-link {
    @apply opacity-50 cursor-not-allowed;
}
</style>
