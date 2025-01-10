<template>
  <div class="relative h-full w-full flex justify-center items-center">
    <Link 
      :href="route('holland-codes.index')"
      class="relative w-full h-full flex items-center justify-center"
    >
      <!-- Animated Background Glow -->
      <div 
        class="absolute inset-0 rounded-full blur-3xl opacity-50 transition-all duration-500"
        :class="[
          isHovering ? 'scale-110 bg-gradient-to-r from-indigo-600/30 via-purple-600/30 to-pink-600/30' : 'scale-90 bg-indigo-400/20'
        ]"
      ></div>

      <!-- Cube Container -->
      <div 
        id="cube" 
        class="relative h-60 w-60 flex justify-center items-center cursor-pointer transform transition-all duration-700 animate-float"
        :class="{ 'scale-110': isHovering }"
      >
        <!-- Animated Hexagon Glow -->
        <div 
          class="hexagon absolute scale-125 transition-all duration-500"
          :class="{ 'glow-intense': isHovering }"
        ></div>

        <!-- Box Base (Back) -->
        <div class="cube back absolute top-0 left-0 h-60 w-60 scale-125 transition-all duration-500"></div>

        <!-- Animated Flaps -->
        <div 
          class="cube top absolute top-0 left-0 h-60 w-60 scale-125 transition-all duration-700 origin-top"
          :class="{ 'open-top': isHovering }"
        ></div>
        <div 
          class="cube left absolute top-0 left-0 h-60 w-60 scale-125 transition-all duration-700 origin-left"
          :class="{ 'open-left': isHovering }"
        ></div>
        <div 
          class="cube right absolute top-0 left-0 h-60 w-60 scale-125 transition-all duration-700 origin-right"
          :class="{ 'open-right': isHovering }"
        ></div>
        
        <!-- Animated Question Mark -->
        <div 
          class="absolute inset-0 flex items-center justify-center z-10 transition-all duration-700"
          :class="{ 
            'scale-110 translate-y-0 opacity-100': isHovering,
            'scale-75 translate-y-4 opacity-0': !isHovering
          }"
        >
          <svg 
            class="w-16 h-16 text-white transition-all duration-500 transform"
            :class="{ 
              'opacity-100 scale-110 rotate-12': isHovering, 
              'opacity-0': !isHovering 
            }"
            fill="currentColor" 
            viewBox="0 0 24 24"
          >
            <path d="M12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z"/>
            <path d="M12 14C11.4477 14 11 13.5523 11 13V12C11 11.4477 11.4477 11 12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 9.55228 9.55228 10 9 10C8.44772 10 8 9.55228 8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9C16 11.2091 14.2091 13 12 13V13C12 13.5523 11.5523 14 11 14H12ZM12 15C12.5523 15 13 15.4477 13 16V17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17V16C11 15.4477 11.4477 15 12 15Z"/>
          </svg>
        </div>

        <!-- Light Effect -->
        <div 
          class="absolute inset-0 bg-white/0 transition-all duration-700 rounded-full blur-xl"
          :class="{ 'bg-white/20': isHovering }"
        ></div>

        <!-- Sparkles -->
        <div 
          v-if="isHovering"
          class="absolute inset-0 pointer-events-none"
        >
          <div v-for="n in 3" :key="n" class="sparkle" :style="`--delay: ${n * 0.15}s`"></div>
        </div>
      </div>
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  isHovering: {
    type: Boolean,
    required: true
  }
})
</script>

<style scoped>
:root {
  --glow: rgba(255, 195, 26, 0.4);
}

.hexagon {
  z-index: -2;
  position: relative;
  width: 200px;
  height: 115.47px;
  background-color: var(--glow);
  margin: 57.74px 0;
  filter: blur(20px);
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.glow-intense {
  --glow: rgba(129, 140, 248, 0.8);
  filter: blur(40px);
  transform: scale(1.3);
}

.hexagon:before,
.hexagon:after {
  content: "";
  position: absolute;
  width: 0;
  border-left: 100px solid transparent;
  border-right: 100px solid transparent;
}

.hexagon:before {
  bottom: 100%;
  border-bottom: 57.74px solid var(--glow);
}

.hexagon:after {
  top: 100%;
  width: 0;
  border-top: 57.74px solid var(--glow);
}

.back {
  background-image: url("https://res.cloudinary.com/dbrwtwlwl/image/upload/v1580369339/cube/mysteryBoxBackground_2x_b2espr.png");
  background-size: cover;
  background-position: center;
  z-index: -1;
}

.top {
  background-image: url("https://res.cloudinary.com/dbrwtwlwl/image/upload/v1580369339/cube/mysteryBoxTopFlap_2x_f9cb8g.png");
  background-size: cover;
  background-position: center;
  z-index: 1;
}

.left {
  background-image: url("https://res.cloudinary.com/dbrwtwlwl/image/upload/v1580369339/cube/mysteryBoxLeftFlap_2x_y8u4gz.png");
  background-size: cover;
  background-position: center;
  z-index: 1;
}

.right {
  background-image: url("https://res.cloudinary.com/dbrwtwlwl/image/upload/v1580369339/cube/mysteryBoxRightFlap_2x_abexhh.png");
  background-size: cover;
  background-position: center;
  z-index: 1;
}

.hover-transform {
  transform: translateZ(10px) scale(1.05);
}

/* Floating Animation */
.animate-float {
  animation: float 6s ease-in-out infinite;
  transform-style: preserve-3d;
  perspective: 1000px;
}

@keyframes float {
  0%, 100% {
    transform: translateY(-8px) rotate3d(1, 1, 1, 0deg);
  }
  25% {
    transform: translateY(-4px) rotate3d(1, 1, 1, 5deg);
  }
  50% {
    transform: translateY(8px) rotate3d(1, 1, 1, 0deg);
  }
  75% {
    transform: translateY(-4px) rotate3d(1, 1, 1, -5deg);
  }
}

/* Sparkle Animation */
.sparkle {
  position: absolute;
  width: 20px;
  height: 20px;
  background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0) 70%);
  border-radius: 50%;
  animation: sparkle 2s ease-in-out infinite;
  animation-delay: var(--delay);
  opacity: 0;
  transform: scale(0);
  transition: opacity 0.3s ease;
}

.sparkle:nth-child(1) { top: 20%; left: 20%; }
.sparkle:nth-child(2) { top: 60%; left: 80%; }
.sparkle:nth-child(3) { top: 80%; left: 40%; }

@keyframes sparkle {
  0% {
    transform: scale(0) rotate(0deg);
    opacity: 0;
  }
  50% {
    transform: scale(1.5) rotate(180deg);
    opacity: 1;
  }
  100% {
    transform: scale(0) rotate(360deg);
    opacity: 0;
  }
}

/* Smooth transitions */
#cube {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
  perspective: 1000px;
  will-change: transform;
}

/* Enhanced glow effect */
#cube:hover .hexagon {
  filter: blur(30px);
  opacity: 0.9;
}

.open-top {
  transform: translateY(-20px) rotateX(-110deg) rotate3d(1, 0, 0, 10deg) !important;
}

.open-left {
  transform: translateX(-20px) rotateY(110deg) rotate3d(0, 1, 0, 10deg) !important;
}

.open-right {
  transform: translateX(20px) rotateY(-110deg) rotate3d(0, 1, 0, -10deg) !important;
}

/* Modify cube faces for 3D effect */
.cube {
  transform-style: preserve-3d;
  backface-visibility: hidden;
  perspective: 1000px;
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
  transform: scale(1.25); /* Base scale */
}

/* Default (closed) state */
.top {
  transform-origin: top;
  transform: rotateX(0) translateZ(0);
}

.left {
  transform-origin: left;
  transform: rotateY(0) translateZ(0);
}

.right {
  transform-origin: right;
  transform: rotateY(0) translateZ(0);
}

/* Open states */
.open-top {
  transform: translateY(-20px) rotateX(-110deg) rotate3d(1, 0, 0, 10deg) !important;
}

.open-left {
  transform: translateX(-20px) rotateY(110deg) rotate3d(0, 1, 0, 10deg) !important;
}

.open-right {
  transform: translateX(20px) rotateY(-110deg) rotate3d(0, 1, 0, -10deg) !important;
}

/* Base layer */
.back {
  transform: translateZ(-10px);
}

/* Modify glow effect */
.hexagon {
  z-index: -2;
  position: relative;
  width: 200px;
  height: 115.47px;
  background-color: var(--glow);
  margin: 57.74px 0;
  filter: blur(20px);
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.glow-intense {
  --glow: rgba(129, 140, 248, 0.8);
  filter: blur(40px);
  transform: scale(1.3);
}

/* Smooth container transitions */
#cube {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
  perspective: 1000px;
}

/* Question mark transitions */
.scale-75 {
  transform: scale(0.75) translateY(1rem);
}

.scale-110 {
  transform: scale(1.1) translateY(0);
}

/* Add transition for light effect */
.bg-white\/0 {
  transition: background-color 0.7s ease;
}

.bg-white\/20 {
  transition: background-color 0.7s ease;
}
</style>
