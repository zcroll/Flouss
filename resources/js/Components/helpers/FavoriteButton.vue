<template>
    <button 
        @click="toggleFavorite" 
        :class="{ 'text-red-500': isFavorited }"
        class="flex items-center space-x-2 focus:outline-none"
    >
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-6 w-6" 
             :class="{ 'fill-current': isFavorited, 'stroke-current': !isFavorited }"
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    modelId: {
        type: Number,
        required: true
    },
    modelType: {
        type: String,
        required: true,
        validator: value => ['career', 'degree'].includes(value)
    },
    initialIsFavorited: {
        type: Boolean,
        default: false
    },
    showLabel: {
        type: Boolean,
        default: true
    }
})

const isFavorited = ref(props.initialIsFavorited)

// Watch for changes in initialIsFavorited prop
watch(() => props.initialIsFavorited, (newValue) => {
    isFavorited.value = newValue
})

const toggleFavorite = async () => {
    try {
         router.post('/favorites', {
            favoritable_id: props.modelId,
            favoritable_type: props.modelType
        })
        isFavorited.value = !isFavorited.value
    } catch (error) {
        console.error('Error toggling favorite:', error)
    }
}
</script>