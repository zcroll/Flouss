<template>
    <AppLayout>
        <div class="layout--sidebar__body__main">



        <StickySidebar
            :slug="occupation.slug"
            :title="occupation.name"
            :image="occupation.image"
            type="career"
            :salary="occupation.salary"
            :personality="occupation.personality || 'N/A'"
            :satisfaction="occupation.satisfaction || 'N/A'"
            :id="occupation.id"
            :isFavorited="occupation.is_favorited"
        >

            <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow-2xl">
              <nav class="flex items-center space-x-2 text-sm mb-8 font-['aktiv-grotesk','Helvetica_Neue',Helvetica,Arial,sans-serif]">
                <Link :href="route('dashboard')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Home') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('jobs.index')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Jobs') }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <Link :href="route('career', { id: occupation.slug })" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ occupation.name }}</Link>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-400 font-medium border-b-2 border-gray-400 transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Work Environments') }}</span>
            </nav>

            <Deferred data="workEnvironments">
                <template #fallback>
                    <div class="animate-pulse space-y-8">
                        <!-- Table of Contents Skeleton -->
                        <div class="rounded-lg my-4 w-full bg-[#f2e1d5] p-9">
                            <div class="h-8 bg-gray-200 rounded w-1/4 mb-6"></div>
                            <div class="space-y-6">
                                <div v-for="n in 3" :key="n" class="space-y-4">
                                    <div class="h-6 bg-gray-200 rounded w-1/3"></div>
                                    <div class="pl-4 space-y-3">
                                        <div v-for="i in 3" :key="i" class="h-4 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Environments Skeleton -->
                        <div class="space-y-8">
                            <div v-for="n in 4" :key="n" class="space-y-4">
                                <div class="h-6 bg-gray-200 rounded w-2/3"></div>
                                <div class="h-24 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded-full w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #default>
                    <h2 class="custom-heading">{{ __('career.typical_work_environments') }}</h2>

                    <aside id="table-of-contents-container" class="block">
                        <div
                            id="table-of-contents"
                            class="table-of-contents rounded-lg my-4 w-full bg-[#f2e1d5] text-gray-900 relative p-9"
                            role="directory"
                            tabindex="0"
                            title="Table of contents"
                        >
                            <p class="custom-heading">
                                {{ __('career.in_this_article') }}
                            </p>
                            <ol v-for="(items, category, index) in groupedByCategory" :key="category" class="category-block">
                                <!-- Category Title with Toggle -->
                                <li
                                    class="cursor-pointer trait-type text-xl"
                                    @click="toggleSection(index)"
                                >
                                    {{ category }} {{ openSections[index] ? '_' : '+' }}
                                </li>

                                <!-- Collapsible Content -->
                                <transition name="slide">
                                    <ol v-show="openSections[index]" class="list-disc m-0 p-0 text-base leading-6 font-light tracking-tight">
                                        <li
                                            v-for="item in items"
                                            :key="item.id"
                                            class="relative mb-0 text-xl leading-10"
                                        >
                                            <a
                                                :href="`#section-${item.id}`"
                                                class="hover:underline block"
                                                @click.prevent="highlightAndScroll(item.id)"
                                            >
                                                {{ item.name }}
                                            </a>
                                        </li>
                                    </ol>
                                </transition>
                            </ol>
                        </div>
                    </aside>

                    <!-- Work Environment Section -->
                    <div>
                        <div v-for="environment in workEnvironments" :key="environment.id" class="Box block py-4 bg-transparent border-b border-white/18">
                            <div :id="`section-${environment.id}`" class="text-gray-700 text-xl font-black leading-[25px] pr-5 rounded-xl">
                                {{ environment.name }}
                            </div>
                            <p class="text-gray-700 mt-5 mb-5">{{ environment.description }}</p>
                            <figure v-if="environment.score" class="relative">
                                <span class="block h-[34px] rounded-full bg-gradient-to-r" :style="getScoreStyle(environment.score)"></span>
                            </figure>
                        </div>
                    </div>
                </template>
            </Deferred>

            <BackToTop />
        </div>
        </StickySidebar>
    </div>
    </AppLayout>
</template>



<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Deferred } from '@inertiajs/vue3';
import BackToTop from "@/Components/BackToTop.vue";

const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    workEnvironments: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const openSections = ref([]);

const groupedByCategory = computed(() => {
    if (!props.workEnvironments) return {};
    
    return props.workEnvironments.reduce((acc, item) => {
        const category = item.category || 'Other';
        if (!acc[category]) {
            acc[category] = [];
        }
        acc[category].push(item);
        return acc;
    }, {});
});

const toggleSection = (index) => {
    openSections.value[index] = !openSections.value[index];
};

const highlightAndScroll = (id) => {
    // Remove highlight from all sections
    document.querySelectorAll('.Box').forEach(box => {
        box.classList.remove('highlight');
    });

    // Add highlight to the target section
    const element = document.getElementById(`section-${id}`);
    if (element) {
        const parentBox = element.closest('.Box');
        if (parentBox) {
            parentBox.classList.add('highlight');
        }
        // Smooth scroll to the element
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

const getScoreStyle = (score) => {
    return {
        width: `${score < 15 ? 11 : score}%`,
    };
};
</script>





<style scoped>
h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

h2 {
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
}

p {
    line-height: 1.6;
}

.text-gray-900 {
    color: #1a202c;
}

.text-gray-700 {
    color: #4a5568;
}

.Box {
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
}

.grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 2fr;
    gap: 1rem;
}

.bg-gradient-to-r {
    background: linear-gradient(to right, #0D7C66, #41B3A2, #C8A1E0);
}

.shadow-md {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.text-gray-200 {
    color: #e2e8f0;
}

.mt-2 {
    margin-top: 0.5rem;
}

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

.trait-type {
    background-color: transparent;
    text-decoration: none;
    transition:
        color 0.2s ease-in-out,
        border-bottom 0.2s ease-in-out;
    border-bottom: 0px;
    color: rgb(36, 36, 36);
    font-weight: 300;
    font-family:
        aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}



h3.category-title {
    cursor: pointer;
}

.slide-enter-active, .slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from, .slide-leave-to {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}



.glow-text {
    text-shadow: 0 0 10px rgba(200, 161, 224, 0.8),
    0 0 20px rgba(200, 161, 224, 0.8),
    0 0 30px rgba(200, 161, 224, 0.8);

}
.highlight {
    background-color: #f7f7f7;
    border-radius: 0.5rem;
    padding: 1rem;
}
</style>
