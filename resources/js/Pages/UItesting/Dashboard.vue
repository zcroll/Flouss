<template>
    <Head title="Dashboard" />
    
    <!-- Avatar Section -->
    <div class="flex-1 flex items-center justify-center relative" style="margin-top: -10rem">
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
    <BottomCards :favorite-jobs="favoriteJobs" />
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import ArchetypeAvatar from '@/Components/Result/Archetype_Avatar/ArchetypeAvatar.vue'
import BottomCards from '@/Components/BottomCards.vue'

defineOptions({
    layout: MainLayout,
})

// Get favoriteJobs from page props
const props = defineProps({
  favoriteJobs: {
    type: Array,
    required: true
  }
})

const showDetails = ref(false)
const archetypeName = ref('Advocat')
const archetypeDescription = ref('The Strategic Thinker')
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
