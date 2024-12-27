<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';

import ActionMessage from '@/Components/helpers/ActionMessage.vue';
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage
} from '@/components/ui/form';
import InputError from '@/Components/helpers/InputError.vue';
import PrimaryButton from '@/Components/helpers/PrimaryButton.vue';
import SecondaryButton from '@/Components/helpers/SecondaryButton.vue';
import { Input } from '@/Components/ui/input';
import Form from '@/components/ui/form/Form.vue';
import FormControl from '@/components/ui/form/FormControl.vue';

const props = defineProps({
    user: Object,
});

// Form validation schema
const formSchema = toTypedSchema(z.object({
    name: z.string().min(2).max(50),
    email: z.string().email(),
    photo: z.any().optional()
}));

// Form state using vee-validate
const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.user.name,
        email: props.user.email,
        photo: null
    }
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const onSubmit = form.handleSubmit((values) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', values.name);
    formData.append('email', values.email);

    if (photoInput.value?.files[0]) {
        formData.append('photo', photoInput.value.files[0]);
    }

    router.post(route('user-profile-information.update'), formData, {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
});

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

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
    <Form @submit="onSubmit">
        <div class="space-y-8">
            <div>
                <h2 class="text-lg font-medium text-slate-100">
                    {{ __('profile.profile_information') }}
                </h2>
                <p class="mt-1 text-sm text-slate-300">
                    {{ __('profile.update_profile_information') }}
                </p>
            </div>

            <div class="space-y-6">
                <!-- Profile Photo -->
                <div v-if="$page.props.jetstream.managesProfilePhotos">
                    <FormField name="photo">
                        <FormItem>
                            <FormLabel class="text-slate-100">{{ __('profile.photo') }}</FormLabel>
                            <FormControl>
                                <input type="file" class="hidden" ref="photoInput" @change="updatePhotoPreview">
                            </FormControl>

                            <!-- Current Profile Photo -->
                            <div v-show="!photoPreview" class="mt-2">
                                <img :src="user.profile_photo_url" :alt="user.name"
                                    class="rounded-full h-20 w-20 object-cover">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div v-show="photoPreview" class="mt-2">
                                <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                                    :style="'background-image: url(\'' + photoPreview + '\');'" />
                            </div>

                            <div class="mt-2 flex gap-2">
                                <SecondaryButton type="button" @click.prevent="selectNewPhoto">
                                    {{ __('profile.select_new_photo') }}
                                </SecondaryButton>

                                <SecondaryButton v-if="user.profile_photo_path" type="button"
                                    @click.prevent="deletePhoto">
                                    {{ __('profile.remove_photo') }}
                                </SecondaryButton>
                            </div>

                            <FormMessage />
                        </FormItem>
                    </FormField>
                </div>

                <!-- Name -->
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel class="text-slate-100">{{ __('profile.name') }}</FormLabel>
                        <FormControl>
                            <Input type="text" class="bg-zinc-700 text-slate-100" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <!-- Email -->
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel class="text-slate-100">{{ __('profile.email') }}</FormLabel>
                        <FormControl>
                            <Input type="email" class="bg-zinc-700 text-slate-100" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </div>

            <div class="flex items-center gap-4">
                <ActionMessage :on="form.recentlySuccessful" class="text-slate-100">
                    {{ __('profile.saved') }}
                </ActionMessage>

                <PrimaryButton :disabled="form.isSubmitting">
                    {{ __('profile.save') }}
                </PrimaryButton>
            </div>
        </div>
    </Form>
</template>
