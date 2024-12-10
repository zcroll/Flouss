<script setup>
import { ref, inject, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { 
    HomeIcon,
    BriefcaseIcon,
    AcademicCapIcon,
    DocumentTextIcon,
    BookOpenIcon,
    ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline';
import NavGroup from './NavGroup.vue';
import NavItem from './NavItem.vue';

const props = defineProps({
    isOver10Days: Boolean
});

const navigationItems = [
    { 
        name: 'Dashboard',
        route: 'dashboard',
        icon: HomeIcon 
    },
    { 
        name: 'Results',
        route: 'results',
        icon: DocumentTextIcon 
    },
    { 
        name: 'Jobs',
        route: 'jobs.index',
        icon: BriefcaseIcon 
    },
    { 
        name: 'Degrees',
        route: 'degrees.index',
        icon: AcademicCapIcon 
    },
    { 
        name: 'Formations',
        route: 'formations.index',
        icon: BookOpenIcon 
    }
];

if (props.isOver10Days) {
    navigationItems.push({
        name: 'Career Test',
        route: 'activities',
        icon: ClipboardDocumentListIcon
    });
}
</script>

<template>
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-stone-950/95 backdrop-blur-xl border-t border-stone-800 rounded-t-xl z-50">
        <NavGroup v-slot="{ ready, size, position, duration }">
            <div
                :style="{
                    '--size': size,
                    '--position': position,
                    '--duration': duration,
                }"
                class="px-2 py-1"
            >
                <!-- Pill Effect -->
                <div
                    v-if="ready"
                    class="absolute inset-y-0 h-[85%] w-[var(--size)] translate-x-[var(--position)] rounded-lg bg-[#db492b]/10 transition-[width,transform] duration-[var(--duration)]"
                />

                <!-- Light Effect -->
                <div
                    v-if="ready"
                    class="absolute bottom-0 h-1/3 w-[var(--size)] translate-x-[var(--position)] rounded-lg bg-[#db492b]/100 blur-lg transition-[width,transform] duration-[var(--duration)]"
                />

                <!-- Navigation Items -->
                <ul class="relative flex items-center justify-around">
                    <NavItem
                        v-for="item in navigationItems"
                        :key="item.route"
                        :href="route(item.route)"
                        :active="route().current(item.route)"
                        class="flex flex-col items-center justify-center py-1.5 px-2 min-w-[40px] nav-item-container"
                    >
                        <div class="flex flex-col items-center gap-[2px]">
                            <component 
                                :is="item.icon" 
                                class="w-[18px] h-[18px] transition-colors duration-300"
                                :class="route().current(item.route) ? 'text-[#db492b]' : 'text-white/60'"
                            />
                            <span 
                                class="text-[9px] leading-none nav-text text-center" 
                                :class="{ 'text-[#db492b]': route().current(item.route) }"
                            >
                                {{ __(`navigation.${item.name.toLowerCase()}`) }}
                            </span>
                        </div>
                    </NavItem>
                </ul>
            </div>
        </NavGroup>
    </nav>
</template>

<style scoped>
.nav-text {
    font-family: 'Satoshi', sans-serif;
    font-weight: 500;
    letter-spacing: -0.025em;
}

.nav-item-container {
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}

:deep(.nav-group) {
    padding: 0.25rem !important;
}
</style> 