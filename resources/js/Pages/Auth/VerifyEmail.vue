<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-xs sm:text-sm text-gray-600 text-center sm:text-left px-4 sm:px-0">
            Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        <div v-if="verificationLinkSent" class="mb-4 font-medium text-xs sm:text-sm text-green-600 text-center sm:text-left px-4 sm:px-0">
            A new verification link has been sent to the email address you provided in your profile settings.
        </div>

        <form @submit.prevent="submit" class="w-full max-w-sm mx-auto px-4 sm:px-0">
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-2">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full sm:w-auto text-xs sm:text-sm">
                    Resend Verification Email
                </PrimaryButton>

                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full sm:w-auto">
                    <Link
                        :href="route('profile.show')"
                        class="underline text-xs sm:text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto text-center"
                    >
                        Edit Profile</Link>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="underline text-xs sm:text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ms-2 w-full sm:w-auto text-center"
                    >
                        Log Out
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticationCard>
</template>
