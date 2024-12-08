<template>
    <div class="avatar-container"
         @mouseenter="playAnimation"
         @mouseleave="pauseAnimation">
        <div ref="lottieContainer" class="avatar-image">
            <DotLottieVue 
                ref="playerRef"
                :src="'/personality_animations/consul_animation.json'" 
                :autoplay="false"
                :loop="true"
                :style="{ width: '120%', height: '120%' }"
            />
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import { DotLottieVue } from '@lottiefiles/dotlottie-vue'

const playerRef = ref(null);
let dotLottieInstance = null;

onMounted(() => {
    if (playerRef.value) {
        dotLottieInstance = playerRef.value.getDotLottieInstance();
        
        // Add event listeners
        dotLottieInstance.addEventListener('play', () => {
            console.log('Animation started playing');
        });
        
        dotLottieInstance.addEventListener('pause', () => {
            console.log('Animation paused');
        });
        
        dotLottieInstance.addEventListener('frame', ({ currentFrame }) => {
            console.log('Current frame:', currentFrame);
        });
        
        dotLottieInstance.addEventListener('error', (error) => {
            console.error('Animation error:', error);
        });
    }
});

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
}

.avatar-image {
    width: 90%;
    height: 90%;
    transition: transform 0.3s ease;
}

.avatar-container:hover .avatar-image {
    transform: scale(1.1);
}
</style>
