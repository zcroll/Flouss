<template>
  <div id="welcome-screen" class="Welcome Welcome--default" aria-atomic="true" aria-live="assertive" tabindex="0">
    <div>
      <div class="Welcome__intro">
        <div>
          <h1 class="Welcome__intro__title">Welcome back!</h1>
          <h2><span class="sr-only">To get started, press the Enter or Return key</span></h2>
          <div class="Welcome__grid">
            <div>
              <div class="Welcome__grid__heading">
                <span>4.5</span>
                <div class="Stars" aria-label="4.5 out of 5">
                  <svg v-for="i in 4" :key="i" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star" class="svg-inline--fa fa-star fa-fw fa-sm Stars__star Stars__star--full" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-testid="star-full">
                    <path fill="currentColor" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                  </svg>
                  <span class="Stars__star Stars__star--half fa-layers fa-fw fa-sm" data-testid="star-half">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star" class="svg-inline--fa fa-star Stars__star--empty" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                      <path fill="currentColor" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                    </svg>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star-half" class="svg-inline--fa fa-star-half" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                      <path fill="currentColor" d="M288 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.995 275.8 .0131 287.1-.0391L288 439.8zM433.2 512C432.1 512.1 431 512.1 429.9 512H433.2z"></path>
                    </svg>
                  </span>
                </div>
              </div>
              <span class="Welcome__grid__sub">1842 ratings</span>
            </div>
            <div>
              <div class="Welcome__grid__heading"><span aria-label="This test will take about "></span>30 minutes</div>
              <span class="Welcome__grid__sub">Career Test</span>
            </div>
            <div>
              <div class="Welcome__grid__heading">Unlimited</div>
              <span class="Welcome__grid__sub">Possibilities</span>
            </div>
          </div>
          <div class="Welcome__action">
            <button id="welcome-enter-button" class="alans-butt--grey js-begin-assessment" @click="$emit('continue')">Continue assessment</button>
          </div>
          <div class="Welcome__offering">
            <div class="Tiles Tiles--mouseup Tiles--mousesrelease Tiles--show-arrows-always">
              <div class="Tiles__tile-list" style="--displayWidth: 760px;" data-testid="tile-list">
                <div v-for="(card, index) in cards" :key="index"
                     class="Welcome__card"
                     :class="[`Welcome__card--type-a`, `Welcome__card--variant-${card.variant}`]"
                     :id="card.variant"
                     :data-testid="card.variant"
                     tabindex="0">
                  <div class="sr-only">
                    <h4>{{ card.title }}</h4>
                    <p>{{ card.description }}</p>
                  </div>
                  <picture>
                    <source :data-srcset="card.mobileSrcset" media="(max-width: 600px)">
                    <source :data-srcset="card.mobileWebpSrcset" type="image/webp" media="(max-width: 600px)">
                    <source :data-srcset="card.webpSrcset" type="image/webp">
                    <img :srcset="card.srcset" :alt="card.title" aria-hidden="true" role="presentation">
                  </picture>
                </div>
              </div>
              <div class="Tiles__tile-buttons">
                <button class="Tiles__tile-button Tiles__tile-button--left" aria-label="Previous" tabindex="-1" @click="previousCard">
                  <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-left" class="svg-inline--fa fa-chevron-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path fill="currentColor" d="M234.8 36.25c3.438 3.141 5.156 7.438 5.156 11.75c0 3.891-1.406 7.781-4.25 10.86L53.77 256l181.1 197.1c6 6.5 5.625 16.64-.9062 22.61c-6.5 6-16.59 5.594-22.59-.8906l-192-208c-5.688-6.156-5.688-15.56 0-21.72l192-208C218.2 30.66 228.3 30.25 234.8 36.25z"></path>
                  </svg>
                </button>
                <button class="Tiles__tile-button Tiles__tile-button--right" aria-label="Next" tabindex="-1" @click="nextCard">
                  <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path fill="currentColor" d="M85.14 475.8c-3.438-3.141-5.156-7.438-5.156-11.75c0-3.891 1.406-7.781 4.25-10.86l181.1-197.1L84.23 58.86c-6-6.5-5.625-16.64 .9062-22.61c6.5-6 16.59-5.594 22.59 .8906l192 208c5.688 6.156 5.688 15.56 0 21.72l-192 208C101.7 481.3 91.64 481.8 85.14 475.8z"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  data() {
    return {
      currentCardIndex: 0,
      cards: [
        {
          variant: 'career-matches',
          title: 'Career Matches',
          description: 'Discover your top career matches and why they\'re right for you.',
          mobileSrcset: 'https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_600/v1593735998/fdxakkpypatudykgl3my 1x, https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_1200/v1593735998/fdxakkpypatudykgl3my 2x',
          mobileWebpSrcset: 'https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_600/v1593735998/fdxakkpypatudykgl3my.webp 1x, https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_1200/v1593735998/fdxakkpypatudykgl3my.webp 2x',
          webpSrcset: 'https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_744/v1593735998/fdxakkpypatudykgl3my.webp 1x, https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_1488/v1593735998/fdxakkpypatudykgl3my.webp 2x',
          srcset: 'https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_744/v1593735998/fdxakkpypatudykgl3my 1x, https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_1488/v1593735998/fdxakkpypatudykgl3my 2x'
        },
        // Add other cards here following the same pattern
      ]
    }
  },
  methods: {
    previousCard() {
      if (this.currentCardIndex > 0) {
        this.currentCardIndex--
      }
    },
    nextCard() {
      if (this.currentCardIndex < this.cards.length - 1) {
        this.currentCardIndex++
      }
    }
  }
}
</script>
