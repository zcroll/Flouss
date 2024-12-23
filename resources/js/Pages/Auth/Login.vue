<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/helpers/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/helpers/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/helpers/Checkbox.vue';
import InputError from '@/Components/helpers/InputError.vue';
import InputLabel from '@/Components/helpers/InputLabel.vue';
import PrimaryButton from '@/Components/helpers/PrimaryButton.vue';
import TextInput from '@/Components/helpers/TextInput.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    error: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div v-if="error" class="mb-4 font-medium text-sm text-red-600">
            {{ error }}
        </div>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6 w-full max-w-sm mx-auto px-4 sm:px-0 bg-transparent">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" :value="__('auth.password')" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-800 hover:text-gray-900 font-bold">
                    {{ __('auth.forgot_password') }}
                </Link>
            </div>

            <div>
                <PrimaryButton class="w-full justify-center rounded-md px-4 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ __('auth.log_in') }}
                </PrimaryButton>
            </div>
        </form>

        <div class="relative mt-8 sm:mt-10 max-w-sm mx-auto px-4 sm:px-0">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200" />
            </div>
            <div class="relative flex justify-center text-sm font-medium leading-6">
                <span class=" px-4 sm:px-6 text-gray-900">{{ __('auth.or_continue_with') }}</span>
            </div>
        </div>

        <div class="mt-6 max-w-sm mx-auto px-4 sm:px-0">
            <button class="w-full">
                <a href="/auth/google" class="flex w-full items-center justify-center gap-3 rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:ring-transparent">
                    <svg class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24">
                        <path d="M12.0003 4.75C13.7703 4.75 15.3553 5.36002 16.6053 6.54998L20.0303 3.125C17.9502 1.19 15.2353 0 12.0003 0C7.31028 0 3.25527 2.69 1.28027 6.60998L5.27028 9.70498C6.21525 6.86002 8.87028 4.75 12.0003 4.75Z" fill="#EA4335" />
                        <path d="M23.49 12.275C23.49 11.49 23.415 10.73 23.3 10H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.945 21.1C22.2 19.01 23.49 15.92 23.49 12.275Z" fill="#4285F4" />
                        <path d="M5.26498 14.2949C5.02498 13.5699 4.88501 12.7999 4.88501 11.9999C4.88501 11.1999 5.01998 10.4299 5.26498 9.7049L1.275 6.60986C0.46 8.22986 0 10.0599 0 11.9999C0 13.9399 0.46 15.7699 1.28 17.3899L5.26498 14.2949Z" fill="#FBBC05" />
                        <path d="M12.0004 24.0001C15.2404 24.0001 17.9654 22.935 19.9454 21.095L16.0804 18.095C15.0054 18.82 13.6204 19.245 12.0004 19.245C8.8704 19.245 6.21537 17.135 5.2654 14.29L1.27539 17.385C3.25539 21.31 7.3104 24.0001 12.0004 24.0001Z" fill="#34A853" />
                    </svg>
                    <span>{{ __('auth.sign_in_with_google') }}</span>
                </a>
            </button>
        </div>

        <!-- <p class="mt-8 sm:mt-10 text-center text-sm text-gray-500 px-4 sm:px-0">
            {{ __('auth.not_a_member') }}
            <Link :href="route('register')" class="font-semibold leading-6 text-[#db492b] hover:text-gray-900">
                {{ __('auth.sign_up') }}
            </Link>
        </p> -->
    </AuthenticationCard>
</template>
