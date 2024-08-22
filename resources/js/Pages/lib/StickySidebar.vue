<template>
    <div class="container mx-auto p-4 flex flex-col-reverse lg:flex-row">
        <!-- Main Content Section -->
        <div class="w-full lg:w-2/3">
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
                            <img src="icon.png" alt="Icon" class="w-6 h-6">
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
                    <ul class="space-y-2 text-gray-700">
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Your Compatibility</li>
                        <li class="font-bold text-black cursor-pointer hover:animate-pulse">Overview</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Abilities</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">How to Become</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Knowledge</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Personality</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Technologies</li>
                        <li class="hover:text-purple-600 cursor-pointer hover:animate-pulse">Work Environment</li>
                    </ul>
                </div>

                <!-- Horizontal Scroller for Small Screens -->
                <div v-else class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <img src="icon.png" alt="Icon" class="w-6 h-6">
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
                            <p :class="[item.text === 'Overview' ? 'font-bold text-black' : 'hover:text-purple-600 cursor-pointer hover:animate-pulse']">
                                {{ item.text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sidebarTitle: {
            type: String,
            required: true
        },
        sidebarDescription: {
            type: String,
            required: true
        }
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
    }
};
</script>

<style scoped>
/* Minimum width to ensure the card is correctly sized in the scroller */
.min-w-card {
    min-width: 150px;
}

@keyframes slideIn {
    from {
        transform: translateX(30px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slideIn 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

@keyframes hoverEffect {
    from {
        transform: scale(1);
    }
    to {
        transform: scale(1.05);
    }
}

.hover\:transform {
    transition: transform 0.2s;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 12px 48px 0 rgba(0, 0, 0, 0.25);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-in-out;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease-in-out;
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.25);
    transition: box-shadow 0.3s ease-in-out;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25%, 75% {
        transform: translateX(-10px);
    }
    50% {
        transform: translateX(10px);
    }
}

.hover\:animate-shake:hover {
    animation: shake 0.5s;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.7;
    }
}

.hover\:animate-pulse:hover {
    animation: pulse 1s ;
}

@keyframes hoverEffect {
    from {
        transform: scale(1);
    }
    to {
        transform: scale(1.05);
    }
}

.hover\:transform {
    transition: transform 0.2s;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}
</style>
