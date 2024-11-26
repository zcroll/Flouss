<template>
  
  <section class="Discovery" aria-live="polite" aria-atomic="true" aria-relevant="text" role="presentation">
    <div class="DiscoveryButton__inner">
      <div class="DiscoveryConfetti" id="dicovery-confetti" data-testid="dicovery-confetti" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 614 614" width="614" height="614"
          preserveAspectRatio="xMidYMid slice"
          style="width: 100%; height: 100%; transform: translate3d(0px, 0px, 0px); content-visibility: visible;">
          <defs>
            <clipPath id="__lottie_element_216">
              <rect width="614" height="614" x="0" y="0"></rect>
            </clipPath>
          </defs>
          <g clip-path="url(#__lottie_element_216)">
            <g transform="matrix(0.28186464309692383,-0.4369809031486511,0.4369809031486511,0.28186464309692383,414.5985107421875,162.72265625)"
              opacity="0.00019470564538920599" style="display: none;">
              <g opacity="1" transform="matrix(1,0,0,1,-115.88700103759766,-116.55899810791016)">
                <path fill="rgb(113,73,255)" fill-opacity="1"
                  d=" M17.113500595092773,-16.441499710083008 C17.113500595092773,-16.441499710083008 17.113500595092773,16.441499710083008 17.113500595092773,16.441499710083008 C17.113500595092773,16.441499710083008 -17.113500595092773,16.441499710083008 -17.113500595092773,16.441499710083008 C-17.113500595092773,16.441499710083008 -17.113500595092773,-16.441499710083008 -17.113500595092773,-16.441499710083008 C-17.113500595092773,-16.441499710083008 17.113500595092773,-16.441499710083008 17.113500595092773,-16.441499710083008z">
                </path>
                <path stroke-linecap="butt" stroke-linejoin="miter" fill-opacity="0" stroke-miterlimit="4"
                  stroke="rgb(255,255,255)" stroke-opacity="0" stroke-width="2" d="M0 0"></path>
              </g>
            </g>
          </g>
        </svg>
      </div>
      <button class="DiscoveryButton DiscoveryButton--degree-match DiscoveryButton--is-alive"
        data-dialog-target="dialog-discovery"
        aria-label="We've made a new discovery: Click here for your degree matches!. "
        aria-owns="#dialog-discovery" tabindex="0"
        :style="{ backgroundImage: `url(${archetypeDiscovery.image_url})` }" @click="showArchetype = true">
        <h3 class="DiscoveryButton__title" aria-hidden="true">Click here for your degree matches!</h3>
        <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up"
          class="svg-inline--fa fa-chevron-up DiscoveryButton__arrow" role="img"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
          <path fill="currentColor"
            d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z">
          </path>
        </svg>
        <div class="DiscoveryButton__dismiss" tabindex="0" aria-label="Dismiss this discovery">
          <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="xmark"
            class="svg-inline--fa fa-xmark " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
            <path fill="currentColor"
              d="M315.3 411.3c-6.253 6.253-16.37 6.253-22.63 0L160 278.6l-132.7 132.7c-6.253 6.253-16.37 6.253-22.63 0c-6.253-6.253-6.253-16.37 0-22.63L137.4 256L4.69 123.3c-6.253-6.253-6.253-16.37 0-22.63c6.253-6.253 16.37-6.253 22.63 0L160 233.4l132.7-132.7c6.253-6.253 16.37-6.253 22.63 0c6.253 6.253 6.253 16.37 0 22.63L182.6 256l132.7 132.7C321.6 394.9 321.6 405.1 315.3 411.3z">
            </path>
          </svg>
        </div>
      </button>
    </div>
    <div id="discovery-dialog-container" class="yodel dialog-container Dialog-container">
      <Archetype v-if="showArchetype" :archetype-discovery="archetypeDiscovery" @close="handleClose" />
    </div>
  </section>
</template>

<script setup>
import Archetype from '../helpers/Archetype.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
  archetypeDiscovery: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['close']);

const showArchetype = ref(false);

const handleClose = () => {
  showArchetype.value = false;
  emit('close');
};

onMounted(() => {
  showArchetype.value = true;
  console.log('Archetype Discovery:', props.archetypeDiscovery);
});
</script>

<style scoped>
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/assessments.ba16abcb0f5b.css')
</style>