<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import {router} from "@inertiajs/vue3";

export default {
    components: { AppLayout },
    props: {
        activities: Array,
        initialResponses: Object,
        initialIndex: Number,
    },
    data() {
        return {
            responses: this.initialResponses,
            currentActivityIndex: this.initialIndex,
            progress: (this.initialIndex / this.activities.length) * 100,
        };
    },
    methods: {
        changeActivity(index) {
            if (index >= 0 && index < this.activities.length) {
                this.currentActivityIndex = index;
                this.updateProgress();
            }
        },
        submit() {
            // Make sure to post responses data to the server
            router.post('/activity', {
                responses: this.responses
            });
        },
        updateProgress() {
            this.progress = ((this.currentActivityIndex + 1) / this.activities.length) * 100;
        },
    },
};
</script>


<template>
    <app-layout title="Activity Progress">
        <div class="max-w-3xl mx-auto mt-10">
            <!-- Progress bar outside the form -->
            <div class="w-full bg-gray-300 rounded-full h-6 mb-6 overflow-hidden">
                <div class="h-6 bg-green-500 transition-all duration-300" :style="{ width: progress + '%' }"></div>
            </div>

            <!-- Activity Display -->
            <div v-for="(activity, index) in activities" :key="activity.id">
                <div v-if="currentActivityIndex === index" class="p-6 mb-6 bg-white rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">{{ activity.name }}</h2>
                    <div class="mb-4">
                        <!-- Radio buttons -->
                        <label v-for="option in ['Strongly Dislike', 'Dislike', 'Unsure', 'Like', 'Strongly Like']"
                            :key="option" class="block mb-2">
                            <input type="radio" :name="'response[' + activity.id + ']'" :value="option"
                                v-model="responses[activity.id]" class="mr-2">
                            {{ option }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button v-if="currentActivityIndex > 0" @click="changeActivity(currentActivityIndex - 1)"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">
                    Previous
                </button>
                <button v-if="currentActivityIndex < activities.length - 1"
                    @click="changeActivity(currentActivityIndex + 1)"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    Next
                </button>
                <button v-if="currentActivityIndex === activities.length - 1" @click="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg">
                    Submit
                </button>
            </div>
        </div>
    </app-layout>
</template>



<style scoped>

</style>
