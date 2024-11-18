<template>
    <AppLayout :name-exist="true" :it-is-visible="true">
        <div class="min-h-screen">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <!-- Welcome Banner -->
                <div class="bg-stone-950 rounded-2xl p-8 mb-4 border border-stone-700">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold text-stone-100">
                                {{ __('dashboard.welcome') }} {{ $page.props.auth.user.name }}!
                            </h1>
                            <p class="mt-2 text-lg text-stone-400">
                                {{ __('dashboard.orientation_process') }}
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <Link v-if="!hasResult"
                                href="/main-test"
                                class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-stone-900 font-medium rounded-lg transition-colors duration-200"
                            >
                                {{ __('dashboard.begin_assessment') }}
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <!-- Liked Jobs -->
                        <div class="bg-stone-950 rounded-2xl p-6 border border-stone-700">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_jobs') }}</h2>
                                <span class="px-4 py-2 bg-stone-700 rounded-full text-sm text-stone-300">
                                    {{ favoriteJobs.length }}
                                </span>
                            </div>
                            <div v-if="favoriteJobs.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
                                <div v-for="job in favoriteJobs" :key="job.id"
                                     class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-4 transition-all duration-300">
                                    <Link :href="`/career/${job.slug}`" class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-stone-100">{{ job.name }}</h3>
                                            <p class="text-sm text-stone-400">Career Path</p>
                                        </div>
                                        <button @click.prevent="removeFavorite(job.id)"
                                                class="opacity-0 group-hover:opacity-100 p-2 hover:bg-stone-600 rounded-full transition-all">
                                            <svg class="w-5 h-5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center py-8 text-center">
                                <svg class="w-16 h-16 text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <p class="text-stone-400 mb-2">{{ __('dashboard.no_liked_jobs') }}</p>
                                <Link href="/jobs" class="text-amber-500 hover:text-amber-400 text-sm">
                                    {{ __('dashboard.discover_jobs') }} →
                                </Link>
                            </div>
                        </div>

                        <!-- Liked Formations -->
                        <div class="bg-stone-950 rounded-2xl p-6 border border-stone-700">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.liked_formations') }}</h2>
                                <span class="px-4 py-2 bg-stone-700 rounded-full text-sm text-stone-300">
                                    {{ favoriteDegrees.length }}
                                </span>
                            </div>
                            <div v-if="favoriteDegrees.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
                                <div v-for="degree in favoriteDegrees" :key="degree.id"
                                     class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-4 transition-all duration-300">
                                    <Link :href="`/degree/${degree.slug}`" class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-stone-100">{{ degree.name }}</h3>
                                            <p class="text-sm text-stone-400">Education Path</p>
                                        </div>
                                        <button @click.prevent="removeFavorite(degree.id)"
                                                class="opacity-0 group-hover:opacity-100 p-2 hover:bg-stone-600 rounded-full transition-all">
                                            <svg class="w-5 h-5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center py-8 text-center">
                                <svg class="w-16 h-16 text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21l9-5-9-5-9 5 9 5z" />
                                </svg>
                                <p class="text-stone-400 mb-2">{{ __('dashboard.no_liked_formations') }}</p>
                                <Link href="/degrees" class="text-amber-500 hover:text-amber-400 text-sm">
                                    {{ __('dashboard.discover_formations') }} →
                                </Link>
                            </div>
                        </div>
                    </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Assessment Results Card -->
                    <div v-if="hasResult" class="lg:col-span-2 bg-stone-950 rounded-2xl p-6 border border-stone-700">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-stone-100">{{ __('dashboard.your_assessment_results') }}</h2>
                            <Link
                                href="/results"
                                class="px-3 py-1.5 text-sm bg-white hover:bg-stone-300/30 hover:text-white text-stone-900 rounded-lg transition-colors duration-200 w-auto text-center"
                            >
                                {{ __('dashboard.view_full_results') }}
                            </Link>
                        </div>

                        <!-- Archetype Card -->
                        <div class="bg-[#353535] rounded-2xl p-8 mb-6 shadow-2xl hover:shadow-amber-500/5 transition-all duration-500 border border-stone-700/50 relative overflow-hidden" 
                             style="background-image: url('https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607545512/dashboard-sharing-bg-lg_ngkdfq.png'); background-size: cover; background-position: center;">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                            <div class="absolute bottom-0 left-0 w-64 h-64 bg-stone-600/5 rounded-full blur-3xl -ml-32 -mb-32"></div>
                            
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative">
                                <div class="flex items-center gap-4">
    
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-4xl font-bold bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent tracking-tight hover:scale-105 transition-transform duration-300">
                                                {{ archetype.slug }}
                                            </h3>
                                            <span class="px-3 py-1 text-xs font-semibold text-amber-400 bg-amber-400/10 rounded-full border border-amber-400/20">
                                                {{ archetype.rarity_string }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-stone-400 text-sm font-medium">Personality Type</span>
                                        </div>
                                        <p class="text-stone-300 font-bold leading-relaxed tracking-wide">
                                            {{ archetype.rationale }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6">
                            <div v-for="(scale, index) in JSON.parse(archetype.scales)" :key="scale.scale_id"
                                 class="bg-black rounded-xl p-6 transition-all duration-300 border border-stone-700 hover:border-amber-500/30 group">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-stone-100 tracking-wide">
                                        {{ scale.scale_name }}
                                    </h4>
                                    <span class="text-xl font-bold text-amber-400 bg-amber-400/10 px-3 py-1 rounded-lg">
                                        {{ Math.round(scale.percentile_score) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-stone-800 rounded-full h-5 mb-4 overflow-hidden">
                                    <div class="bg-amber-500 h-5 rounded-full transition-all duration-700 ease-out"
                                         :style="{ width: `${Math.round(scale.percentile_score)}%` }">
                                    </div>
                                </div>
                                <div @click="toggleDescription(scale.scale_id)" 
                                     class="cursor-pointer group-hover:bg-stone-800/50 rounded-lg p-3 transition-colors">
                                    <p class="text-sm leading-relaxed text-stone-300 font-medium tracking-wide group-hover:text-stone-200 transition-colors"
                                       :class="{ 'line-clamp-2': !expandedDescriptions[scale.scale_id] }">
                                        {{ scale.single_sentence_description }}
                                    </p>
                                    <span class="text-amber-400 text-sm mt-2 hover:text-amber-300" 
                                          v-if="scale.single_sentence_description.length > 100">
                                        {{ expandedDescriptions[scale.scale_id] ? '▲ Show less' : '▼ Read more' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Traits Grid -->
                    
                    </div>

                    <!-- AI Assistant Card -->
                    <div class="bg-stone-950 rounded-2xl border border-stone-700 flex flex-col h-[600px]">
                        <div class="p-6 border-b border-stone-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></div>
                                <h2 class="text-xl font-semibold text-stone-100">{{ __('dashboard.orientation_ai_assistant') }}</h2>
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                            <div class="space-y-4">
                                <div class="bg-black rounded-xl p-4">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                        </div>
                                        <span class="text-stone-300 font-medium">AI Assistant</span>
                                    </div>
                                    <p class="text-sm text-stone-300 leading-relaxed animate-fade-in">{{ displayedText }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import {Link, router} from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import __ from '@/lang';
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
    },
    archetype: {
        type: Object,
    },
    topJobs: {
        type: Array,
        default: () => []
    }
});


const expandedDescriptions = ref({});

const toggleDescription = (scaleId) => {
    expandedDescriptions.value[scaleId] = !expandedDescriptions.value[scaleId];
};

const fullText = `Hello! I'm your AI Career Guide 

Need guidance? Just ask me about:
- Career recommendations
- Educational pathways
- Skill development
- Industry insights`;

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
    }, 50);
};

onMounted(() => {
    typingEffect(fullText);
});

const removeFavorite = (id) => {
    router.delete(`/favorites/${id}`);
};
</script>

<style scoped>
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(199, 210, 254, 0.3) rgba(67, 56, 202, 0.3);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(67, 56, 202, 0.3);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(199, 210, 254, 0.3);
    border-radius: 4px;
    border: 2px solid rgba(67, 56, 202, 0.3);
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-in-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
