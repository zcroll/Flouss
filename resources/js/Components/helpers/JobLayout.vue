<template>
    <div class="w-4/4 flex-col bg-white border-e-2 border-b-2 border-l-2 border-purple-950 rounded-2xl p-5">
        <div v-if="items && items.length > 2">
            <div v-for="item in items" :key="item.element_id" class="">
                <h3 class="p-2 text-2xl font-extrabold font-serif">{{ item.element_name }}</h3>
                <p class="pl-3 font-serif font-medium ">{{ item.description }}</p>
            </div>
        </div>
        <div v-else>
            <p class="text-xl font-sans"> Ooops ... Nothing available!</p>
        </div>
    </div>
</template>

<script setup>
import {defineProps} from "vue";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    }
});
</script>

<style scoped>
/* Add your styles here */
</style>
