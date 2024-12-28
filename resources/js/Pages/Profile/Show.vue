<script setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import { Card, CardContent } from "@/Components/ui/card";
import { Separator } from "@/Components/ui/separator";
import { useThemeStore } from '@/stores/theme/themeStore';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const themeStore = useThemeStore();
</script>

<template>
    <div class="min-h-screen ">
        <div class="flex-1 space-y-4 container p-8 pt-6">
            <div class="flex items-center justify-between space-y-2">
                <h2 class="text-3xl font-bold tracking-tight" :class="[
                    themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
                ]">
                    {{ __('profile.profile') }}
                </h2>
            </div>

            <Separator :class="[
                themeStore.isDarkMode ? 'bg-gray-700' : 'bg-gray-200'
            ]" />

            <div class="grid gap-6">
                <!-- Profile Information -->
                <Card v-if="$page.props.jetstream.canUpdateProfileInformation" class="col-span-3 border-0 shadow-md"
                    :class="[
                        themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white'
                    ]">
                    <CardContent class="p-0">
                        <UpdateProfileInformationForm :user="$page.props.auth.user" />
                    </CardContent>
                </Card>

                <!-- Password Update -->
                <Card v-if="$page.props.jetstream.canUpdatePassword" class="col-span-3 border-0 shadow-md" :class="[
                    themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white'
                ]">
                    <CardContent class="p-0">
                        <UpdatePasswordForm />
                    </CardContent>
                </Card>

                <!-- Two Factor Authentication -->
                <Card v-if="$page.props.jetstream.canManageTwoFactorAuthentication"
                    class="col-span-3 border-0 shadow-md" :class="[
                        themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white'
                    ]">
                    <CardContent class="p-0">
                        <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
                    </CardContent>
                </Card>

                <!-- Browser Sessions -->
                <Card class="col-span-3 border-0 shadow-md" :class="[
                    themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white'
                ]">
                    <CardContent class="p-0">
                        <LogoutOtherBrowserSessionsForm :sessions="sessions" />
                    </CardContent>
                </Card>

                <!-- Delete Account -->
                <Card v-if="$page.props.jetstream.hasAccountDeletionFeatures" class="col-span-3 border-0 shadow-md"
                    :class="[
                        themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white'
                    ]">
                    <CardContent class="p-0">
                        <DeleteUserForm />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
