<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/helpers/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/helpers/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/helpers/Checkbox.vue';
import InputError from '@/Components/helpers/InputError.vue';
import InputLabel from '@/Components/helpers/InputLabel.vue';
import PrimaryButton from '@/Components/helpers/PrimaryButton.vue';
import SecondaryButton from '@/Components/helpers/SecondaryButton.vue';
import TextInput from '@/Components/helpers/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6 w-full max-w-sm mx-auto px-4 sm:px-0">
            <div>
                <InputLabel for="name" :value="__('auth.name')" class="text-sm sm:text-base" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full text-sm sm:text-base"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2 text-xs sm:text-sm" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-sm sm:text-base" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full text-sm sm:text-base"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2 text-xs sm:text-sm" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" :value="__('auth.password')" class="text-sm sm:text-base" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full text-sm sm:text-base"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 text-xs sm:text-sm" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" :value="__('auth.confirm_password')" class="text-sm sm:text-base" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full text-sm sm:text-base"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 text-xs sm:text-sm" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2 text-xs sm:text-sm">
                            {{ __('auth.agree_to_terms') }} <a target="_blank" :href="route('terms.show')" class="underline text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2 text-xs sm:text-sm" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex flex-row items-center justify-between gap-2">
                <SecondaryButton @click="$inertia.visit(route('login'))" class="text-[10px] sm:text-sm text-gray-600 hover:text-gray-900 rounded-md font-bold">
                    {{ __('auth.already_registered') }}
                </SecondaryButton>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="text-[10px] sm:text-sm">
                    {{ __('auth.sign_up') }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
