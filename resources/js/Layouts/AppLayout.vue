<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Header from "@/Components/Header.vue";
import LanguageSelector from '@/Components/LanguageSelector.vue';
import __ from '@/lang';

const props = defineProps({
    title: String,
    isOver10Days: Boolean,
    headTitle: String,
    headSubTitle: String,
    showDiv: Boolean,

});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), current: route().current('dashboard') },
    { name: 'Results', href: route('results'), current: route().current('results') },
    // Add more navigation items as needed
];

const socialLinks = [
    // ... (keep your existing social links)
];
</script>

<template >
    <div >
        <Head :title="title" />
        <div class="min-h-screen relative" style="background-image: linear-gradient(#e4e2dc, #c4bcac);">

            <Header>
                <template #navigation>
                    <nav class="absolute top-5 left-0 right-0 z-10 bg-transparent">
                        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center w-full">

                            <div class="flex">
                                        <div class="flex-shrink-0">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-8 w-15" />
                                </Link>
                                        </div>
                                <!-- Navigation Links -->
                                <div class="hidden sm:-my-px sm:ml-32 sm:flex items-center justify-between w-full">
                                    <div class="flex space-x-8">
                                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="btn_link">
                                            {{ __('navigation.dashboard') }}
                                        </NavLink>
                                        <NavLink :href="route('results')" :active="route().current('results')" class="btn_link ">
                                            {{ __('navigation.results') }}
                                        </NavLink>
                                        <NavLink :href="route('jobs.index')" :active="route().current('jobs.index')" class="btn_link">
                                            {{ __('navigation.jobs') }}
                                        </NavLink>
                                        <NavLink :href="route('degrees.index')" :active="route().current('degrees.index')" class="btn_link">
                                            {{ __('navigation.degrees') }}
                                        </NavLink>
                                        <NavLink
                                            v-if="isOver10Days"
                                            :href="route('activities')"
                                            :active="route().current('activities')"
                                            class="btn_link text-white hover:text-green-400 transition duration-150 ease-in-out"
                                        >
                                            {{ __('navigation.career_test') }}
                                        </NavLink>
                                    </div>
                                </div>
                            </div>

                                <div class="hidden sm:flex sm:items-center sm:ml-6">
                                    <!-- Language Selector -->
                                    <LanguageSelector  :languages="$page.props.languages" :selectedLanguage="$page.props.language" />

                                    <!-- Teams Dropdown -->
                                    <div class="ml-3 relative" v-if="$page.props.jetstream.hasTeamFeatures">
                                        <Dropdown align="right" width="60">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                        {{ $page.props.auth.user.current_team.name }}
                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>
                                            <template #content>
                                                <div class="w-60">
                                                    <!-- Team Management -->
                                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                                        {{ __('navigation.manage_team') }}
                                                    </div>
                                                    <!-- Team Settings -->
                                                    <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                                        {{ __('navigation.team_settings') }}
                                                    </DropdownLink>
                                                    <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                        {{ __('navigation.create_new_team') }}
                                                    </DropdownLink>
                                                    <!-- Team Switcher -->
                                                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                        <div class="border-t border-gray-200"></div>
                                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                                            {{ __('navigation.switch_teams') }}
                                                        </div>
                                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                            <form @submit.prevent="switchToTeam(team)">
                                                                <DropdownLink as="button">
                                                                    <div class="flex items-center">
                                                                        <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <div>{{ team.name }}</div>
                                                                    </div>
                                                                </DropdownLink>
                                                            </form>
                                                        </template>
                                                    </template>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>

                                    <!-- Settings Dropdown -->
                                    <div class="ml-3 relative">
                                        <Dropdown align="right" width="60">
                                            <template #trigger>
                                                <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                    <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                                                </button>
                                                <span v-else class="inline-flex rounded-md">
                                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                        {{ $page.props.auth.user.name }}
                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>
                                            <template #content>
                                                <div class="w-60">
                                                <!-- Account Management -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('navigation.manage_account') }}
                                                </div>
                                                <DropdownLink :href="route('profile.show')">
                                                    {{ __('navigation.profile') }}
                                                </DropdownLink>
                                                <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                                    {{ __('navigation.api_tokens') }}
                                                </DropdownLink>
                                                <div class="border-t border-gray-200"></div>
                                                <!-- Authentication -->
                                                <form @submit.prevent="logout">
                                                    <DropdownLink as="button">
                                                        {{ __('navigation.log_out') }}
                                                    </DropdownLink>
                                                </form>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </div>

                                <!-- Hamburger -->
                                <div class=" sm:hidden mt-5">
                                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>


                            </div>
                        </div>

                        <!-- Responsive Navigation Menu -->
                        <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                            <!-- Overlay that closes when clicked outside the menu -->
                            <div class="fixed inset-0 bg-gray-800 bg-opacity-75" @click="showingNavigationDropdown=false">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="absolute inset-0 overflow-y-auto">
                                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                                            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                                <div class="mt-2">
                                                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                                        {{ __('navigation.dashboard') }}
                                                    </ResponsiveNavLink>
                                                    <ResponsiveNavLink :href="route('results')" :active="route().current('results')">
                                                        {{ __('navigation.results') }}
                                                    </ResponsiveNavLink>
                                                    <ResponsiveNavLink :href="route('jobs.index')" :active="route().current('jobs.index')">
                                                        {{ __('navigation.jobs') }}
                                                    </ResponsiveNavLink>
                                                    <ResponsiveNavLink :href="route('degrees.index')" :active="route().current('degrees.index')">
                                                        {{ __('navigation.degrees') }}
                                                    </ResponsiveNavLink>
                                                    <ResponsiveNavLink v-if="isOver10Days" :href="route('activities')" :active="route().current('activities')">
                                                        {{ __('navigation.career_test') }}
                                                    </ResponsiveNavLink>
                                                </div>

                                                <!-- Responsive Settings Options -->
                                                <div class="pt-4 pb-1 border-t border-gray-200">
                                                    <div class="flex items-center px-4">
                                                        <div class="flex-shrink-0">
                                                            <img class="h-10 w-10 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                                                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 space-y-1">
                                                        <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                                            {{ __('navigation.profile') }}
                                                        </ResponsiveNavLink>
                                                        <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                                            {{ __('navigation.api_tokens') }}
                                                        </ResponsiveNavLink>
                                                        <!-- Authentication -->
                                                        <form method="POST" @submit.prevent="logout">
                                                            <ResponsiveNavLink as="button">
                                                                {{ __('navigation.log_out') }}
                                                            </ResponsiveNavLink>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </nav>
                </template>
            </Header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-black">
            <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                <nav class="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                    <div v-for="item in navigation" :key="item.name" class="pb-6">
                        <a :href="item.href" class="text-sm leading-6 text-gray-200 hover:text-gray-200">{{ item.name }}</a>
                    </div>
                </nav>
                <div class="mt-10 flex justify-center space-x-10">
                    <a v-for="item in socialLinks" :key="item.name" :href="item.href" class="text-gray-200 hover:text-gray-200">
                        <span class="sr-only">{{ item.name }}</span>
                        <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                    </a>
                </div>
                <p class="mt-10 text-center text-xs leading-5 text-gray-200">&copy; 2024 Your Company, Inc. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>
<style scoped>
.navbar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 10;
    width: 100%;
    padding: 1rem 0;
    background-color: transparent;
}
</style>
