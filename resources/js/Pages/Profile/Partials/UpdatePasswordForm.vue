<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useToast } from "@/Components/ui/toast";
import { useThemeStore } from '@/stores/theme/themeStore';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const themeStore = useThemeStore();
const { toast } = useToast();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast({
                title: "Password Updated",
                description: "Your password has been successfully updated.",
                variant: "success",
            });
        },
        onError: (errors) => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }

            toast({
                title: "Error",
                description: Object.values(errors)[0],
                variant: "destructive",
            });
        },
    });
};
</script>

<template>
    <Card class="w-full" :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
        <CardHeader>
            <CardTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                {{ __('profile.update_password') }}
            </CardTitle>
            <CardDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                {{ __('profile.ensure_account_security') }}
            </CardDescription>
        </CardHeader>

        <CardContent>
            <form @submit.prevent="updatePassword" class="space-y-6">
                <!-- Current Password -->
                <div class="space-y-2">
                    <Label for="current_password" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                        {{ __('profile.current_password') }}
                    </Label>
                    <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                        type="password" :class="[
                            { 'border-red-500': form.errors.current_password },
                            themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                        ]" autocomplete="current-password" />
                    <Alert v-if="form.errors.current_password" variant="destructive">
                        <AlertDescription>{{ form.errors.current_password }}</AlertDescription>
                    </Alert>
                </div>

                <!-- New Password -->
                <div class="space-y-2">
                    <Label for="password" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                        {{ __('profile.new_password') }}
                    </Label>
                    <Input id="password" ref="passwordInput" v-model="form.password" type="password" :class="[
                        { 'border-red-500': form.errors.password },
                        themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                    ]" autocomplete="new-password" />
                    <Alert v-if="form.errors.password" variant="destructive">
                        <AlertDescription>{{ form.errors.password }}</AlertDescription>
                    </Alert>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <Label for="password_confirmation"
                        :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                        {{ __('profile.confirm_password') }}
                    </Label>
                    <Input id="password_confirmation" v-model="form.password_confirmation" type="password" :class="[
                        { 'border-red-500': form.errors.password_confirmation },
                        themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                    ]" autocomplete="new-password" />
                    <Alert v-if="form.errors.password_confirmation" variant="destructive">
                        <AlertDescription>{{ form.errors.password_confirmation }}</AlertDescription>
                    </Alert>
                </div>
            </form>
        </CardContent>

        <CardFooter class="flex justify-end space-x-2">
            <Button type="submit" @click="updatePassword" :disabled="form.processing" :class="[
                { 'opacity-50': form.processing },
                themeStore.getThemeClasses('button'),
                themeStore.getThemeClasses('hover')
            ]">
                {{ __('profile.save') }}
            </Button>
        </CardFooter>
    </Card>
</template>
