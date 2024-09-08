<template>
    <AppLayout>
        <StickySidebar :slug="occupation.slug" :title="occupation.name" :image="occupation.image">
            <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
                <h2 class="custom-heading">Typical Work Environments:</h2>


                <aside id="table-of-contents-container" class="block">
                    <div
                        id="table-of-contents"
                        class="table-of-contents rounded-lg my-4 w-full bg-[#f2e1d5] text-gray-900 relative p-9"
                        role="directory"
                        tabindex="0"
                        title="Table of contents"
                    >
                        <p class="custom-heading">
                            In this article:
                        </p>
                        <ol v-for="(items, category, index) in groupedByCategory" :key="category" class="category-block">
                            <!-- Category Title with Toggle -->
                            <li
                                class="cursor-pointer trait-type text-xl"
                                @click="toggleSection(index)"
                            >
                                {{ category }} ...
                            </li>

                            <!-- Collapsible Content -->
                            <transition name="slide">
                                <ol v-show="openSections[index]" class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight">
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


                    <div v-for="environment in workEnvironments" :key="environment.id"  class="Box block py-4 bg-transparent border-b border-white/18">
                        <div :id="`section-${environment.id}`" class="text-gray-700 text-xl font-black leading-[25px] pr-5 rounded-xl ">
                            {{ environment.name }}
                        </div>

                        <p class="text-gray-700 mt-5 mb-5">{{ environment.description }}</p>
                        <figure v-if="environment.score" class="relative">
                            <span
                                class="block h-[34px] rounded-full bg-gradient-to-r from-red-400 to-orange-600 shadow-md"
                                :style="{ width: `${environment.score < 15 ? 11 : environment.score}%` }"
                            >
                                <span class="text-gray-200 font-medium text-lg leading-[32px] ml-2 absolute top-1/2 transform -translate-y-1/2">
                                    {{ environment.score }}%
                                </span>
                            </span>
                        </figure>
                    </div>
                </div>
            </div>
        </StickySidebar>
    </AppLayout>
</template>



<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    workEnvironments: {
        type: Array,
        required: true,
    },
});

const groupedByCategory = computed(() => {
    return props.workEnvironments.reduce((acc, item) => {
        (acc[item.category] = acc[item.category] || []).push(item);
        return acc;
    }, {});
});

const openSections = ref(Object.keys(groupedByCategory.value).map(() => false));

const toggleSection = (index) => {
    openSections.value[index] = !openSections.value[index];
};

const highlightAndScroll = (id) => {
    const element = document.getElementById(`section-${id}`);
    if (element) {
        element.classList.add('glow-text');
        setTimeout(() => {
            element.classList.remove('glow-text');
        }, 2000); // Glow effect duration

        // Smooth scroll to the element
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
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

.custom-heading {
    font-size: 32px;
    line-height: 36px;
    font-weight: 300;
    letter-spacing: -0.3px;
    margin-bottom: 30px;
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
</style>
