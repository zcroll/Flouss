<template>
    <img
        src="/icons8-arrow-100.png"
        alt="Back to Top"
        class="back-to-top"
        v-if="showBackToTop"
        @click="scrollToTop"
    />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const showBackToTop = ref(false);

const handleScroll = () => {
    showBackToTop.value = window.pageYOffset > 300;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px; /* Adjust size as needed */
    height: 50px; /* Adjust size as needed */
    background-color: rgba(219, 73, 43, 0.8); /* Semi-transparent background */
    border-radius: 50%; /* Circular shape */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition */
}

.back-to-top:hover {
    background-color: rgba(219, 73, 43, 1); /* Solid background on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
}
</style> 