<template>
    <div>
      <NavBar :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
      <link rel="stylesheet" media="all" href="" />
      <div class="grid-container home">
        <div class="card card--home">
          <div class="card-1">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">Geoscientist</p>
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
                match
              </div>
            </div>
          </div>
          <div class="card-2">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">Architect</p>
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
                match
              </div>
            </div>
          </div>
          <div class="card-3 show-for-large">
            <div class="card__image"></div>
            <div class="card__section">
              <p class="card__name disable-fade-in">Web developer</p>
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
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent">
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
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent">
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
                    <div class="fill fillAction_8">
                      <svg height="88px" width="88px" enableBackground="new 0 0 88 88" version="1.1" viewBox="0 0 88 88" x="0px" y="0px">
                        <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z" />
                      </svg>
                    </div>
                  </div>
                  <div class="match-percent ">
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
        <div class="circle top-personality">
          <img class="hide-for-small-only" height="43" width="41" src="https://www.careerhunter.io/images/home/personality.png" />
          <img class="show-for-small-only" height="27" width="26" src="https://www.careerhunter.io/images/home/personality_mobile.png" />
        </div>
        <div class="circle top-interests">
          <img class="hide-for-small-only" height="41" width="49" src="https://www.careerhunter.io/images/home/interests.png" />
          <img class="show-for-small-only" height="26" width="31" src="https://www.careerhunter.io/images/home/interests.png" />
        </div>
        <div class="grid-x text-center">
          <div class="small-12 cell">
            <h1 class="banner-top__title-home">Career choices <span>made easier than ever</span></h1>
            <h2 class="banner-top__subtitle">Our tests will accurately match you to professions that <span>you'll love</span> and <span>succeed in!</span></h2>
            <div class="banner-top--home-btns-container">
              <div>
                <div class="banner-top--home-btns">
                  <template v-if="$page.props.auth.user">
                    <Link :href="route('dashboard')" class="button button--green before-fade-in fade-in button--with-icon button--green--big rounded-2xl">
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
        <div class="circle bottom-abstract">
          <img class="hide-for-small-only" height="40" width="42" src="https://www.careerhunter.io/images/home/abstract.png" />
          <img class="show-for-small-only" height="25" width="27" src="https://www.careerhunter.io/images/home/abstract.png" />
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
              Our self-assessments
            </h2>
            <p class="home-tests__desc before-fade-in fade-in">
              Uniquely crafted to deliver actionable reporting
            </p>
          </div>
        </div>
        <div class="grid-x card__equalizer home-tests__cards">
          <div v-for="(test, index) in tests" :key="index" class="large-2 medium-6 small-12 cell text-center card__equal-items">
            <div class="home-tests__card">
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
        <Section />
      <Footer />
    </div>

  </template>

  <script>
  import { Link } from '@inertiajs/vue3';
  import Content from "@/Components/Content.vue";
  import NavBar from "@/Components/NavBar.vue";
  import Footer from "@/Components/Footer.vue"
  import Section from "@/Pages/HomePage/Section.vue";

  export default {
    name: 'App',
    components: {Section, NavBar, Content, Footer,Link},
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
      navigation:Array,
    },
    data() {
      return {
        tests: [
          {
            iconClass: 'interests-blue',
            title: 'Career Interests',
            description: 'Uncover the work activities that you enjoy the most.',
            link: 'https://www.careerhunter.io/tests/interests'
          },
          {
            iconClass: 'personality-blue',
            title: 'Work Personality',
            description: 'Assess your personality type and work preferences.',
            link: 'https://www.careerhunter.io/tests/personality'
          },
          {
            iconClass: 'motivators-blue',
            title: 'Career Motivators',
            description: 'Reveal what motivates you to work and succeed.',
            link: 'https://www.careerhunter.io/tests/motivators'
          },
          {
            iconClass: 'abstract-blue',
            title: 'Abstract Reasoning',
            description: 'Assess your lateral thinking and ability to analyze patterns.',
            link: 'https://www.careerhunter.io/tests/abstract'
          },
          {
            iconClass: 'numerical-blue',
            title: 'Numerical Reasoning',
            description: 'Discover how well you can decipher data and numbers.',
            link: 'https://www.careerhunter.io/tests/numerical'
          },
          {
            iconClass: 'verbal-blue',
            title: 'Verbal Reasoning',
            description: 'Measure your ability to interpret written information.',
            link: 'https://www.careerhunter.io/tests/verbal'
          }
        ]
      }
    }
  }
  </script>
