<script>
import { ref, reactive, computed, onMounted } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: { AppLayout },
    props: {
        activities: Array,
        initialResponses: Object,
        initialIndex: Number,
    },
    setup(props) {
        const responses = reactive({ ...props.initialResponses });
        const currentChunkIndex = ref(Math.floor(props.initialIndex / 4));
        const progressFra = ref({ current: 1, total: 1 });
        const progressBar = ref(0);
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

        onMounted(() => {
            updateProgress();
            fractionProgress();
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
