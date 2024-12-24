<template>
    <div class="h-screen bg-gradient-to-br from-yellow-100 via-white to-yellow-50 p-8 flex items-center justify-center">
        <div class="w-full max-w-[98rem] aspect-[16/9] rounded-[48px] p-10 shadow-2xl relative overflow-hidden backdrop-blur-lg"
            :style="{
                backgroundPosition: '80% center',
                backgroundSize: 'contain',
                backgroundRepeat: 'no-repeat',
                backgroundColor: 'rgba(255, 255, 255, 0.4)',
                backdropFilter: 'blur(20px)',
            }">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-yellow-100 rounded-full blur-3xl -z-10 opacity-60" />
            <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-yellow-50 rounded-full blur-3xl -z-10 opacity-60" />

            <!-- Header -->
            <header class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-japanese tracking-wide">アニマンガヘブン</h1>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                        <input type="search" placeholder="Search"
                            class="pl-12 pr-6 py-3 rounded-full bg-white/90 w-56 text-base backdrop-blur-md" />
                    </div>
                    <button class="relative">
                        <Bell class="h-6 w-6 text-gray-700" />
                        <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full" />
                    </button>
                    <div class="flex items-center gap-3">
                        <span class="text-base">Hi,liza</span>
                        <img src="/placeholder.svg" alt="Profile"
                            class="w-9 h-9 rounded-full border-2 border-yellow-400" />
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex gap-8 h-[calc(100%-theme(spacing.6))]">
                <!-- Sidebar -->
                <Navbar />

                <!-- Center Content -->
                <div class="flex-1 relative flex flex-col justify-between">
                    <!-- Avatar Section -->
                    <div class="flex-1 flex items-center justify-center relative" style="margin-top: -20rem">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="relative flex items-center gap-8">
                                <!-- Avatar Container -->
                                <div class="w-[400px] h-[400px]" 
                                     @mouseover="showDetails = true"
                                     @mouseleave="showDetails = false">
                                    <ArchetypeAvatar 
                                        :archetype="'Advocat'" 
                                        class="w-full h-full transform scale-110"
                                    />
                                </div>

                                <!-- Details Panel -->
                                <div class="w-[300px] relative overflow-hidden"
                                     :class="{ 'pointer-events-none': !showDetails }">
                                    <div class="space-y-6 transition-all duration-500 ease-out"
                                         :class="[
                                             showDetails ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-20',
                                             'transform'
                                         ]">
                                        <!-- Title -->
                                        <div class="space-y-2">
                                            <h2 class="text-4xl font-bold text-gray-800">{{ archetypeName }}</h2>
                                            <p class="text-xl text-gray-600">{{ archetypeDescription }}</p>
                                        </div>

                                        <!-- Stats -->
                                        <div class="space-y-4">
                                            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4">
                                                <div class="text-sm text-gray-600 mb-1">Personality Traits</div>
                                                <div class="flex gap-2">
                                                    <span v-for="trait in traits" 
                                                          :key="trait"
                                                          class="px-3 py-1 bg-yellow-400/20 rounded-full text-sm">
                                                        {{ trait }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Strengths -->
                                            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4">
                                                <div class="text-sm text-gray-600 mb-2">Key Strengths</div>
                                                <div class="space-y-2">
                                                    <div v-for="(strength, index) in strengths" 
                                                         :key="index"
                                                         class="flex items-center gap-2"
                                                         :style="{
                                                             transitionDelay: `${index * 100}ms`
                                                         }">
                                                        <div class="h-1.5 w-1.5 bg-yellow-400 rounded-full"></div>
                                                        <span>{{ strength }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Cards -->
                    <BottomCards />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Search, Bell, ArrowRight, Home as HomeIcon, Calendar as CalendarIcon, Command as CommandIcon, Bookmark as BookmarkIcon } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Navbar from '@/Components/Navbar.vue'
import BottomCards from '@/Components/BottomCards.vue'
import ArchetypeAvatar from '@/Components/Result/Archetype_Avatar/ArchetypeAvatar.vue'
import './resources/css/testing.css'

const showDetails = ref(false)
const archetypeName = ref('Advocat')
const archetypeDescription = ref('The Strategic Thinker')

// Add new reactive data
const traits = ref(['Analytical', 'Strategic', 'Determined'])
const strengths = ref([
    'Innovative Problem Solving',
    'Strategic Planning',
    'Logical Analysis',
    'System Design'
])
</script>

<style scoped>
.font-japanese {
    font-family: "Noto Sans JP", sans-serif;
}

.font-display {
    font-family: "Montserrat", sans-serif;
}

/* Add any additional styling needed for the avatar container */

/* Add smooth transitions for strength items */
.space-y-2 > div {
    transition: all 0.5s ease-out;
    transition-property: transform, opacity;
}

/* When details are hidden, cascade the strength items out */
.opacity-0 .space-y-2 > div {
    opacity: 0;
    transform: translateX(20px);
}

/* When details are shown, cascade the strength items in */
.opacity-100 .space-y-2 > div {
    opacity: 1;
    transform: translateX(0);
}
</style>
