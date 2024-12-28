<template>
    <Button variant="ghost" size="icon" :disabled="isLoading" @click="handleFavoriteClick" :class="[
        showLabel ? 'px-4 py-2' : 'p-2',
        localIsFavorited ? 'text-red-500' : 'text-gray-400'
    ]">
        <HeartIcon :class="[
            'w-5 h-5 transition-colors duration-300',
            localIsFavorited ? 'fill-current' : ''
        ]" />
        <span v-if="showLabel" class="ml-2 text-sm font-medium">
            {{ localIsFavorited ? 'Favorited' : 'Add to Favorites' }}
        </span>
    </Button>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { HeartIcon } from '@heroicons/vue/24/outline';
import { useFavorites } from '@/composables/useFavorites';
import { Button } from '@/Components/ui/button';
import { useToast } from "@/Components/ui/toast/use-toast";
import { useToastConfig } from "@/Components/ui/toast/toast-config";
import { useThemeStore } from '@/stores/theme/themeStore';

const props = defineProps({
    modelId: {
        type: Number,
        required: true
    },
    modelType: {
        type: String,
        required: true
    },
    initialIsFavorited: {
        type: Boolean,
        default: false
    },
    showLabel: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['favorite-toggled']);
const themeStore = useThemeStore();
const { toast } = useToast();
const { toggleFavorite } = useFavorites();
const localIsFavorited = ref(props.initialIsFavorited);
const isLoading = ref(false);

const toastConfig = useToastConfig();

const getToastConfig = (type) => ({
    ...toastConfig,
    class: `${toastConfig.class} ${type === 'error'
            ? themeStore.isDarkMode
                ? `bg-destructive/10 border-${themeStore.currentTheme.border}/20`
                : 'bg-destructive/20'
            : themeStore.isDarkMode
                ? `bg-${themeStore.currentTheme.background.dark}/10 border-${themeStore.currentTheme.border}/20`
                : `bg-${themeStore.currentTheme.background.light}/20`
        }`,
});

watch(() => props.initialIsFavorited, (newValue) => {
    localIsFavorited.value = newValue;
});

const handleFavoriteClick = async () => {
    if (isLoading.value) return;

    isLoading.value = true;
    const newState = !localIsFavorited.value;

    try {
        const success = await toggleFavorite(props.modelId, props.modelType, newState);
        if (success) {
            localIsFavorited.value = newState;
            emit('favorite-toggled', newState);
        }
    } catch (error) {
        console.error('Error toggling favorite:', error);
        toast({
            title: "Error",
            description: "Failed to toggle favorite status",
            variant: "destructive",
            ...getToastConfig('error'),
        });
    } finally {
        isLoading.value = false;
    }
};
</script>