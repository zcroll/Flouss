<template>
  <div>
    <NavBar :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
    <link rel="stylesheet" media="all" href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css" />
    <div class="banner-top banner-top--home before-fade-in fade-in">
      <div class="circle top-left-big hide-for-small-only"></div>
      <div class="circle top-left-small hide-for-small-only"></div>

      <div class="grid-container home">
        <div class="card card--home">
          <div class="card-1">
            <div class="card__image"></div>
            <div class="card__section"><p class="card__name disable-fade-in">Geoscientist</p></div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                  <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                  </svg>
                </div>
              </div>
              <div class="match-percent hidden">
                <div class="qm">64%</div>
                match
              </div>
            </div>
          </div>
          <div class="card-2">
            <div class="card__image"></div>
            <div class="card__section"><p class="card__name disable-fade-in">Architect</p></div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                  <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                  </svg>
                </div>
              </div>
              <div class="match-percent hidden">
                <div class="qm">78%</div>
                match
              </div>
            </div>
          </div>
          <div class="card-3 show-for-large">
            <div class="card__image"></div>
            <div class="card__section"><p class="card__name disable-fade-in">Web developer</p></div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                  <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                  </svg>
                </div>
              </div>
              <div class="match-percent hidden">
                <div class="qm">78%</div>
                match
              </div>
            </div>
          </div>
          <div class="card--horizontal">
            <div class="card-4 hide-for-small-only">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                      <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent hidden">
                    <div class="qm">64%</div>
                    match
                  </div>
                </div>
                <p class="card__name disable-fade-in">Business and Management</p>
              </div>
            </div>
            <div class="card-5 show-for-large">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                      <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent hidden">
                    <div class="qm">64%</div>
                    match
                  </div>
                </div>
                <p class="card__name disable-fade-in">Engineering</p>
              </div>
            </div>
            <div class="card-6 show-for-large">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8" style="transform: translate(0px, 12.24px);">
                      <svg version="1.1" x="0px" y="0px" width="88px" height="88px" viewBox="0 0 88 88" enable-background="new 0 0 88 88">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent hidden">
                    <div class="qm">64%</div>
                    match
                  </div>
                </div>
                <p class="card__name disable-fade-in">Creative Arts</p>
              </div>
            </div>
          </div>
        </div>
        <div class="circle top-right-small"></div>
        <div class="circle top-personality"><img src="images/home/personality.png" width="41" height="43" class="hide-for-small-only"><img src="images/home/personality_mobile.png" width="26" height="27" class="show-for-small-only"></div>
        <div class="circle top-interests"><img src="images/home/interests.png" width="49" height="41" class="hide-for-small-only"><img src="images/home/interests.png" width="31" height="26" class="show-for-small-only"></div>

        <div class="grid-x text-center">
          <div class="small-12 cell">
            <h1 class="banner-top__title-home">Career choices <span>made easier than ever</span></h1>
            <h2 class="banner-top__subtitle">Our tests will accurately match you to professions that <span>you'll love</span> and <span>succeed in!</span></h2>
            <div class="banner-top--home-btns-container">
              <div>
                <div class="banner-top--home-btns">
                  <template v-if="$page.props.auth.user">
                    <Link :href="route('dashboard')" class="button button--green before-fade-in fade-in button--with-icon button--green--big">
                      Dashboard
                      <span class="small-icons next-arrow-white-s"></span>
                      <span class="button__hovered"></span>
                    </Link>
                  </template>
                  <template v-else>
                    <Link :href="route('login')" class="button button--green before-fade-in fade-in button--with-icon button--green--big">
                      Log in
                      <span class="small-icons next-arrow-white-s"></span>
                      <span class="button__hovered"></span>
                    </Link>
                    <p class="banner-top--home-or before-fade-in fade-in">or</p>
                    <Link v-if="canRegister" :href="route('register')" class="button button--white before-fade-in fade-in button--with-icon button--white--big">
                      Register
                      <span class="small-icons green-small-lock-s"></span>
                      <span class="button__hovered"></span>
                    </Link>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="circle bottom-left-small"></div>
        <div class="circle bottom-abstract"><img src="images/home/abstract.png" width="42" height="40" class="hide-for-small-only"><img src="images/home/abstract.png" width="27" height="25" class="show-for-small-only"></div>
      </div>
    </div>
    <Content :canLogin="canLogin" :canRegister="canRegister" />
    <div>
      <link rel="stylesheet" media="all" href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css" />
      <div class="grid-container">
        <div class="grid-x text-center">
          <div class="small-12 cell">
            <h2 class="home-tests__heading before-fade-in fade-in">
              Our self-assessments
            </h2>
            <p class="home-tests__desc before-fade-in fade-in">
              Uniquely crafted to deliver actionable reporting
            </p>
          </div>
        </div>
        <div class="grid-x card__equalizer home-tests__cards align-center">
          <div v-for="(test, index) in tests" :key="index" class="large-2 medium-6 small-12 cell text-center card__equal-items">
            <div class="home-tests__card" style="margin: 0 auto;">
              <div class="circle half-top"></div>
              <div class="circle right-small"></div>
              <div class="home-tests__icon">
                <div :class="['large-icon', test.iconClass, 'float-center', 'before-fade-in', 'fade-in']"></div>
              </div>
              <h3 class="home-tests__title before-fade-in fade-in">
                {{ test.title }}
              </h3>
              <p class="home-tests__info before-fade-in fade-in">
                {{ test.description }}
              </p>
              <div>
                <a class="button button--blue-off before-fade-in fade-in" :href="test.link">
                  Learn more
                  <span class="button__hovered"></span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <RedaNwi9s />
</template>

<script>
import NavBar from '@/Components/NavBar.vue';
import Content from '@/Components/Content.vue';
import RedaNwi9s from '@/Pages/HomePage/RedaNwi9s.vue';
export default {
  components: {
    NavBar,
    Content,
    RedaNwi9s
  },
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String
  },
}
</script>