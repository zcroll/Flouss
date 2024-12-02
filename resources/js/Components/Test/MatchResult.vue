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
                  class="HorizontalCard HorizontalCard--wide"
                  aria-labelledby="HorizontalCard-undefined"
                >
                  <img
                    class="HorizontalCard-image"
                    src="https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,h_160,q_80,w_160/v1727983767/kawnatp284eo1akskye7.jpg"
                    alt=""
                    role="presentation"
                    aria-hidden="true"
                  />
                  <div class="HorizontalCard-wrap">
                    <div
                      id="HorizontalCard-undefined"
                      class="HorizontalCard-name"
                    >
                      Game Design
                    </div>
                    <div class="Stars" aria-label="5 out of 5">
                      <svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="star"
                        class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        data-testid="star-full"
                      >
                        <path
                          fill="currentColor"
                          d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"
                        ></path></svg
                      ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="star"
                        class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        data-testid="star-full"
                      >
                        <path
                          fill="currentColor"
                          d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"
                        ></path></svg
                      ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="star"
                        class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        data-testid="star-full"
                      >
                        <path
                          fill="currentColor"
                          d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"
                        ></path></svg
                      ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="star"
                        class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        data-testid="star-full"
                      >
                        <path
                          fill="currentColor"
                          d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"
                        ></path></svg
                      ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="star"
                        class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        data-testid="star-full"
                      >
                        <path
                          fill="currentColor"
                          d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"
                        ></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </ul>
            </div>
            <div class="Discovery__related__feedback">
              <div
                class="DiscoveryFeedback"
                aria-live="assertive"
                aria-atomic="true"
                aria-relevant="text"
              >
                <span tabindex="0">Was this interesting?</span
                ><button
                  class="DiscoveryFeedback-button alans-butt--grey"
                  tabindex="0"
                  aria-label="Option one: this discovery was interesting. Click here to provide feedback."
                  title="interesting"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="thumbs-up"
                    class="svg-inline--fa fa-thumbs-up DiscoveryFeedback-button__icon"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                  >
                    <path
                      fill="currentColor"
                      d="M128 447.1V223.1c0-17.67-14.33-31.1-32-31.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64C113.7 479.1 128 465.6 128 447.1zM512 224.1c0-26.5-21.48-47.98-48-47.98h-146.5c22.77-37.91 34.52-80.88 34.52-96.02C352 56.52 333.5 32 302.5 32c-63.13 0-26.36 76.15-108.2 141.6L178 186.6C166.2 196.1 160.2 210 160.1 224c-.0234 .0234 0 0 0 0L160 384c0 15.1 7.113 29.33 19.2 38.39l34.14 25.59C241 468.8 274.7 480 309.3 480H368c26.52 0 48-21.47 48-47.98c0-3.635-.4805-7.143-1.246-10.55C434 415.2 448 397.4 448 376c0-9.148-2.697-17.61-7.139-24.88C463.1 347 480 327.5 480 304.1c0-12.5-4.893-23.78-12.72-32.32C492.2 270.1 512 249.5 512 224.1z"
                    ></path>
                  </svg></button
                ><button
                  class="DiscoveryFeedback-button alans-butt--grey"
                  tabindex="0"
                  aria-label="Option two: this discovery was not interesting. Click here to provide feedback."
                  title="not interesting"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="thumbs-down"
                    class="svg-inline--fa fa-thumbs-down DiscoveryFeedback-button__icon"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                  >
                    <path
                      fill="currentColor"
                      d="M96 32.04H32c-17.67 0-32 14.32-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64c17.67 0 32-14.33 32-31.1V64.03C128 46.36 113.7 32.04 96 32.04zM467.3 240.2C475.1 231.7 480 220.4 480 207.9c0-23.47-16.87-42.92-39.14-47.09C445.3 153.6 448 145.1 448 135.1c0-21.32-14-39.18-33.25-45.43C415.5 87.12 416 83.61 416 79.98C416 53.47 394.5 32 368 32h-58.69c-34.61 0-68.28 11.22-95.97 31.98L179.2 89.57C167.1 98.63 160 112.9 160 127.1l.1074 160c0 0-.0234-.0234 0 0c.0703 13.99 6.123 27.94 17.91 37.36l16.3 13.03C276.2 403.9 239.4 480 302.5 480c30.96 0 49.47-24.52 49.47-48.11c0-15.15-11.76-58.12-34.52-96.02H464c26.52 0 48-21.47 48-47.98C512 262.5 492.2 241.9 467.3 240.2z"
                    ></path>
                  </svg>
                </button>
              </div>
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
  matchDiscovery: {
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


