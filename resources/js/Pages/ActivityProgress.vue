<script>
import { ref, computed, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import { animate, spring } from 'motion';

export default {
    components: {
        AppLayout,
        Link,
    },
    props: {
        activity: Object,
        totalQuestions: Number,
        currentIndex: Number,
        canGoBack: Boolean,
    },
    setup(props) {
        const form = useForm({
            activityId: props.activity.id,
            answer: '',
        });

        const progress = computed(() => (props.currentIndex / props.totalQuestions) * 100);

        const options = [
            { key: '1', value: 'hate', label: 'Hate it' },
            { key: '2', value: 'dislike', label: 'Dislike it' },
            { key: '3', value: 'neutral', label: 'Neutral' },
            { key: '4', value: 'like', label: 'Like it' },
            { key: '5', value: 'love', label: 'Love it' },
        ];

        const activityContainer = ref(null);
        const nextActivityContainer = ref(null);

        const submitAnswer = (answer) => {
            form.answer = answer;

            if (props.currentIndex === props.totalQuestions - 1) {
                isCalculating.value = true;
            }

            // Animate current activity out (up)
            animate(activityContainer.value, { opacity: 0, y: -100 }, { duration: 0.2, easing: spring() })
                .finished.then(() => {
                    // Animate next activity in (from bottom)
                    animate(nextActivityContainer.value, { opacity: [0, 1], y: [100, 0] }, { duration: 0.2, easing: spring() });
                });

            // Post the answer without waiting for the animation to finish
            form.post(route('activity.submit-answer'), {
                preserveState: false,
                preserveScroll: true,
            });
        };

        const goToPrevious = () => {
            form.get(route('activity.previous'));
        };

        const skipQuestion = () => {
            form.answer = 'skipped';

            // Animate current activity out (up)
            animate(activityContainer.value, { opacity: 0, y: -100 }, { duration: 0.2, easing: spring() })
                .finished.then(() => {
                    // Animate next activity in (from bottom)
                    animate(nextActivityContainer.value, { opacity: [0, 1], y: [100, 0] }, { duration: 0.2, easing: spring() });
                });

            // Post the skipped answer
            form.post(route('activity.submit-answer'), {
                preserveState: false,
                preserveScroll: true,
            });
        };

        const isCalculating = ref(false);

        onMounted(() => {
            animate(activityContainer.value, { opacity: [0, 1], y: [100, 0] }, { duration: 0.2, easing: spring() });
        });

        return {
            form,
            progress,
            options,
            submitAnswer,
            goToPrevious,
            activityContainer,
            nextActivityContainer,
            skipQuestion,
            isCalculating,
        };
    }
};
</script>

<template>
    <app-layout title="Activity Progress">
        <div class="max-w-3xl mx-auto mt-10 px-4 sm:px-0">
            <div class="flex justify-between items-center mb-6">
                <button
                    v-if="currentIndex > 0"
                    @click="goToPrevious"
                    class="text-white hover:text-gray-300 focus:outline-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h2 class="text-2xl  text-gray-800 trait-type">Would you like to...</h2>
            </div>

            <div class="mb-4 bg-gray-200 rounded-full h-3.5 dark:bg-gray-200">
                <div class="bg-emerald-900 h-3.5 rounded-full" :style="{ width: `${progress}%` }"></div>
            </div>

            <div ref="activityContainer">
                <h3 class="text-3xl font-black mt-10 mb-10 text-gray-800 trait-type">{{ activity.name }}</h3>
                <div class="space-y-2">
                    <button
                        v-for="option in options"
                        :key="option.value"
                        @click="submitAnswer(option.value)"
                        class="w-full text-left py-3 px-4 rounded-md flex items-center bg-black text-white hover:bg-emerald-900 transition-all duration-200"
                        :disabled="form.processing"
                    >
                        <span class="mr-2 w-6 h-6 flex items-center justify-center border border-white rounded-md">{{ option.key }}</span>
                        {{ option.label }}
                    </button>
                </div>

                <!-- Add Skip button -->
                <div class="mt-4">
                    <button
                        @click="skipQuestion"
                        class="w-full py-2 px-4 bg-gray-100 text-black rounded-md "
                        :disabled="form.processing"
                    >
                        Skip this question
                    </button>
                </div>
            </div>

            <div ref="nextActivityContainer" class="hidden">
                <!-- This will be populated with the next activity -->
            </div>

            <div v-if="isCalculating" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="text-center">
                    <div class="spinner mb-4"></div>
                    <p class="text-white text-xl">We're calculating your score...</p>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
body {
    background-color: #2c3e50;
    color: #0cb6e0;
    font-family: 'Arial', sans-serif;
    background-color: black;
}
.trait-type{
    box-sizing: border-box;
    background-color: transparent;
    text-decoration: none;
    transition:
        color 0.2s ease-in-out, border-bottom 0.2s ease-in-out;
    border-bottom: 0px;
    color: rgb(36, 36, 36);
    font-weight: 300;
    font-family:
        aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}

.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #ffffff;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
