<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/helpers/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/helpers/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/helpers/PrimaryButton.vue';

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
            {{ __('auth.verify_email') }}
        </div>

        <div v-if="verificationLinkSent" class="mb-4 font-medium text-xs sm:text-sm text-green-600 text-center sm:text-left px-4 sm:px-0">
            {{ __('auth.verification_link_sent') }}
        </div>

        <form @submit.prevent="submit" class="w-full max-w-sm mx-auto px-4 sm:px-0">
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-2">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full sm:w-auto text-xs sm:text-sm">
                    {{ __('auth.resend_verification_email') }}
                </PrimaryButton>

                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full sm:w-auto">
                    <Link
                        :href="route('profile.show')"
                        class="underline text-xs sm:text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto text-center"
                    >
                        {{ __('auth.edit_profile') }}
                    </Link>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="underline text-xs sm:text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ms-2 w-full sm:w-auto text-center"
                    >
                        {{ __('auth.log_out') }}
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticationCard>
</template>
