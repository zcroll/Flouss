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
                <h2 class="text-2xl font-bold text-white">Would you like to...</h2>
            </div>
            
            <div class="mb-4 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: `${progress}%` }"></div>
            </div>
            
            <div ref="activityContainer">
                <h3 class="text-xl font-semibold mb-4 text-white">{{ activity.name }}</h3>
                <div class="space-y-2">
                    <button 
                        v-for="option in options" 
                        :key="option.value"
                        @click="submitAnswer(option.value)"
                        class="w-full text-left py-3 px-4 rounded-md flex items-center bg-gray-800 text-white hover:bg-gray-700 transition-all duration-200"
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
                        class="w-full py-2 px-4 bg-gray-600 text-white rounded-md hover:bg-gray-500 transition-all duration-200"
                        :disabled="form.processing"
                    >
                        Skip this question
                    </button>
                </div>
            </div>

            <div ref="nextActivityContainer" class="hidden">
                <!-- This will be populated with the next activity -->
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
</style>
