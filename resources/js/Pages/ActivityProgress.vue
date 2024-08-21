<script>
import {ref, reactive, computed, onMounted} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import {router, usePage} from "@inertiajs/vue3";

export default {
    components: {AppLayout},
    props: {
        activities: Array,
        initialResponses: Object,
        initialIndex: Number,
    },
    setup(props) {
        const responses = reactive({...props.initialResponses});
        const currentChunkIndex = ref(Math.floor(props.initialIndex / 4));
        const progress = ref((props.initialIndex / props.activities.length) * 100);
        const error = ref(null);
        const refHiddenInput = ref(null);
        const loading = ref(false);

        const chunkedActivities = computed(() => {
            const chunkSize = 4;
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
                clearErrors(); // Clear errors when changing chunk
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
                loading.value = true; // Set loading state
                router.post('/activity/submit', {
                    responses: responses
                }).then(() => {
                    router.reload(); // Reload the page using Inertia
                    Object.keys(responses).forEach(key => {
                        delete responses[key];
                    });
                    currentChunkIndex.value = 0; // Reset chunk index
                    progress.value = 0; // Reset progress
                }).finally(() => {
                    loading.value = false; // Reset loading state
                }).catch(error => {
                    console.error('Submission failed:', error);
                });
            } else {
                markEmptyResponses();
            }
        };

        const updateProgress = () => {
            progress.value = ((currentChunkIndex.value + 1) / chunkedActivities.value.length) * 100;
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

        onMounted(() => {
            updateProgress();
        });

        return {
            responses,
            currentChunkIndex,
            progress,
            loading,
            chunkedActivities,
            changeChunk,
            validateResponses,
            markEmptyResponses,
            clearErrors,
            submit,
            updateProgress,
            nextChunk,
            prevChunk,
        };
    }
};
</script>

<template>
    <app-layout title="Activity Progress">
        <div class="max-w-3xl mx-auto mt-10 px-4 sm:px-0">
            <!-- Progress bar outside the form -->
            <div class="w-full bg-gray-300 rounded-full h-2 mb-6 overflow-hidden">
                <div class="h-2 bg-emerald-700 transition-all duration-300" :style="{ width: progress + '%' }"></div>
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
                                <!-- Radio buttons -->
                                <label
                                    v-for="option in ['Strongly Dislike', 'Dislike', 'Unsure', 'Like', 'Strongly Like']"
                                    :key="option" class="block mb-2">
                                    <input type="radio" :name="'response[' + activity.id + ']'" :value="option"
                                           v-model="responses[activity.id]"
                                           class="mr-2 form-radio h-4 w-4 text-emerald-800 transition duration-150 ease-in-out">
                                    {{ option }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation and submit buttons -->
            <div class="flex justify-between mt-6">
                <button v-if="currentChunkIndex > 0" @click="prevChunk"
                        class="bg-black  text-white font-semibold py-2 px-4 rounded-lg">
                    Previous
                </button>

                <button v-if="currentChunkIndex < chunkedActivities.length - 1"
                        @click="nextChunk"
                        class="bg-sky-700 hover:bg-sky-900 text-white font-semibold py-2 px-4 rounded-lg">
                    Next
                </button>

                <button v-if="currentChunkIndex === chunkedActivities.length - 1" @click="submit"
                        class="bg-emerald-800 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center">
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
