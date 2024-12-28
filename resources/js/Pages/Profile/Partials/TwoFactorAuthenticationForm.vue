<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useToast } from "@/Components/ui/toast";
import { useThemeStore } from '@/stores/theme/themeStore';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from "@/Components/ui/dialog";

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const themeStore = useThemeStore();
const { toast } = useToast();

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);
const showConfirmDialog = ref(false);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => !enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]);
            toast({
                title: "2FA Enabled",
                description: "Two factor authentication has been enabled.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Error",
                description: "There was a problem enabling 2FA.",
                variant: "destructive",
            });
        },
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
            toast({
                title: "2FA Confirmed",
                description: "Two factor authentication has been confirmed.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Error",
                description: "Invalid authentication code.",
                variant: "destructive",
            });
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios.post(route('two-factor.recovery-codes'))
        .then(() => {
            showRecoveryCodes();
            toast({
                title: "Success",
                description: "Recovery codes have been regenerated.",
                variant: "success",
            });
        });
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
            toast({
                title: "2FA Disabled",
                description: "Two factor authentication has been disabled.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Error",
                description: "There was a problem disabling 2FA.",
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
                {{ __('profile.two_factor_authentication') }}
            </CardTitle>
            <CardDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                {{ __('profile.add_security') }}
            </CardDescription>
        </CardHeader>

        <CardContent>
            <div class="space-y-6">
                <!-- Status Messages -->
                <div class="space-y-2">
                    <h3 :class="[
                        'text-lg font-medium',
                        themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
                    ]">
                        <template v-if="twoFactorEnabled && !confirming">
                            {{ __('profile.two_factor_enabled') }}
                        </template>
                        <template v-else-if="twoFactorEnabled && confirming">
                            {{ __('profile.finish_enabling_2fa') }}
                        </template>
                        <template v-else>
                            {{ __('profile.not_enabled_2fa') }}
                        </template>
                    </h3>

                    <p :class="[themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600']">
                        {{ __('profile.two_factor_explanation') }}
                    </p>
                </div>

                <!-- QR Code Section -->
                <div v-if="twoFactorEnabled && qrCode" class="space-y-4">
                    <div class="space-y-2">
                        <p v-if="confirming" class="font-semibold" :class="[
                            themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
                        ]">
                            {{ __('profile.finish_enabling_two_factor_instructions') }}
                        </p>
                        <p v-else :class="[themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600']">
                            {{ __('profile.two_factor_enabled_instructions') }}
                        </p>
                    </div>

                    <div class="p-2 inline-block" :class="[
                        themeStore.isDarkMode ? 'bg-gray-800' : 'bg-gray-100'
                    ]" v-html="qrCode" />

                    <div v-if="setupKey" class="space-y-1">
                        <Label :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                            {{ __('profile.setup_key') }}
                        </Label>
                        <p class="font-mono text-sm" :class="[
                            themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-600'
                        ]" v-html="setupKey" />
                    </div>

                    <!-- Confirmation Code Input -->
                    <div v-if="confirming" class="space-y-2">
                        <Label for="code" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                            {{ __('profile.code') }}
                        </Label>
                        <Input id="code" v-model="confirmationForm.code" type="text" inputmode="numeric" autofocus
                            autocomplete="one-time-code" :class="[
                                { 'border-red-500': confirmationForm.errors.code },
                                themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
                            ]" @keyup.enter="confirmTwoFactorAuthentication" />
                        <Alert v-if="confirmationForm.errors.code" variant="destructive">
                            <AlertDescription>{{ confirmationForm.errors.code }}</AlertDescription>
                        </Alert>
                    </div>
                </div>

                <!-- Recovery Codes Section -->
                <div v-if="recoveryCodes.length > 0 && !confirming" class="space-y-4">
                    <div class="space-y-2">
                        <Label :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">
                            {{ __('profile.store_recovery_codes') }}
                        </Label>
                        <div class="grid gap-1 p-4 font-mono text-sm rounded-lg" :class="[
                            themeStore.isDarkMode ? 'bg-gray-800 text-gray-300' : 'bg-gray-100 text-gray-600'
                        ]">
                            <div v-for="code in recoveryCodes" :key="code">{{ code }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <template v-if="!twoFactorEnabled">
                        <Button @click="enableTwoFactorAuthentication" :disabled="enabling" :class="[
                            { 'opacity-50': enabling },
                            themeStore.getThemeClasses('button')
                        ]">
                            {{ __('profile.enable') }}
                        </Button>
                    </template>

                    <template v-else>
                        <Button v-if="confirming" @click="confirmTwoFactorAuthentication" :disabled="enabling" :class="[
                            { 'opacity-50': enabling },
                            themeStore.getThemeClasses('button')
                        ]">
                            {{ __('profile.confirm') }}
                        </Button>

                        <Button v-if="recoveryCodes.length > 0 && !confirming" variant="outline"
                            @click="regenerateRecoveryCodes">
                            {{ __('profile.regenerate_recovery_codes') }}
                        </Button>

                        <Button v-if="recoveryCodes.length === 0 && !confirming" variant="outline"
                            @click="showRecoveryCodes">
                            {{ __('profile.show_recovery_codes') }}
                        </Button>

                        <Button v-if="confirming" variant="outline" :disabled="disabling"
                            @click="disableTwoFactorAuthentication">
                            {{ __('profile.cancel') }}
                        </Button>

                        <Button v-if="!confirming" variant="destructive" :disabled="disabling"
                            :class="{ 'opacity-50': disabling }" @click="disableTwoFactorAuthentication">
                            {{ __('profile.disable') }}
                        </Button>
                    </template>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
