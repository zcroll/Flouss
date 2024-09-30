<template>
    <div>
      <NavBar   :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
      <link rel="stylesheet" media="all" href="/css/landingPage/index.css" />
      <div class="grid-container home">
        <div class="card card--home">
          <div class="card-1">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">{{ __('welcome.job_titles.geoscientist') }}</p>
            </div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8">
                  <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                  </svg>
                </div>
              </div>
              <div class="match-percent">
                <div class="qm">64%</div>
                {{ __('welcome.match') }}
              </div>
            </div>
          </div>
          <div class="card-2">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">{{ __('welcome.job_titles.architect') }}</p>
            </div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8">
                  <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                  </svg>
                </div>
              </div>
              <div class="match-percent">
                <div class="qm">78%</div>
                {{ __('welcome.match') }}
              </div>
            </div>
          </div>
          <div class="card-3 show-for-large">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">{{ __('welcome.job_titles.web_developer') }}</p>
            </div>
            <div class="match-tank pulse_8">
              <div class="inner">
                <div class="fill fillAction_8">
                  <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                  </svg>
                </div>
              </div>
              <div class="match-percent">
                <div class="qm">78%</div>
                {{ __('welcome.match') }}
              </div>
            </div>
          </div>
          <div class="card--horizontal">
            <div class="card-4 hide-for-small-only">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent">
                    <div class="qm">64%</div>
                    {{ __('welcome.match') }}
                  </div>
                </div>
                <p class="card__name disable-fade-in">{{ __('welcome.job_titles.business_management') }}</p>
              </div>
            </div>
            <div class="card-5 show-for-large">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent">
                    <div class="qm">64%</div>
                    {{ __('welcome.match') }}
                  </div>
                </div>
                <p class="card__name disable-fade-in">{{ __('welcome.job_titles.engineering') }}</p>
              </div>
            </div>
            <div class="card-6 show-for-large">
              <div class="card__image"></div>
              <div class="card__section">
                <div class="match-tank pulse_8">
                  <div class="inner">
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent ">
                    <div class="qm">64%</div>
                    {{ __('welcome.match') }}
                  </div>
                </div>
                <p class="card__name disable-fade-in">{{ __('welcome.job_titles.creative_arts') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="circle top-right-small"></div>
        <div class="circle top-personality">
          <img class="hide-for-small-only" height="43" width="41" src="https://www.careerhunter.io/images/home/personality.png" :alt="__('welcome.personality_image_alt')" />
          <img class="show-for-small-only" height="27" width="26" src="https://www.careerhunter.io/images/home/personality_mobile.png" :alt="__('welcome.personality_image_alt')" />
        </div>
        <div class="circle top-interests">
          <img class="hide-for-small-only" height="41" width="49" src="https://www.careerhunter.io/images/home/interests.png" :alt="__('welcome.interests_image_alt')" />
          <img class="show-for-small-only" height="26" width="31" src="https://www.careerhunter.io/images/home/interests.png" :alt="__('welcome.interests_image_alt')" />
        </div>
        <div class="grid-x text-center">
          <div class="small-12 cell">
            <h1 class="banner-top__title-home">{{ __('welcome.title1') }} <span>{{ __('welcome.title2') }}</span></h1>
            <img v-if="$page.props.language === 'fr'" src="/orienté.webp" alt="orienté" width="40%" height="auto">
            <img v-if="$page.props.language === 'en'" src="/oriented.webp" alt="orienté" width="40%" height="auto">
            <h2 class="banner-top__subtitle">{{ __('welcome.subtitle1') }} <span>{{ __('welcome.subtitle2') }}</span>{{ __('welcome.subtitle3') }}<span>{{ __('welcome.subtitle4') }}</span></h2>
            <div class="banner-top--home-btns-container">
              <div>
                <div class="banner-top--home-btns">
                  <template v-if="$page.props.auth.user">
                    <Link :href="route('dashboard')" class="button button--green--large before-fade-in fade-in button--with-icon">
                        {{ __('welcome.dashboard') }}
                      <span class="small-icons next-arrow-white-s"></span>
                      <span class="button__hovered"></span>
                    </Link>
                  </template>
                  <template v-else>
                    <Link :href="route('login')" class="button button--green--large before-fade-in fade-in button--with-icon">
                        {{ __('welcome.login') }}
                      <span class="small-icons next-arrow-white-s"></span>
                      <span class="button__hovered"></span>
                    </Link>
                    <p class="banner-top--home-or before-fade-in fade-in">{{ __('welcome.or') }}</p>
                    <Link v-if="canRegister" :href="route('register')" class="button button--white before-fade-in fade-in button--with-icon button--white--big">
                        {{ __('welcome.register') }}
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
        <div class="circle bottom-abstract">
          <img class="hide-for-small-only" height="40" width="42" src="https://www.careerhunter.io/images/home/abstract.png" :alt="__('welcome.abstract_image_alt')" />
          <img class="show-for-small-only" height="25" width="27" src="https://www.careerhunter.io/images/home/abstract.png" :alt="__('welcome.abstract_image_alt')" />
        </div>
      </div>
    </div>
    <Content :canLogin="canLogin" :canRegister="canRegister" />
    <div>
      <link rel="stylesheet" media="all" href="">
      <div class="grid-container">
        <div class="grid-x text-center">
          <div class="small-12 cell">
            <h2 class="home-tests__heading before-fade-in fade-in">
              {{ __('welcome.self_assessments') }}
            </h2>
            <p class="home-tests__desc before-fade-in fade-in">
              {{ __('welcome.self_assessments_desc') }}
            </p>
          </div>
        </div>
        <div class="grid-x card__equalizer home-tests__cards">
          <div v-for="(test, index) in tests" :key="index" class="large-2 medium-6 small-12 cell text-center card__equal-items">
            <div class="home-tests__card" style="border: 2px solid #fb6302; border-radius: 10px; margin: 10px; padding: 15px; transition: all 0.3s ease;">
              <div class="circle half-top"></div>
              <div class="circle right-small"></div>
              <div class="home-tests__icon">
                <div :class="['large-icon',  'float-center', 'before-fade-in', 'fade-in']"></div>
              </div>
              <h3 class="home-tests__title before-fade-in fade-in" style="margin: 15px 0; transition: color 0.3s ease;">
                {{ __(`welcome.tests.${test.key}.title`) }}
              </h3>
              <p class="home-tests__info before-fade-in fade-in" style="margin: 15px 0; transition: color 0.3s ease;">
                {{ __(`welcome.tests.${test.key}.description`) }}
              </p>
              <div>

              </div>
            </div>
          </div>
        </div>
      </div>
        <Section />
      <Footer />
    </div>

  </template>

  <script>
  import { Link , usePage } from '@inertiajs/vue3';
  import Content from "@/Components/Content.vue";
  import NavBar from "@/Pages/NavBar.vue";
  import Footer from "@/Components/Footer.vue"
  import Section from "@/Pages/HomePage/Section.vue";




  export default {
    name: 'App',
    components: {Section, NavBar, Content, Footer, Link, usePage},
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
      navigation: Array,
    },
    data() {
      return {
        tests: [
          {
            key: 'interests',
            iconClass: 'interests-blue',
            
          },
          {
            key: 'personality',
            iconClass: 'personality-blue',
            
          },
          {
            key: 'motivators',
            iconClass: 'motivators-blue',
            
          },
          {
            key: 'abstract',
            iconClass: 'abstract-blue',
            
          },
          {
            key: 'numerical',
            iconClass: 'numerical-blue',
            
          },
          {
            key: 'verbal',
            iconClass: 'verbal-blue',
            
          }
        ]
      }
    }
  }
  </script>
