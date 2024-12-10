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
import NavGroup from '@/Components/helpers/NavGroup.vue';
import NavItem from '@/Components/helpers/NavItem.vue';
import MobileNavigation from '@/Components/helpers/MobileNavigation.vue';

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

const navigationItems = [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'Results', route: 'results' },
    { name: 'Jobs', route: 'jobs.index' },
    { name: 'Degrees', route: 'degrees.index' },
    { name: 'Formations', route: 'formations.index' }
];

</script>

<template >
    <div >
        <Head :title="title" />
        <div class="min-h-screen relative sm:pb-0" style="background-image: linear-gradient(#ffffff, #ddd3ed);">

            <Header>
                <template #navigation>
                    <nav class="top-3 left-0 mx-3 right-0 z-10 fixed rounded-xl" style="background: linear-gradient(0deg, #00000000 0%, #0c0a09 3%);">
                        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center w-full h-[55px]">

                            <div class="flex h-full">
                                <div class="flex-shrink-0 flex items-center">
                                    <Link :href="route('home')">
                                        <ApplicationMark class="block" />
                                    </Link>
                                </div>
                                <!-- Navigation Links -->
                                <div class="hidden md[630px]:-my-px sm:ml-32 sm:flex items-center justify-between w-full h-full">
                                    <NavGroup v-slot="{ ready, size, position, duration }">
                                        <div
                                            :style="{
                                                '--size': size,
                                                '--position': position,
                                                '--duration': duration,
                                            }"
                                        >
                                            <!-- Border highlighter - Reduced opacity and blur -->
                                            <div
                                                v-if="ready"
                                                class="absolute bottom-0 h-1/2 w-[var(--size)] translate-x-[var(--position)] bg-white/10 blur-lg transition-[width,transform] duration-[var(--duration)]"
                                            />

                                            <div class="relative">
                                                <!-- Pill - Adjusted opacity -->
                                                <div
                                                    v-if="ready"
                                                    class="absolute inset-y-0 h-full w-[var(--size)] translate-x-[var(--position)] rounded-full bg-[#db492b]/10  transition-[width,transform] duration-[var(--duration)]"
                                                />

                                                <!-- Light effect - Adjust size -->
                                                <div
                                                    v-if="ready"
                                                    class="absolute bottom-0 h-1/2 w-[var(--size)] translate-x-[var(--position)] rounded-full bg-[#db492b]/100 blur-lg transition-[width,transform] duration-[var(--duration)]"
                                                />

                                                <!-- Navigation Items -->
                                                <ul class="relative flex items-center gap-3">
                                                    <NavItem
                                                        v-for="item in navigationItems"
                                                        :key="item.route"
                                                        :href="route(item.route)"
                                                        :active="route().current(item.route)"
                                                    >
                                                        {{ __(`navigation.${item.name.toLowerCase()}`) }}
                                                    </NavItem>
                                                </ul>
                                            </div>
                                        </div>
                                    </NavGroup>
                                </div>
                            </div>

                            <div class="hidden lg:flex sm:items-center sm:ml-6 h-full ">
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
                            <div class="lg:hidden">
                                <Sheet v-model:open="isOpen">
                                    <SheetTrigger class="inline-flex items-center justify-center p-1 rounded-full border-2 border-stone-300/20 transition-all duration-200">
                                        <img class="h-6 w-6 rounded-full object-cover"
                                             :src="$page.props.auth.user.profile_photo_url"
                                             :alt="$page.props.auth.user.name" />
                                    </SheetTrigger>
                                    <SheetContent side="top" class="w-auto bg-gray-50 rounded-b-xl backdrop-blur-xl ">
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
        <MobileNavigation :is-over10-days="isOver10Days" />
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
