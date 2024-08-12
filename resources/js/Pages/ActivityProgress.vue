<script setup>
import {ref, computed} from 'vue';
import {usePage, router} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    activities: Array,
    initialResponses: Object,
    initialIndex: Number,
});

const responses = ref(props.initialResponses);
const currentChunkIndex = ref(Math.floor(props.initialIndex / 4)); // Initial chunk index
const progress = ref((props.initialIndex / props.activities.length) * 100);
const error = ref(null);
const refHiddenInput = ref(null);
const $page = usePage();

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
    return currentChunk.every(activity => responses.value[activity.id]);
};

const markEmptyResponses = () => {
    const currentChunk = chunkedActivities.value[currentChunkIndex.value];
    currentChunk.forEach(activity => {
        if (!responses.value[activity.id]) {
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
        $page.props.loading = true; // Set loading state
        router.post('/activity/submit', {
            responses: responses.value
        }).then(() => {
            // Additional success logic if needed
            $page.props.loading = false; // Reset loading state
        }).catch(error => {
            console.error('Submission failed:', error);
            $page.props.loading = false; // Reset loading state on error
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
                    <span v-if="!$page.props.loading">Submit</span>
                    <div v-if="$page.props.loading" class="flex items-center">
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
