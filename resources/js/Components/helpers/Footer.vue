<template>
    <footer 
        v-show="shouldShowFooter" 
        v-if="!route().current('jobs.index') && 
                !route().current('degrees.index') && 
                !isMobile" 
        class="bg-black transition-opacity duration-300"
    >
        <div class="mx-auto max-w-7xl overflow-hidden px-6 py-12 sm:py-16 lg:px-8">
            <!-- Main Navigation -->
            <nav class="grid grid-cols-2 gap-8 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                <div v-for="item in navigationItems" 
                     :key="item.name" 
                     class="footer-link-container"
                >
                    <Link 
                        :href="item.href" 
                        class="text-sm leading-6 text-gray-200 hover:text-white transition-colors duration-200"
                        :class="{ 'text-blue-400': item.current }"
                    >
                        {{ item.name }}
                    </Link>
                </div>
            </nav>

            <!-- Social Links -->
            <div class="mt-8 flex justify-center space-x-8">
                <a v-for="item in socialLinks" 
                   :key="item.name" 
                   :href="item.href"
                   :aria-label="item.name"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="text-gray-200 hover:text-white transition-colors duration-200"
                >
                    <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                </a>
            </div>

            <!-- Copyright -->
            <p class="mt-8 text-center text-xs leading-5 text-gray-200">
                &copy; {{ currentYear }} Gen.z, Inc. All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// State
const isMobile = ref(false);
const isVisible = ref(true);
let lastScrollPosition = 0;

// Get current route
const page = usePage();

// Computed
const currentYear = computed(() => new Date().getFullYear());

const shouldShowFooter = computed(() => {
    const excludedRoutes = ['jobs.index', 'degrees.index'];
    return !excludedRoutes.includes(page.component) && !isMobile.value;
});

// Navigation Items
const navigationItems = [
    { 
        name: 'Dashboard', 
        href: route('dashboard'), 
        current: page.component === 'Dashboard'
    },
    { 
        name: 'Results', 
        href: route('results'), 
        current: page.component === 'Results'
    }
];

// Social Links with inline SVGs
const socialLinks = [
    {
        name: 'LinkedIn',
        href: 'https://linkedin.com/company/genz',
        icon: {
            template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
            </svg>`
        }
    },
    {
        name: 'Twitter',
        href: 'https://twitter.com/genz',
        icon: {
            template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>`
        }
    }
];

// Methods
const checkIfMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

const handleScroll = () => {
    const currentScrollPosition = window.pageYOffset;
    isVisible.value = currentScrollPosition < lastScrollPosition || 
                     currentScrollPosition < 50;
    lastScrollPosition = currentScrollPosition;
};

// Lifecycle Hooks
onMounted(() => {
    checkIfMobile();
    window.addEventListener('resize', checkIfMobile);
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkIfMobile);
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.footer-link-container {
    @apply transition-transform duration-200;
}

.footer-link-container:hover {
    @apply transform -translate-y-0.5;
}
</style>