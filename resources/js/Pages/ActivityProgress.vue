<script>
import { ref, reactive, computed, onMounted } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  DialogDescription,
  TransitionRoot,
  TransitionChild,
} from '@headlessui/vue'

export default {
    components: { 
        AppLayout, 
        Dialog, 
        DialogPanel, 
        DialogTitle, 
        DialogDescription,
        TransitionRoot,
        TransitionChild,
    },
    props: {
        activities: Array,
        initialResponses: Object,
        initialIndex: Number,
    },
    setup(props) {
        const responses = reactive({ ...props.initialResponses });
        const currentChunkIndex = ref(Math.floor(props.initialIndex / 2));
        const progressFra = ref({ current: 1, total: 1 });
        const progressBar = ref(0);
        const loading = ref(false);
        const isOpen = ref(false);
        const showHelpModal = ref(false);

        const chunkedActivities = computed(() => {
            const chunkSize = 2;
            let result = [];
            for (let i = 0; i < props.activities.length; i += chunkSize) {
                result.push(props.activities.slice(i, i + chunkSize));
            }
            return result;
        });

        const changeChunk = (index) => {
            if (index >= 0 && index < chunkedActivities.value.length) {
                currentChunkIndex.value = index;
                updateProgress();
                fractionProgress();
                clearErrors();
            }
        };

        const validateResponses = () => {
            const currentChunk = chunkedActivities.value[currentChunkIndex.value];
            return currentChunk.every(activity => responses[activity.id]);
        };

        const markEmptyResponses = () => {
            const currentChunk = chunkedActivities.value[currentChunkIndex.value];
            currentChunk.forEach(activity => {
                if (!responses[activity.id]) {
                    activity.error = true;
                } else {
                    if (activity.error) {
                        delete activity.error;
                    }
                }
            });
        };

        const clearErrors = () => {
            chunkedActivities.value.forEach(chunk => {
                chunk.forEach(activity => {
                    if (activity.error) {
                        delete activity.error;
                    }
                });
            });
        };

        const submit = () => {
            if (validateResponses()) {
                loading.value = true;
                router.post('/activity/submit', {
                    responses: responses
                }, {
                    onFinish: () => loading.value = false
                }).then(() => {
                    router.reload();
                    Object.keys(responses).forEach(key => {
                        delete responses[key];
                    });
                    currentChunkIndex.value = 0;
                    progressBar.value = 0;
                    progressFra.value = { current: 1, total: 1 };
                }).catch(error => {
                    console.error('Submission failed:', error);
                });
            } else {
                markEmptyResponses();
            }
        };

        const fractionProgress = () => {
            progressFra.value = {
                current: currentChunkIndex.value + 1,
                total: chunkedActivities.value.length
            };
        };

        const updateProgress = () => {
            progressBar.value = ((currentChunkIndex.value + 1) / chunkedActivities.value.length) * 100;
        };

        const nextChunk = () => {
            if (validateResponses()) {
                changeChunk(currentChunkIndex.value + 1);
            } else {
                markEmptyResponses();
            }
        };

        const prevChunk = () => {
            changeChunk(currentChunkIndex.value - 1);
        };

        const setIsOpen = (value) => {
            isOpen.value = value;
        };

        const toggleHelpModal = () => {
            showHelpModal.value = !showHelpModal.value;
        };

        onMounted(() => {
            updateProgress();
            fractionProgress();
            setTimeout(() => {
                isOpen.value = true;
            }, 1500);
        });

        return {
            responses,
            currentChunkIndex,
            progressFra,
            progressBar,
            loading,
            chunkedActivities,
            changeChunk,
            validateResponses,
            markEmptyResponses,
            clearErrors,
            submit,
            updateProgress,
            fractionProgress,
            nextChunk,
            prevChunk,
            isOpen,
            setIsOpen,
            showHelpModal,
            toggleHelpModal,
            optionsWithImages: [
                { value: 'Strongly Like', src: '/images_options/strongly_like.webp' },
                { value: 'Like', src: '/images_options/like.webp' },
                { value: 'Unsure', src: '/images_options/unsure.webp' },
                { value: 'Dislike', src: '/images_options/dislike.webp' },
                { value: 'Strongly Dislike', src: '/images_options/strongly_dislike.webp' },
            ],
        };
    }
};
</script>

<template>
    <app-layout title="Activity Progress">
        <div class="max-w-3xl mx-auto mt-10 px-4 sm:px-0">
            <!-- Help button -->
            <button @click="toggleHelpModal" class="fixed bottom-4 right-4 bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-600 transition-colors duration-300">
                <span class="text-2xl font-bold">?</span>
            </button>

            <!-- Progress bar outside the form -->
            <div class="relative pt-1 mb-6">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200">
                            Task Progress
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-emerald-600">
                            {{ progressFra.current }} / {{ progressFra.total }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-emerald-200">
                    <div :style="{ width: progressBar + '%' }"
                         class="flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-600"></div>
                </div>
            </div>

            <!-- Activity Display -->
            <div v-if="chunkedActivities.length > 0">
                <div v-for="(chunk, chunkIndex) in chunkedActivities" :key="chunkIndex">
                    <div v-if="currentChunkIndex === chunkIndex" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div v-for="activity in chunk" :key="activity.id"
                             :class="{'bg-red-100': activity.error, 'bg-white': !activity.error}"
                             class="p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-bold mb-4">{{ activity.name }}</h2>
                            <div class="mb-4">
                                <!-- Radio buttons with larger images -->
                                <label v-for="option in optionsWithImages" :key="option.value" class="block mb-2">
                                    <input type="radio" :name="'response[' + activity.id + ']'" :value="option.value"
                                           v-model="responses[activity.id]"
                                           class="mr-2 form-radio h-4 w-4 text-emerald-800 transition duration-150 ease-in-out">
                                    <img :src="option.src" :alt="option.value" class="inline-block h-15 w-12">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation and submit buttons -->
            <div class="flex justify-between mt-6">
                <button v-if="currentChunkIndex > 0" @click="prevChunk"
                        class="bg-black text-white font-semibold py-2 px-4 rounded-lg">
                    Previous
                </button>

                <button v-if="currentChunkIndex < chunkedActivities.length - 1"
                        @click="nextChunk"
                        class="bg-sky-700 hover:bg-sky-900 text-white font-semibold py-2 px-4 rounded-lg">
                    Next
                </button>

                <button v-if="currentChunkIndex === chunkedActivities.length - 1" @click="submit"
                        :disabled="loading"
                        class="bg-emerald-800 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center"
                        :class="{'cursor-not-allowed': loading}">
                    <span v-if="!loading">Submit</span>
                    <div v-if="loading" class="flex items-center">
                        <svg class="spinner h-5 w-5 text-white mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span>Be ready for the path to your career ...</span>
                    </div>
                </button>
            </div>
        </div>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="setIsOpen" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                    Welcome to the Activity Progress Test
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        This test will help us understand your preferences and guide you towards suitable career paths. Here's what each option means:
                                    </p>
                                    <ul class="mt-4 space-y-2">
                                        <li v-for="option in optionsWithImages" :key="option.value" class="flex items-center">
                                            <img :src="option.src" :alt="option.value" class="w-8 h-8 mr-2">
                                            <span class="text-sm font-medium">{{ option.value }}:</span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                {{ 
                                                    option.value === 'Strongly Like' ? 'You really enjoy this activity and it aligns well with your interests.' :
                                                    option.value === 'Like' ? 'You find this activity pleasant and somewhat interesting.' :
                                                    option.value === 'Unsure' ? 'You\'re not sure how you feel about this activity.' :
                                                    option.value === 'Dislike' ? 'You don\'t particularly enjoy this activity.' :
                                                    'You strongly dislike this activity and it doesn\'t align with your interests at all.'
                                                }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                        @click="setIsOpen(false)"
                                    >
                                        Got it, thanks!
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Help Modal -->
        <TransitionRoot appear :show="showHelpModal" as="template">
            <Dialog as="div" @close="toggleHelpModal" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                    Need Help?
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Here's a quick guide to help you understand the options:
                                    </p>
                                    <ul class="mt-4 space-y-2">
                                        <li v-for="option in optionsWithImages" :key="option.value" class="flex items-center">
                                            <img :src="option.src" :alt="option.value" class="w-8 h-8 mr-2">
                                            <span class="text-sm font-medium">{{ option.value }}:</span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                {{ 
                                                    option.value === 'Strongly Like' ? 'You really enjoy this activity and it aligns well with your interests.' :
                                                    option.value === 'Like' ? 'You find this activity pleasant and somewhat interesting.' :
                                                    option.value === 'Unsure' ? 'You\'re not sure how you feel about this activity.' :
                                                    option.value === 'Dislike' ? 'You don\'t particularly enjoy this activity.' :
                                                    'You strongly dislike this activity and it doesn\'t align with your interests at all.'
                                                }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-4">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                        @click="toggleHelpModal"
                                    >
                                        Got it, thanks!
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </app-layout>
</template>

<style scoped>
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }

}

.spinner {
    animation: spin 1s linear infinite;
}
</style>
