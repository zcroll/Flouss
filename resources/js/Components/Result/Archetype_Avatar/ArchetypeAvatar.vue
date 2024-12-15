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
                :style="{ width: '120%', height: '120%' }"
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

// Map archetypes to their corresponding animation files
const animationMapping = {
    // Advocate animation group (Blue theme)
    // Advocate animation group
    'advocat': 'advocate',
    'humanitarian': 'mediator',
    'mentor': 'advocate',
    'supporter': 'consul',
    'caregiver': 'advocate',
    'protector': 'protagonist',
    
    // Architect animation group
    'architect': 'architect', 
    'mastermind': 'architect',
    'researcher': 'campaigner',
    'technician': 'architect',
    'builder': 'virtuoso',
    'groundbreaker': 'architect',
    
    // Commander animation group
    'captain': 'protagonist',
    'kingpin': 'protagonist',
    'strategist': 'commander',
    'anchor': 'commander',
    
    // Adventurer animation group
    'explorer': 'adventurer',
    'maverick': 'debater',
    
    // Entertainer animation group
    'composer': 'entertainer',
    'designer': 'entertainer',
    'creator': 'entertainer',
    
    // Entrepreneur animation group
    'producer': 'adventurer',
    'innovator': 'entrepreneur',
    'luminary': 'entertainer',
    
    // Logician animation group
    'philosopher': 'logistician',
    'scholar': 'logician',
    
    // Protagonist animation group
    'visionary': 'protagonist',
    'enthusiast': 'protagonist',
    
    // Virtuoso animation group
    'inventor': 'virtuoso',
    'guardian': 'defender'
};

const animationPath = computed(() => {
    // Convert archetype to lowercase for case-insensitive matching
    const archetypeKey = props.archetype.toLowerCase();
    const animationType = animationMapping[archetypeKey];
    
    // If no mapping found, use the first animation as default
    if (!animationType) {
        console.warn(`No animation mapping found for archetype: ${props.archetype}`);
        return '/personality_animations/advocate_animation.json';
    }
    
    return `/personality_animations/${animationType}_animation.json`;
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
            await dotLottieInstance.load('/personality_animations/advocate_animation.json');
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