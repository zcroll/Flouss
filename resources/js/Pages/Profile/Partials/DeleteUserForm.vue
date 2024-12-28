<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useToast } from "@/Components/ui/toast";
import { useThemeStore } from '@/stores/theme/themeStore';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const themeStore = useThemeStore();
const { toast } = useToast();

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    setTimeout(() => passwordInput.value?.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            toast({
                title: "Account Deleted",
                description: "Your account has been successfully deleted.",
                variant: "success",
            });
        },
        onError: () => {
            passwordInput.value?.focus();
            toast({
                title: "Error",
                description: "There was a problem deleting your account.",
                variant: "destructive",
            });
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <Card class="w-full" :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
        <CardHeader>
            <CardTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                {{ __('profile.delete_account') }}
            </CardTitle>
            <CardDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                {{ __('profile.permanently_delete_account') }}
            </CardDescription>
        </CardHeader>

        <CardContent>
            <div class="max-w-xl">
                <p :class="[themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600']">
                    {{ __('profile.delete_account_warning') }}
                </p>

                <div class="mt-5">
                    <Button variant="destructive" @click="confirmUserDeletion"
                        :class="[themeStore.isDarkMode ? 'hover:bg-red-600' : 'hover:bg-red-500']">
                        {{ __('profile.delete_account') }}
                    </Button>
                </div>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <Dialog :open="confirmingUserDeletion" @close="closeModal">
                <DialogContent :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
                    <DialogHeader>
                        <DialogTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                            {{ __('profile.delete_account') }}
                        </DialogTitle>
                        <DialogDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                            {{ __('profile.delete_account_confirmation') }}
                        </DialogDescription>
                    </DialogHeader>

                    <div class="mt-4 space-y-4">
                        <div class="space-y-2">
                            <Label for="password" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                                {{ __('profile.password') }}
                            </Label>
                            <Input id="password" ref="passwordInput" v-model="form.password" type="password" :class="[
                                { 'border-red-500': form.errors.password },
                                themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                            ]" autocomplete="current-password" @keyup.enter="deleteUser" />
                            <Alert v-if="form.errors.password" variant="destructive">
                                <AlertDescription>{{ form.errors.password }}</AlertDescription>
                            </Alert>
                        </div>
                    </div>

                    <DialogFooter class="mt-6">
                        <Button variant="outline" @click="closeModal" :class="themeStore.getThemeClasses('button')">
                            {{ __('profile.cancel') }}
                        </Button>
                        <Button variant="destructive" @click="deleteUser" :class="[
                            { 'opacity-50': form.processing },
                            themeStore.isDarkMode ? 'hover:bg-red-600' : 'hover:bg-red-500'
                        ]" :disabled="form.processing">
                            {{ __('profile.delete_account') }}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </CardContent>
    </Card>
</template>
