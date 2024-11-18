<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
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

</script>

<template >
    <div >
        <Head :title="title" />
        <div class="min-h-screen relative" style="background-image: linear-gradient(#d3cbb8, #dbd5a4);">

            <Header>
                <template #navigation>
                    <nav class="top-0 left-0 right-0 z-10 fixed" style="background: linear-gradient(0deg, #00000000 0%, #0c0a09 3%);">
                        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center w-full">

                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <Link :href="route('home')">
                                        <ApplicationMark class="block" />
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
                                    </div>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <!-- Language Selector -->
                                <LanguageSelector :languages="$page.props.languages" :selectedLanguage="$page.props.language" />

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

                            <!-- Mobile Menu Button -->
                            <div class="sm:hidden">
                                <button @click="showingNavigationDropdown = !showingNavigationDropdown" 
                                        class="inline-flex items-center justify-center p-2 rounded-full bg-stone-800/50 text-stone-300 hover:text-amber-500 hover:bg-stone-700/50 transition-all duration-200">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" 
                                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" 
                                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Mobile Navigation Menu -->
                    <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" 
                         class="sm:hidden fixed inset-0 z-50">
                        <div class="fixed inset-0 bg-stone-900/90 backdrop-blur-sm" 
                             @click="showingNavigationDropdown = false">
                            <div class="absolute right-0 h-full w-64 bg-stone-800 shadow-xl">
                                <div class="p-6">
                                    <!-- User Info -->
                                    <div class="flex items-center mb-6 pb-6 border-b border-stone-700">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             :src="$page.props.auth.user.profile_photo_url" 
                                             :alt="$page.props.auth.user.name" />
                                        <div class="ml-3">
                                            <div class="font-medium text-stone-100">{{ $page.props.auth.user.name }}</div>
                                            <div class="text-sm text-stone-400">{{ $page.props.auth.user.email }}</div>
                                        </div>
                                    </div>

                                    <!-- Navigation Links -->
                                    <div class="space-y-3">
                                        <ResponsiveNavLink :href="route('dashboard')" 
                                                         :active="route().current('dashboard')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.dashboard') }}
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('results')" 
                                                         :active="route().current('results')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.results') }}
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('jobs.index')" 
                                                         :active="route().current('jobs.index')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.jobs') }}
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('degrees.index')" 
                                                         :active="route().current('degrees.index')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.degrees') }}
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink v-if="isOver10Days" 
                                                         :href="route('activities')" 
                                                         :active="route().current('activities')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.career_test') }}
                                        </ResponsiveNavLink>
                                    </div>

                                    <!-- Settings Links -->
                                    <div class="mt-6 pt-6 border-t border-stone-700">
                                        <ResponsiveNavLink :href="route('profile.show')" 
                                                         :active="route().current('profile.show')"
                                                         class="block px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                            {{ __('navigation.profile') }}
                                        </ResponsiveNavLink>
                                        <form @submit.prevent="logout" class="mt-3">
                                            <ResponsiveNavLink as="button"
                                                             class="w-full text-left px-3 py-2 rounded-lg text-stone-300 hover:bg-stone-700 hover:text-amber-500 transition-colors">
                                                {{ __('navigation.log_out') }}
                                            </ResponsiveNavLink>
                                        </form>
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
    <Footer />
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
