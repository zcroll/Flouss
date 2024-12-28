<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from "@/Components/ui/card";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Toast } from "@/Components/ui/toast";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useToast } from "@/Components/ui/toast";
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
    user: Object,
});

const themeStore = useThemeStore();
const { toast } = useToast();

// Form handling with Inertia
const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

// File handling refs
const photoPreview = ref(null);
const photoInput = ref(null);

// Form submission handler
const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onFinish: () => {
            clearPhotoFileInput();
        },
        onSuccess: () => {
            toast({
                title: "Profile Updated",
                description: "Your profile information has been successfully updated.",
                variant: "success",
            });
        },
        onError: (errors) => {
            if (Object.keys(errors).length > 0) {
                const firstError = Object.values(errors)[0];
                toast({
                    title: "Update Failed",
                    description: firstError,
                    variant: "destructive",
                });
            }
        },
    });
};

// Photo handling methods
const selectNewPhoto = () => photoInput.value.click();

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
            toast({
                title: "Photo Removed",
                description: "Your profile photo has been removed successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Error",
                description: "There was a problem removing your profile photo.",
                variant: "destructive",
            });
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <Card class="w-full" :class="[themeStore.isDarkMode ? 'bg-gray-900' : 'bg-white']">
        <CardHeader>
            <CardTitle :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ __('profile.profile_information') }}</CardTitle>
            <CardDescription :class="[themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500']">{{ __('profile.update_profile_information') }}</CardDescription>
        </CardHeader>

        <CardContent>
            <form @submit.prevent="updateProfileInformation" class="space-y-6">
                <!-- Profile Photo -->
                <div v-if="$page.props.jetstream.managesProfilePhotos">
                    <Input type="file" class="hidden" ref="photoInput" @change="updatePhotoPreview" accept="image/*" />

                    <Label for="photo" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ __('profile.photo') }}</Label>

                    <!-- Current/Preview Photo -->
                    <div class="mt-2">
                        <div v-show="!photoPreview" class="relative h-20 w-20">
                            <img :src="user.profile_photo_url" :alt="user.name"
                                class="rounded-full h-20 w-20 object-cover">
                        </div>

                        <div v-show="photoPreview" class="relative h-20 w-20">
                            <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                                :style="'background-image: url(\'' + photoPreview + '\');'" />
                        </div>
                    </div>

                    <div class="mt-2 flex gap-2">
                        <Button type="button" variant="secondary" @click="selectNewPhoto" 
                            :class="themeStore.getThemeClasses('button')">
                            {{ __('profile.select_new_photo') }}
                        </Button>

                        <Button v-if="user.profile_photo_path" type="button" variant="destructive" @click="deletePhoto">
                            {{ __('profile.remove_photo') }}
                        </Button>
                    </div>

                    <Alert v-if="form.errors.photo" variant="destructive" class="mt-2">
                        <AlertDescription>{{ form.errors.photo }}</AlertDescription>
                    </Alert>
                </div>

                <!-- Name -->
                <div class="space-y-2">
                    <Label for="name" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ __('profile.name') }}</Label>
                    <Input id="name" v-model="form.name" type="text" autocomplete="name"
                        :class="[{ 'border-red-500': form.errors.name }, themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900']" />
                    <Alert v-if="form.errors.name" variant="destructive">
                        <AlertDescription>{{ form.errors.name }}</AlertDescription>
                    </Alert>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <Label for="email" :class="[themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ __('profile.email') }}</Label>
                    <Input id="email" v-model="form.email" type="email"
                        :class="[{ 'border-red-500': form.errors.email }, themeStore.isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900']" />
                    <Alert v-if="form.errors.email" variant="destructive">
                        <AlertDescription>{{ form.errors.email }}</AlertDescription>
                    </Alert>
                </div>
            </form>
        </CardContent>

        <CardFooter class="flex justify-end space-x-2">
            <Button type="submit" @click="updateProfileInformation" :disabled="form.processing"
                :class="[{ 'opacity-50': form.processing }, themeStore.getThemeClasses('button'), themeStore.getThemeClasses('hover')]">
                {{ __('profile.save') }}
            </Button>
        </CardFooter>
    </Card>
</template>
