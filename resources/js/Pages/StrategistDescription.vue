<template>
              <!-- <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">
        
        <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/shared-discovery.4d94d358083a.css"> -->
    <div class="SharedDiscoveryPage__wrap">
      <div id="shared-discovery-container">
        <div class="SharedDiscoveryPage">
          <div
            class="Discovery Discovery--no-accent"
            :style="{ backgroundImage: `url(${discovery.properties.image_url})` }"
          >
            <!-- <span class="Discovery__rarity DiscoveryRarityTag DiscoveryRarityTag--ultra-rare" aria-hidden="true">
              <svg aria-hidden="true" focusable="false" class="svg-inline--fa fa-gem Discovery__rarity__icon DiscoveryRarityTag__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M378.7 32H133.3L256 182.7L378.7 32zM512 192l-107.4-141.3L289.6 192H512zM107.4 50.67L0 192h222.4L107.4 50.67zM244.3 474.9C247.3 478.2 251.6 480 256 480s8.653-1.828 11.67-5.062L510.6 224H1.365L244.3 474.9z"></path>
              </svg>
              {{ discovery.properties.rarity_string }}—{{ (discovery.properties.frequency * 100).toFixed(1) }}% of Users
            </span> -->
            <h1 class="Discovery__title" :aria-label="titleAriaLabel">
              I am a<span class=""> {{ discovery.properties.short_descriptor }}</span>
            </h1>
            <p class="Discovery__rationale">{{ discovery.properties.rationale }}</p>
          </div>
          <section>
            <div class="Discovery__related">
              <p class="Discovery__related__lil-subheading">{{ discovery.properties.verbose_description_header }}</p>
              <p class="Discovery__related__description">{{ discovery.properties.verbose_description }}</p>
              <h3 class="Discovery__related__subheading">Things they're good at</h3>
              <p class="Discovery__related__description">{{ discovery.properties.strengths_body }}</p>
              <h3 class="Discovery__related__subheading">Things they can watch out for</h3>
              <p class="Discovery__related__description">{{ discovery.properties.weaknesses_body }}</p>
              <h3 class="Discovery__related__lil-subheading">The Science</h3>
              <p class="Discovery__related__description">{{ discovery.properties.scales_descriptor }}</p>
              <div class="Discovery__related__content Discovery__related__content--scales">
                <h3 class="Discovery__related__lil-subheading" tabindex="0" aria-label="Below you will find a list of scores detailing where you match-up to chosen traits.">Their scores:</h3>
                <div class="PersonalityGraphs">
                  <a v-for="scale in discovery.properties.scales" :key="scale.scale_id" href="#" class="Box">
                    <div class="PersonalityGraphs__scale">
                      <div class="PersonalityGraphs__scale__name" :id="`${scale.scale_id}-personalitygraph-scale-label`">{{ scale.scale_name }}</div>
                      <span class="sr-only" :id="`${scale.scale_id}-personalitygraph-scale-desc`">Your {{ scale.scale_name.toLowerCase() }} rates at {{ scale.percentile_score }} out of 100.</span>
                      <figure class="PersonalityGraphs__scale__graph" tabindex="0" :aria-labelledby="`${scale.scale_id}-personalitygraph-scale-label`" :aria-describedby="`${scale.scale_id}-personalitygraph-scale-desc`">
                        <span class="BarMeter--gradient-dusk" :data-value="Math.round(scale.percentile_score)">
                          <span class="BarMeter__meter" :style="{ width: `${Math.round(scale.percentile_score)}%` }">
                            <span class="BarMeter__label">{{ Math.round(scale.percentile_score) }}%</span>
                          </span>
                        </span>
                      </figure>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="Discovery__sidebar">
              <div>
                <img :src="discovery.properties.cta.image_url" :alt="discovery.properties.cta.title" class="">
                <div class="Discovery__sidebar__content">
                  <h3 class="">{{ discovery.properties.cta.title }}</h3>
                  <p class="">{{ discovery.properties.cta.text }}</p>
                  <a href="/members/register/deferred/" class="alans-butt--purple">Take the career test</a>
                  <a href="/career-test/" class="alans-butt--grey">Learn more about the career test</a>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'StrategistDescription',
    data() {
      return {
        discovery: {
          id: 19,
          slug: "guardian",
          type: "archetype",
          properties: {
            notification_text: "I am a Guardian",
            rarity_string: "ULTRA RARE",
            frequency: 0.0131,
            verbose_description: "Guardians love helping people. They have a natural ability to work with others, but also find time to work in solitude where they are able to organize their thoughts with detail-oriented tasks. Accuracy paired with helpful support makes the Guardian an excellent support system to turn to.",
            image_url: "https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_400,q_auto,w_640/v1558730242/qextjr0j6lwfbwgcbdyy.jpg",
            rationale: "Understanding, Responsible, Rational",
            short_descriptor: "Guardian",
            verbose_description_header: "About Guardians",
            scales_descriptor: "A Social person is sensitive to the needs of others and does well in collaborative group settings where they have the opportunity to work with and help others. A Conventional person is detail oriented and methodical, feeling most at ease in structured environments where they feel in control.",
            scales: [
              {
                scale_name: "Social",
                scale_id: 4,
                percentile_score: 70.51474262868565,
                single_sentence_description: "A Social person is sensitive to the needs of others and does well in collaborative group settings where they have the opportunity to work with and help others."
              },
              {
                scale_name: "Conventional",
                scale_id: 6,
                percentile_score: 90.75462268865567,
                single_sentence_description: "A Conventional person is detail oriented and methodical, feeling most at ease in structured environments where they feel in control."
              }
            ],
            strengths_body: "Guardians are kind, patient, and empathetic and often have a strong preference for organized, methodical tasks. They are often hardworking people that enjoy social interaction and building strong relationships. They are dependable and organized. Guardians are generally quite loyal people, and they like when they can be of value to someone in need. They like to plan things that will be enjoyable experiences for others.",
            weaknesses_body: "Guardians perform well when they have set tasks to complete, but can struggle to define the tasks in the role of leader. They feel most comfortable when they are part of a support system. While this makes them extremely hard working, they are also natural people-pleasers and have a tendency to say yes to most requests. In some cases, this means taking on more work than they can realistically finish within certain time constraints.",
            cta: {
              title: "Discover what makes you, You",
              text: "Are you a guardian too? Discover your archetype with the CareerExplorer assessment. You'll also uncover your top career matches and get insights into your unique personality.",
              image_url: "https://res.cloudinary.com/hnpb47ejt/image/upload/q_auto,f_auto,w_480/v1568404399/ux8i7elvayqifan41pj8"
            }
          }
        }
      };
    },
    computed: {
      titleAriaLabel() {
        return `I am a ${this.discovery.properties.short_descriptor}. That is actually ${this.discovery.properties.rarity_string.toLowerCase()}! This places you in the top ${(this.discovery.properties.frequency * 100).toFixed(1)}% of users.`;
      }
    }
  };
  </script>
  
  <style scoped>
  .DiscoveryRarityTag {
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    background-color: rgba(0, 0, 0, 0.36);
    border-radius: 5px;
    display: inline-block;
    font-size: 10px;
    line-height: 11px;
    padding: 8px;
    border-radius: 5px;
    background-color: rgba(0, 0, 0, 0.2);
    color: white;
    text-transform: uppercase;
    z-index: 2;
  }
  
  @media all and (min-width: 768px) {
    .DiscoveryRarityTag {
      font-size: 14px;
      line-height: 16px;
    }
  }
  
  .DiscoveryRarityTag--ultra-rare .DiscoveryRarityTag__icon,
  .DiscoveryRarityTag--very-rare .DiscoveryRarityTag__icon {
    color: #deb055;
  }
  
  .DiscoveryRarityTag__icon {
    font-size: 10px;
    line-height: 10px;
    margin-right: 8px;
    vertical-align: middle;
  }
  
  @media all and (min-width: 768px) {
    .DiscoveryRarityTag__icon {
      font-size: 14px;
      line-height: 14px;
    }
  }
  
  .Discovery {
    color: white;
    padding: 0 1em 0;
    background-position: bottom;
    background-size: cover;
    border-radius: inherit;
  }
  
  @media all and (min-width: 768px) {
    .Discovery {
      text-align: center;
      padding: 0 2em 0;
    }
  
    .Discovery p {
      text-align: left;
    }
  }
  
  .Discovery:empty {
    pointer-events: none;
    padding: 0 !important;
  }
  
  .Discovery__title {
    color: white;
    font-weight: 300;
    margin: 15px 0 5px;
    font-size: 30px;
    text-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
  }
  
  .Discovery__title:focus {
    outline: none;
  }
  
  @media (min-width: 641px) {
    .Discovery__title {
      font-size: 30px;
    }
  }
  
  @media all and (min-width: 768px) {
    .Discovery__title {
      margin: 15px 0 25px;
      font-size: 40px;
      letter-spacing: -0.4px;
      line-height: 47px;
    }
  }
  
  .Discovery__rarity {
    margin: 15px 0 0;
  }
  
  @media all and (min-width: 768px) {
    .Discovery__rarity {
      margin: 30px 0 0;
    }
  }
  
  .Discovery__rarity+.Dialog-title {
    margin-top: 5px;
  }
  
  @media all and (min-width: 768px) {
    .Discovery__rarity+.Dialog-title {
      margin-top: 10px;
    }
  }
  
  .Discovery__rarity__icon {
    font-size: 14px;
    line-height: 14px;
    margin-right: 8px;
  }
  
  .Discovery p {
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-size: 22px;
    line-height: 28px;
    text-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12);
  }
  
  @media all and (min-width: 768px) {
    .Discovery p {
      text-align: center;
    }
  }
  
  .Discovery p:focus {
    outline: none;
  }
  
  .Discovery__related {
    color: #fff;
    padding: 0 1em 0;
    max-height: 100%;
  }
  
  @media all and (min-width: 768px) {
    .Discovery__related {
      padding: 0 2em 0;
    }
  }
  
  .Discovery__related__description {
    font-family: "pt-serif", serif !important;
    font-size: 16px;
    line-height: 25px;
    color: rgba(255, 255, 255, 0.9);
  }
  
  .Discovery__related__subheading {
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-size: 20px;
    line-height: 26px;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.9);
  }
  
  @media all and (min-width: 768px) {
    .Discovery__related__subheading {
      font-size: 25px;
      line-height: 31px;
    }
  }
  
  .Discovery__related__lil-subheading {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    font-weight: 300;
    letter-spacing: 2px;
    line-height: 14px;
    text-transform: uppercase;
  }
  
  .Discovery__related__content p {
    font-family: "pt-serif", serif !important;
    font-size: 16px;
    line-height: 25px;
    font-weight: 600;
  }
  
  .Discovery__related__content--scales {
    background-color: rgba(0, 0, 0, 0.4);
  }
  
  .Discovery__related__content--scales h3 {
    border-bottom: 2px solid rgba(255, 255, 255, 0.18);
    padding: 16px;
    margin: 0;
  }
  
  .Discovery__related .PersonalityGraphs {
    margin-bottom: 24px;
  }
  
  .Discovery__related .PersonalityGraphs .Box {
    padding: 24px 16px;
    background: transparent;
    cursor: unset;
    border-bottom: 2px solid rgba(255, 255, 255, 0.18) !important;
    margin: 0;
  }
  
  .Discovery__related .PersonalityGraphs .Box:last-of-type {
    border-bottom: 0 !important;
  }
  
  .Discovery__related .PersonalityGraphs .Box figure {
    margin: 0px 0px 10px 0px;
  }
  
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter:before,
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter--gradient-dusk:before,
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter--gradient-midnight:before {
    background-color: #e5e0ea5e;
    height: 5px;
  }
  
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter:before .BarMeter__meter,
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter--gradient-dusk:before .BarMeter__meter,
  .Discovery__related .PersonalityGraphs .Box figure .BarMeter--gradient-midnight:before .BarMeter__meter {
    margin-left: -1px;
    height: 19px;
    top: 3.35px;
  }
  
  .Discovery__related .PersonalityGraphs__scale {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
  }
  
  @media all and (min-width: 768px) {
    .Discovery__related .PersonalityGraphs__scale {
      grid-template-columns: minmax(0, 1fr) 2fr;
    }
  }
  
  .Discovery__related .PersonalityGraphs__scale__name {
    color: #ffffff;
    font-size: 14px;
    font-weight: 300;
    line-height: 17px;
    min-width: 170px;
    padding-right: 20px;
  }
  
  .Discovery__related .Discovery__MemberCareerMatches {
    margin-left: 0;
  }
  
  .Discovery__related .Discovery__MemberCareerMatches .HorizontalCard {
    margin-bottom: 12px;
  }
  
  @media (max-width: 767px) {
    .Discovery__related .Discovery__MemberCareerMatches .HorizontalCard {
      width: calc(100% + 32px);
      margin: 0 -16px 12px;
      border-top: 1px solid #d7d7d7;
      border-bottom: 1px solid #d7d7d7;
      border-radius: 0;
      box-shadow: 0 2px 8px 0 rgba(216, 216, 204, 0.45);
    }
  
    .Discovery__related .Discovery__MemberCareerMatches .HorizontalCard:hover {
      box-shadow: 0 2px 16px rgba(216, 216, 204, 0.8);
    }
  
    .Discovery__related .Discovery__MemberCareerMatches .HorizontalCard-image {
      border-radius: 8px;
      margin: 8px 0 8px 16px;
    }
  }
  
  .Discovery--single-scale .Discovery__related,
  .Discovery--no-scale .Discovery__related,
  .Discovery--multi-scale .Discovery__related,
  .Discovery--career-match .Discovery__related {
    padding: 0 0 2em;
    padding-top: 2em;
  }
  
  @media all and (min-width: 768px) {
    .Discovery--single-scale .Discovery__related,
    .Discovery--no-scale .Discovery__related,
    .Discovery--multi-scale .Discovery__related,
    .Discovery--career-match .Discovery__related {
      padding: 2em;
    }
  }
  
  @media all and (max-width: 767px) {
    .Discovery--single-scale .Discovery__related__description,
    .Discovery--single-scale .Discovery__related__content p,
    .Discovery--single-scale .Discovery__related__feedback,
    .Discovery--no-scale .Discovery__related__description,
    .Discovery--no-scale .Discovery__related__content p,
    .Discovery--no-scale .Discovery__related__feedback,
    .Discovery--multi-scale .Discovery__related__description,
    .Discovery--multi-scale .Discovery__related__content p,
    .Discovery--multi-scale .Discovery__related__feedback,
    .Discovery--career-match .Discovery__related__description,
    .Discovery--career-match .Discovery__related__content p,
    .Discovery--career-match .Discovery__related__feedback {
      padding: 0 1em;
    }
  }
  
  .Discovery--multi-scale .Discovery__related,
  .Discovery--career-match .Discovery__related {
    background: white;
    color: black;
  }
  
  .Discovery__sidebar {
    width: 90%;
    padding-top: 24px;
    text-align: center;
    margin: auto;
  }
  
  @media all and (min-height: 975px) {
    .Discovery__sidebar {
      text-align: left;
    }
  }
  
  @media all and (min-width: 768px) and (orientation: landscape) {
    .Discovery__sidebar {
      width: 417px;
      padding-top: 106px;
      margin: unset;
    }
  }
  
  .Discovery__sidebar>div {
    border-radius: 13px;
    background-color: rgba(0, 0, 0, 0.4);
  }
  
  @media all and (min-width: 768px) and (orientation: landscape) {
    .Discovery__sidebar>div {
      position: sticky;
      top: 70px;
    }
  }
  
  .Discovery__sidebar>div img {
    margin-left: 0;
    margin-top: -35px;
  }
  
  @media all and (min-width: 768px) and (orientation: landscape) {
    .Discovery__sidebar>div img {
      margin-top: -70px;
      max-width: none;
      height: 500px;
      max-height: 47vh;
    }
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content {
    padding: 5px 22px 10px;
    text-align: left;
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content h3,
  .Discovery__sidebar>div .Discovery__sidebar__content p {
    color: rgba(255, 255, 255, 0.9);
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content h3 {
    font-size: 20px;
    line-height: 26px;
    font-weight: bold;
    margin-top: 0;
  }
  
  @media all and (min-width: 768px) and (orientation: landscape) {
    .Discovery__sidebar>div .Discovery__sidebar__content h3 {
      font-size: 25px;
      line-height: 31px;
    }
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content p {
    font-size: 16px;
    line-height: 24px;
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content [class*="alans-butt"] {
    width: 100%;
    margin-bottom: 24px;
    height: 48px;
    font-size: 17px;
    padding: 12px 0;
  }
  
  .Discovery__sidebar>div .Discovery__sidebar__content .alans-butt--grey {
    background: transparent;
    color: white;
    border-width: 2px;
  }
  
  .SharedDiscoveryPage {
    width: 100%;
    padding: 0 0 86px 0;
    background-color: var(--DiscoveryAccentColor, #1e1e21);
  }
  
  .SharedDiscoveryPage__wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  
  .SharedDiscoveryPage__wrap #shared-discovery-container {
    width: 100%;
  }
  
  .SharedDiscoveryPage__wrap .Discovery__title,
  .SharedDiscoveryPage__wrap .Discovery__rationale,
  .SharedDiscoveryPage__wrap .Discovery__related,
  .SharedDiscoveryPage__wrap .Discovery__sidebar {
    position: relative;
    margin: 0 auto;
    text-shadow: 0px 1px 7px rgba(0, 0, 0, 0.06);
    z-index: 2;
  }
  
  .SharedDiscoveryPage__wrap .Discovery__title,
  .SharedDiscoveryPage__wrap .Discovery__rationale {
    max-width: 580px;
  }
  
  @media all and (min-width: 768px) {
    .SharedDiscoveryPage__wrap .Discovery__title {
      font-size: 64px;
      letter-spacing: -0.64px;
      line-height: 83px;
      margin: 14px auto 16px;
    }
  }
  
  .SharedDiscoveryPage__wrap .Discovery__title span {
    font-family: "pt-serif", serif !important;
    font-weight: 400;
  }
  
  .SharedDiscoveryPage__wrap .Discovery__rationale {
    font-family: "pt-serif", serif !important;
    padding-bottom: 24px;
  }
  
  @media all and (min-width: 768px) {
    .SharedDiscoveryPage__wrap .Discovery__rationale {
      font-size: 32px;
      line-height: 41px;
    }
  }
  
  .SharedDiscoveryPage__wrap .Discovery__rarity--wrapper {
    max-width: 580px;
    position: relative;
    margin: 0 auto;
  }
  
  .SharedDiscoveryPage__wrap section {
    max-width: 1125px;
    margin: 25px auto;
  }
  
  @media all and (min-width: 768px) {
    .SharedDiscoveryPage__wrap section {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 430px;
      justify-content: center;
    }
  }
  
  .SharedDiscoveryPage__wrap .Discovery__related {
    padding-top: 24px;
  }
  
  @media all and (min-width: 768px) {
    .SharedDiscoveryPage__wrap .Discovery__related {
      padding-top: 86px;
    }
  }
  
  @media all and (min-width: 768px) {
    .SharedDiscoveryPage__wrap .Discovery__related__description {
      font-size: 20px;
      line-height: 31px;
    }
  }
  
  .SharedDiscoveryPage .Discovery {
    padding-top: 15vh;
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 300px;
    padding-bottom: 40px;
    position: relative;
  }
  
  @media all and (min-width: 1024px) {
    .SharedDiscoveryPage .Discovery {
      min-height: 350px;
    }
  }
  
  .SharedDiscoveryPage .Discovery--no-accent:before,
  .SharedDiscoveryPage .Discovery--no-accent:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    display: block;
    z-index: 1;
    pointer-events: none;
  }
  
  .SharedDiscoveryPage .Discovery--no-accent:after {
    bottom: 0;
    height: 180px;
    background-image: linear-gradient(rgba(0, 0, 0, 0) 0%, rgba(30, 30, 33, 0.321569) 56%, #1e1e21 100%);
  }
  
  .SharedDiscoveryPage .Discovery--no-accent:before {
    top: 0;
    height: 80px;
    background-image: linear-gradient(#1e1e21 0%, rgba(0, 0, 0, 0) 70%);
  }
  
  .SharedDiscoveryPage .Discovery .DiscoveryRarityTag {
    background-color: rgba(0, 0, 0, 0.52);
  }
  .SharedDiscoveryPage .Discovery__sidebar__content {
    box-sizing: border-box;
    margin: 0px;
    padding: 5px 22px 10px;
    text-align: left;
  }

  .SharedDiscoveryPage .Discovery__sidebar__content h3 {
    box-sizing: border-box;
    margin: 0px;
    padding: 0px;
    font-style: normal;
    margin-bottom: 0.5rem;
    text-rendering: optimizelegibility;
    color: rgba(255, 255, 255, 0.9);
    font-weight: bold;
    margin-top: 0px;
    font-size: 25px;
    line-height: 31px;
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  .SharedDiscoveryPage .Discovery__sidebar__content p {
    box-sizing: border-box;
    margin: 0px;
    padding: 0px;
    font-weight: normal;
    margin-bottom: 1.42857rem;
    text-rendering: optimizelegibility;
    color: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    line-height: 24px;
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  .SharedDiscoveryPage .Discovery__sidebar__content .alans-butt--purple {
    box-sizing: border-box;
    text-decoration: none;
    border: medium;
    border-radius: 8px;
    transition: 0.4s;
    position: relative;
    display: inline-flex;
    flex-direction: row;
    justify-content: center;
    font-weight: 500;
    line-height: 1.2;
    text-align: center;
    cursor: pointer;
    background-position: center center;
    background-image: radial-gradient(circle, rgb(195, 91, 138) 0%, rgb(102, 57, 116) 100%);
    background-size: 500%;
    box-shadow: rgba(135, 49, 88, 0.24) 0px 2px 12px 0px;
    color: white;
    padding: 12px 0px;
    width: 100%;
    margin-bottom: 24px;
    height: 48px;
    font-size: 17px;
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  .SharedDiscoveryPage .Discovery__sidebar__content .alans-butt--grey {
    box-sizing: border-box;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.4s;
    position: relative;
    display: inline-flex;
    flex-direction: row;
    justify-content: center;
    font-weight: 500;
    line-height: 1.2;
    text-align: center;
    cursor: pointer;
    border: 2px solid rgb(177, 177, 177);
    padding: 12px 0px;
    width: 100%;
    margin-bottom: 24px;
    height: 48px;
    font-size: 17px;
    background: transparent;
    color: white;
    font-family: "aktiv-grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
  }
  </style>