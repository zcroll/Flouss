<script setup>
import { Link } from '@inertiajs/vue3';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet"
import { ref, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    canLogin: {
        type: Boolean,
        required: true
    },
    canRegister: {
        type: Boolean,
        required: true
    }
})

const isSticky = ref(false)
const isOpen = ref(false)

const handleScroll = () => {
    isSticky.value = window.scrollY > 0
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<template>
    <div id="globalnav-container" data-registration-modal="true">
        <nav :class="['GlobalNav', { 'GlobalNav--sticky': isSticky }]" role="navigation">
            <Link class="GlobalNav-logo--desktop w--current" data-track="mixpanel" data-target="Home" data-link-type="Header" :href="route('home')">
                <img src="/logo_no_background.png" alt="Gen.Z" class="h-20" />
            </Link>

            <Sheet v-model:open="isOpen">
                <SheetTrigger class="GlobalNav-menu-button lg:hidden">
                    <svg v-if="!isOpen" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" class="svg-inline--fa fa-bars GlobalNav-menu-hamburger" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM0 256C0 238.3 14.33 224 32 224H416C433.7 224 448 238.3 448 256C448 273.7 433.7 288 416 288H32C14.33 288 0 273.7 0 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z"></path>
                    </svg>
                    <svg v-else aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" class="svg-inline--fa fa-xmark GlobalNav-menu-hamburger" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path fill="currentColor" d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/>
                    </svg>
                </SheetTrigger>
                <SheetContent side="left" class="w-[300px] z-[100]">
                    <SheetHeader class="border-b pb-4 mb-4">
                        <SheetTitle class="text-xl font-bold">
                            <img src="/logo_no_background.png" alt="Gen.Z" class="h-12" />
                        </SheetTitle>
                    </SheetHeader>
                    <div class="flex flex-col gap-4 py-4">
                        <Link
                            v-for="(item, index) in [
                {
                  name: 'Home',
                  route: 'home',
                  icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                },
                {
                  name: 'Career Test',
                  route: 'Career-Test',
                  icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
                },
              ]"
                            :key="index"
                            :href="route(item.route)"
                            class="px-2 py-2 text-sm hover:bg-gray-100 rounded-md transition-colors duration-200 flex items-center gap-3"
                            @click="isOpen = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>

                        <div class="border-t my-2"></div>

                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="px-2 py-2 text-sm hover:bg-gray-100 rounded-md transition-colors duration-200 flex items-center gap-3"
                                @click="isOpen = false"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                {{ __('navigation.dashboard') }}
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="px-2 py-2 text-sm hover:bg-gray-100 rounded-md transition-colors duration-200 flex items-center gap-3"
                                @click="isOpen = false"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                {{ __('navigation.log_in') }}
                            </Link>
                        </template>

                        <Link
                            :href="route('main-test')"
                            class="mt-4 px-4 py-2 bg-primary text-white rounded-lg text-center hover:bg-primary/90 transition-colors duration-200 flex items-center justify-center gap-3"
                            @click="isOpen = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('navigation.take_test') }}
                        </Link>
                    </div>
                </SheetContent>
            </Sheet>

            <ul class="GlobalNav-menu">
                <li class="GlobalNav-menu-item">
                    <Link class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" :href="route('For-organizations')">&nbsp;&nbsp;&nbsp;&nbsp;</Link>
                </li>
                <li class="GlobalNav-menu-item">
                    <Link class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" :href="route('home')">Home</Link>
                </li>
                <li class="GlobalNav-menu-item GlobalNav-menu-item--mobile">
                    <Link class="GlobalNav-menu-link GlobalNav-menu-link--default w--current" data-track="mixpanel" data-target="Home" data-link-type="Header" :href="route('home')">{{ __('navigation.home') }}</Link>
                </li>
                <li class="GlobalNav-menu-item">
                    <Link class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="Career Test" data-link-type="Header" :href="route('Career-Test')">{{ __('navigation.Career_Test') }}</Link>
                </li>
                <li class="GlobalNav-menu-item">
                    <Link class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" :href="route('For-organizations')">{{ __('navigation.Organization') }}</Link>
                </li>
            </ul>

            <div class="GlobalNav-menu-item--login">
                <template v-if="$page.props.auth.user">
                    <Link :href="route('dashboard')" class="GlobalNav-menu-link GlobalNav-menu-link--default">
                        {{ __('navigation.dashboard') }}
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="GlobalNav-menu-link GlobalNav-menu-link--default" data-qa-id="login-button">
                        {{ __('navigation.log_in') }}
                    </Link>
                </template>
            </div>

            <Link :href="route('main-test')" class="GlobalNav-button GlobalNav-button--main alans-butt--grey">
                {{ __('navigation.take_test') }}
            </Link>
        </nav>

        <div class="progress-container" id="progress-bar-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
    </div>
</template>

<style scoped>
@import '/public/css/header.css';

/* Override sheet styles */
</style>
