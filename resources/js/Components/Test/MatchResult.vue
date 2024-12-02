<template>
<div class="flex justify-center items-center min-h-screen">
  <div
    role="document"
    class="dialog-element Dialog Dialog--Discovery Dialog--Discovery--degree-match Dialog--Discovery--share-dialog-closed"
  >
    <div class="dialog-inner Dialog-wrap Dialog--DiscoveryDialog">
    <button
      class="dialog-close Dialog-close"
      title="Close"
      type="button"
      aria-label="Close this dialog window"
      tabindex="-1"
      @click="handleClose"
    >
        <svg
          aria-hidden="true"
          focusable="false"
          data-prefix="fal"
          data-icon="chevron-down"
          class="svg-inline--fa fa-chevron-down Dialog-close-icon"
          role="img"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 448 512"
        >
          <path
            fill="currentColor"
            d="M4.251 181.1C7.392 177.7 11.69 175.1 16 175.1c3.891 0 7.781 1.406 10.86 4.25l197.1 181.1l197.1-181.1c6.5-6 16.64-5.625 22.61 .9062c6 6.5 5.594 16.59-.8906 22.59l-208 192c-6.156 5.688-15.56 5.688-21.72 0l-208-192C-1.343 197.7-1.749 187.6 4.251 181.1z"
          ></path>
        </svg>
      </button>
      <div>
        <p
          role="heading"
          aria-level="1"
          id="dialog-discovery-title"
          class="dialog-title Dialog-title"
        >
          Here are your matches so far
        </p>
        <div class="Dialog-wrap__background">
          <div class="Dialog-discovery">
            <div class="Dialog-discovery__actions">
              <button
                class="Dialog-discovery__share-button alans-butt--grey"
                tabindex="0"
                aria-label="Click here to share your discovery on social media."
              >
                <svg
                  aria-hidden="true"
                  focusable="false"
                  data-prefix="fas"
                  data-icon="share-from-square"
                  class="svg-inline--fa fa-share-from-square Dialog-discovery__share-button__icon"
                  role="img"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 576 512"
                >
                  <path
                    fill="currentColor"
                    d="M568.9 143.5l-150.9-138.2C404.8-6.773 384 3.039 384 21.84V96C241.2 97.63 128 126.1 128 260.6c0 54.3 35.2 108.1 74.08 136.2c12.14 8.781 29.42-2.238 24.94-16.46C186.7 252.2 256 224 384 223.1v74.2c0 18.82 20.84 28.59 34.02 16.51l150.9-138.2C578.4 167.8 578.4 152.2 568.9 143.5zM416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z"
                  ></path>
                </svg>
                Share</button
                ><button
                  class="Dialog-discovery__extraclose-button alans-butt--grey"
                  tabindex="0"
                  aria-label="Click here to close the dialog and to continue your assessment."
                  @click="handleClose"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="far"
                    data-icon="xmark" 
                    class="svg-inline--fa fa-xmark Dialog-discovery__extraclose-button__icon"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 320 512"
                  >
                  <path
                    fill="currentColor"
                    d="M312.1 375c9.369 9.369 9.369 24.57 0 33.94s-24.57 9.369-33.94 0L160 289.9l-119 119c-9.369 9.369-24.57 9.369-33.94 0s-9.369-24.57 0-33.94L126.1 256L7.027 136.1c-9.369-9.369-9.369-24.57 0-33.94s24.57-9.369 33.94 0L160 222.1l119-119c9.369-9.369 24.57-9.369 33.94 0s9.369 24.57 0 33.94L193.9 256L312.1 375z"
                    class=""
                  ></path>
                </svg>
              </button>
            </div>
            <h1
              class="Discovery__title"
              tabindex="0"
              aria-label="Click here for your degree matches!. "
            >
              Here are your matches so far
            </h1>
          </div>
          <div class="Discovery__related">
            <div class="Discovery__related__readmore">
              <p class="Discovery__related__description" tabindex="0">
                Based on your personality and interests, these are our top
                education picks for you so far. Get more star ratings when you
                finish the assessment!
              </p>
            </div>
            <div class="Discovery__related__content">
            <ul class="Discovery__MemberCareerMatches">
              <div
                v-for="job in jobMatching"
                :key="job.id" 
                class="HorizontalCard HorizontalCard--wide"
                :aria-labelledby="`HorizontalCard-${job.id}`"
              >
                <img
                  class="HorizontalCard-image"
                  :src="job.image"
                  alt=""
                  role="presentation"
                  aria-hidden="true"
                />
                <div class="HorizontalCard-wrap">
                  <div
                    :id="`HorizontalCard-${job.id}`"
                    class="HorizontalCard-name"
                  >
                    {{ job.name }}
                  </div>
                  
                </div>
              </div>
            </ul>
          </div>
          <div class="Discovery__related__feedback">
            
              <button
                class="Dialog__back-button alans-butt--grey"
                tabindex="0"
                aria-label="Click here to close the dialog and to continue your assessment."
                @click="handleClose"
              >
                Continue assessment
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  jobMatching: {
    type: Object,
  },
  showMatch: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["close"]);

const handleClose = () => {
  emit("close");
};

const handleFeedback = (type) => {
  // Handle feedback logic
};

const handleShare = () => {
  // Handle share logic
};
</script>


