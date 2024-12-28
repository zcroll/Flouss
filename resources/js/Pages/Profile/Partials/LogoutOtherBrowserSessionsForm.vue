<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from "@/Components/ui/dialog";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useToast } from "@/Components/ui/toast";
import { useThemeStore } from '@/stores/theme/themeStore';
import { Monitor, Smartphone } from 'lucide-vue-next';

const props = defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);
const themeStore = useThemeStore();
const { toast } = useToast();

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;
    setTimeout(() => passwordInput.value?.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            toast({
                title: "Sessions Terminated",
                description: "Other browser sessions have been successfully logged out.",
                variant: "success",
            });
        },
        onError: () => {
            passwordInput.value?.focus();
            toast({
                title: "Error",
                description: "There was a problem logging out other sessions.",
                variant: "destructive",
            });
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;
    form.reset();
};
</script>

<template>
    <Card class="w-full" :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
        <CardHeader>
            <CardTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                {{ __('profile.browser_sessions') }}
            </CardTitle>
            <CardDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                {{ __('profile.manage_sessions') }}
            </CardDescription>
        </CardHeader>

        <CardContent class="space-y-6">
            <p :class="[themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600']">
                {{ __('profile.logout_other_sessions_info') }}
            </p>

            <!-- Active Sessions List -->
            <div v-if="sessions.length > 0" class="space-y-4">
                <div v-for="(session, i) in sessions" :key="i"
                    class="flex items-center p-3 rounded-lg transition-colors"
                    :class="[themeStore.isDarkMode ? 'bg-gray-800/50' : 'bg-gray-50']">
                    <div class="flex-shrink-0">
                        <Monitor v-if="session.agent.is_desktop" class="w-6 h-6"
                            :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']" />
                        <Smartphone v-else class="w-6 h-6"
                            :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']" />
                    </div>

                    <div class="ml-3 flex-1">
                        <div :class="[themeStore.isDarkMode ? 'text-gray-200' : 'text-gray-700']">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} -
                            {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div class="flex items-center gap-2 mt-1 text-xs">
                            <span :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                                {{ session.ip_address }}
                            </span>

                            <span v-if="session.is_current_device"
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                {{ __('profile.current_device') }}
                            </span>
                            <span v-else :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                                {{ __('profile.last_active') }} {{ session.last_active }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-5">
                <Button @click="confirmLogout" :class="themeStore.getThemeClasses('button')">
                    {{ __('profile.logout_other_browser_sessions') }}
                </Button>
            </div>

            <!-- Confirmation Modal -->
            <Dialog :open="confirmingLogout" @close="closeModal">
                <DialogContent :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
                    <DialogHeader>
                        <DialogTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                            {{ __('profile.logout_other_browser_sessions') }}
                        </DialogTitle>
                        <DialogDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                            {{ __('profile.please_enter_password') }}
                        </DialogDescription>
                    </DialogHeader>

                    <div class="mt-4 space-y-4">
                        <div class="space-y-2">
                            <Input ref="passwordInput" v-model="form.password" type="password" :class="[
                                { 'border-red-500': form.errors.password },
                                themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                            ]" :placeholder="__('profile.password')" autocomplete="current-password"
                                @keyup.enter="logoutOtherBrowserSessions" />
                            <Alert v-if="form.errors.password" variant="destructive">
                                <AlertDescription>{{ form.errors.password }}</AlertDescription>
                            </Alert>
                        </div>
                    </div>

                    <DialogFooter class="mt-6">
                        <Button variant="outline" @click="closeModal" :class="themeStore.getThemeClasses('button')">
                            {{ __('profile.cancel') }}
                        </Button>
                        <Button @click="logoutOtherBrowserSessions" :disabled="form.processing" :class="[
                            { 'opacity-50': form.processing },
                            themeStore.getThemeClasses('button')
                        ]">
                            {{ __('profile.logout_other_browser_sessions') }}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </CardContent>
    </Card>
</template>
