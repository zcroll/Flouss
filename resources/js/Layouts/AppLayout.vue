<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    HomeIcon, 
    BriefcaseIcon, 
    UserIcon, 
    AcademicCapIcon,
    BuildingOfficeIcon,
    DocumentTextIcon,
    BookOpenIcon,
    ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline';
import ApplicationMark from '@/Components/helpers/ApplicationMark.vue';
import Dropdown from '@/Components/helpers/Dropdown.vue';
import DropdownLink from '@/Components/helpers/DropdownLink.vue';
import NavLink from '@/Components/helpers/NavLink.vue';
import ResponsiveNavLink from '@/Components/helpers/ResponsiveNavLink.vue';
import Header from "@/Components/helpers/Header.vue";
import Footer from "@/Components/helpers/Footer.vue";
import LanguageSelector from '@/Components/helpers/LanguageSelector.vue';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/Components/ui/sheet"
import __ from '@/lang';

const props = defineProps({
    title: String,
    isOver10Days: Boolean,
    headTitle: String,
    headSubTitle: String,
    showDiv: Boolean,
});

const showingNavigationDropdown = ref(false);
const isOpen = ref(false);

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
        <div class="min-h-screen relative sm:pb-0" style="background-image: linear-gradient(#ffffff, #ddd3ed);">

            <Header>
                <template #navigation>
                    <nav class="top-0 left-0 right-0 z-10 fixed" style="background: linear-gradient(0deg, #00000000 0%, #0c0a09 3%);">
                        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center w-full h-[50px]">

                            <div class="flex h-full">
                                <div class="flex-shrink-0 flex items-center">
                                    <Link :href="route('home')">
                                        <ApplicationMark class="block" />
                                    </Link>
                                </div>
                                <!-- Navigation Links -->
                                <div class="hidden sm:-my-px sm:ml-32 sm:flex items-center justify-between w-full h-full">
                                    <div class="flex space-x-8 h-full items-center">
                                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="btn_link h-[10px] flex items-center">
                                            {{ __('navigation.dashboard') }}
                                        </NavLink>
                                        <NavLink :href="route('results')" :active="route().current('results')" class="btn_link h-[30px] flex items-center">
                                            {{ __('navigation.results') }}
                                        </NavLink>
                                        <NavLink :href="route('jobs.index')" :active="route().current('jobs.index')" class="btn_link h-[30px] flex items-center">
                                            {{ __('navigation.jobs') }}
                                        </NavLink>
                                        <NavLink :href="route('degrees.index')" :active="route().current('degrees.index')" class="btn_link h-[30px] flex items-center">
                                            {{ __('navigation.degrees') }}
                                        </NavLink>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6 h-full">
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
                                                <button type="button" class="inline-flex h-[30px] items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
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
                                <Sheet v-model:open="isOpen">
                                    <SheetTrigger class="inline-flex items-center justify-center p-1 rounded-full border-2 border-stone-300/20 hover:border-amber-500/50 transition-all duration-200">
                                        <img class="h-6 w-6 rounded-full object-cover" 
                                             :src="$page.props.auth.user.profile_photo_url" 
                                             :alt="$page.props.auth.user.name" />
                                    </SheetTrigger>
                                    <SheetContent side="top" class="w-[250px] bg-stone-950/95 backdrop-blur-xl border-l border-stone-800">
                                        <SheetHeader>
                                            <Link :href="route('profile.show')" 
                                                  class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-stone-800/50 text-gray-900 transition-colors"
                                                  @click="isOpen = false">
                                                <UserIcon class="w-4 h-4" />
                                                <span class="text-sm">{{ __('navigation.profile') }}</span>
                                            </Link>
                                            <div class="border-t border-gray-200 my-1.5"></div>
                                            <LanguageSelector 
                                                :languages="$page.props.languages" 
                                                :selectedLanguage="$page.props.language"
                                                :isMobile="true" 
                                            />
                                        </SheetHeader>
                                        <div class="flex flex-col gap-1.5">
                                            <div class="border-t border-gray-200 my-1.5"></div>
                                            <form @submit.prevent="logout">
                                                <button type="submit" 
                                                        class="w-full flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-stone-800/50 text-red-400 transition-colors text-sm"
                                                        @click="isOpen = false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                    <span>{{ __('navigation.log_out') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </SheetContent>
                                </Sheet>
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

        <!-- Mobile Bottom Navigation -->
        <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-stone-950 backdrop-blur-lg border-t border-stone-800 z-50">
            <div class="flex items-center justify-around px-3 py-3">
                <!-- Dashboard -->
                <Link :href="route('dashboard')" 
                      class="flex flex-col items-center space-y-1">
                    <HomeIcon class="w-6 h-6" :class="{'text-[#db492b]': route().current('dashboard'), 'text-stone-400': !route().current('dashboard')}" />
                </Link>

                <!-- Results -->
                <Link :href="route('results')" 
                      class="flex flex-col items-center space-y-1">
                    <DocumentTextIcon class="w-6 h-6" :class="{'text-[#db492b]': route().current('results'), 'text-stone-400': !route().current('results')}" />
                </Link>

                <!-- Jobs -->
                <Link :href="route('jobs.index')" 
                      class="flex flex-col items-center space-y-1">
                    <BriefcaseIcon class="w-6 h-6" :class="{'text-[#db492b]': route().current('jobs.index'), 'text-stone-400': !route().current('jobs.index')}" />
                </Link>

                <!-- Degrees -->
                <Link :href="route('degrees.index')" 
                      class="flex flex-col items-center space-y-1">
                    <AcademicCapIcon class="w-6 h-6" :class="{'text-[#db492b]': route().current('degrees.index'), 'text-stone-400': !route().current('degrees.index')}" />
                </Link>

                <!-- Career Test (if over 10 days) -->
                <Link v-if="isOver10Days" 
                      :href="route('activities')" 
                      class="flex flex-col items-center space-y-1">
                    <ClipboardDocumentListIcon class="w-6 h-6" :class="{'text-[#db492b]': route().current('activities'), 'text-stone-400': !route().current('activities')}" />
                    <span class="text-xs" :class="{'text-amber-500': route().current('activities'), 'text-stone-400': !route().current('activities')}">
                        {{ __('navigation.career_test') }}
                    </span>
                </Link>
            </div>
        </nav>
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
