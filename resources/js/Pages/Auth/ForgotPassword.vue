<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/helpers/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/helpers/AuthenticationCardLogo.vue';
import InputError from '@/Components/helpers/InputError.vue';
import InputLabel from '@/Components/helpers/InputLabel.vue';
import PrimaryButton from '@/Components/helpers/PrimaryButton.vue';
import TextInput from '@/Components/helpers/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
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

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                   
                    {{ __('auth.password_reset') }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
