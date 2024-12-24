<template>
    <div class="avatar-container"
         @mouseenter="playAnimation"
         @mouseleave="pauseAnimation">
        <div ref="lottieContainer" class="avatar-image">
            <DotLottieVue 
                ref="playerRef"
                :src="animationPath"
                :autoplay="false"
                :loop="true"
                :style="{ width: '100%', height: '100%', transform: 'scale(1.1)' }"
            />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { DotLottieVue } from '@lottiefiles/dotlottie-vue'

const props = defineProps({
    archetype: {
        type: String,
        required: true
    }
});

const playerRef = ref(null);
let dotLottieInstance = null;

const animationPath = computed(() => {
    return '/personality_animations/adventurer_animation.json';
});

onMounted(() => {
    if (playerRef.value) {
        dotLottieInstance = playerRef.value.getDotLottieInstance();
        
        dotLottieInstance.addEventListener('play', () => {
            console.log(`${props.archetype} animation started playing`);
        });
        
        dotLottieInstance.addEventListener('error', (error) => {
            console.error(`${props.archetype} animation error:`, error);
            handleAnimationError();
        });
    }
});

const handleAnimationError = async () => {
    try {
        console.log('Loading fallback animation...');
        if (dotLottieInstance) {
            await dotLottieInstance.load('/personality_animations/architect_animation.json');
        }
    } catch (error) {
        console.error('Error loading fallback animation:', error);
    }
};

const playAnimation = async () => {
    try {
        if (dotLottieInstance) {
            await dotLottieInstance.play();
        }
    } catch (error) {
        console.error('Error playing animation:', error);
    }
}

const pauseAnimation = async () => {
    try {
        if (dotLottieInstance) {
            await dotLottieInstance.pause();
            await dotLottieInstance.setFrame(0);
        }
    } catch (error) {
        console.error('Error pausing animation:', error);
    }
}
</script>

<style scoped>
.avatar-container {
    position: relative;
    width: 100%;
    height: 100%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-image {
    width: 100%;
    height: 100%;
    transition: transform 0.3s ease;
}

.avatar-container:hover .avatar-image {
    transform: scale(1.05);
}
</style> 