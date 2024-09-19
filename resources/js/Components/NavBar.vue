<template>
  <div>
    <link rel="stylesheet" media="all" href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css" />
    <div class="top-menu__main">
      <div class="top-menu__left">
        <nav role="navigation">
          <ul class="top-menu__list">
            <li class="top-menu__logo">
              <Link :href="route('home')" class="top-menu__option">
                <img
                    class="top-menu__image"
                    alt="CareerHunter"
                    src="https://cdn1.careerhunter.io/assets/CareerHunter-c5d37d4480b8e9fd358ce4d6f1fe629893627bf89acf827c9c68f5fde015b306.svg"
                />
              </Link>
            </li>
            <li class="top-menu__submenu show-for-large">
              <a class="top-menu__option">
                Our Tests
                <span class="upgrade-icon caret-down" />
              </a>
              <div class="top-menu__submenu-content">
                <ul>
                  <li><a href="https://www.careerhunter.io/tests/interests">Career Interests</a></li>
                  <li><a href="https://www.careerhunter.io/tests/personality">Work Personality</a></li>
                  <li><a href="https://www.careerhunter.io/tests/motivators">Career Motivators</a></li>
                  <li><a href="https://www.careerhunter.io/tests/abstract">Abstract Reasoning</a></li>
                  <li><a href="https://www.careerhunter.io/tests/numerical">Numerical Reasoning</a></li>
                  <li><a href="https://www.careerhunter.io/tests/verbal">Verbal Reasoning</a></li>
                  <li><a href="https://www.careerhunter.io/tests">See all tests</a></li>
                </ul>
              </div>
            </li>
            <li class="show-for-large">
              <Link :href="route('how-it-works')" class="top-menu__option">How it works</Link>
            </li>
            <li class="show-for-large">
              <a class="top-menu__option" href="https://www.careerhunter.io/careers">Careers</a>
            </li>
            <li class="top-menu__submenu show-for-large">
              <a class="top-menu__option">
                Explore
                <span class="upgrade-icon caret-down" />
              </a>
              <div class="top-menu__submenu-content">
                <ul>
                  <li><a href="https://www.careerhunter.io/courses">Courses</a></li>
                  <li><a href="https://www.careerhunter.io/jobs">Jobs</a></li>
                </ul>
              </div>
            </li>
            <li class="show-for-large">
              <a class="top-menu__option" href="https://www.careerhunter.io/upgrade">Full Access</a>
            </li>
            <li class="show-for-large">
              <a class="top-menu__option" href="https://www.careerhunter.io/group">For Educators</a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="top-menu__right">
        <nav role="navigation" v-if="canLogin">
          <ul class="top-menu__list">
            <template v-if="$page.props.auth.user">
              <li>
                <Link :href="route('dashboard')" class="button button--green">
                  Dashboard
                </Link>
              </li>
            </template>
            <template v-else>
              <li>
                <Link :href="route('login')" class="top-menu__login-text hide-for-small-only">
                  Log in
                </Link>
              </li>
              <li v-if="canRegister">
                <Link :href="route('register')" class="top-menu__login-text hide-for-small-only">
                  Register
                </Link>
              </li>
            </template>
            <li class="top-menu__item">
              <div id="toggle-menu" class="hamburger button button--white">
                <span class="hamburger__part hamburger__part--top" />
                <span class="hamburger__part hamburger__part--middle" />
                <span class="hamburger__part hamburger__part--bottom" />
              </div>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
  name: 'NavBar',
  components: {
    Link
  },
  props: {
    canLogin: {
      type: Boolean,
      default: false
    },
    canRegister: {
      type: Boolean,
      default: false
    },
    laravelVersion: {
      type: String,
      required: true
    },
    phpVersion: {
      type: String,
      required: true
    }
  }
}
</script>
