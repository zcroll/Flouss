<script>
import { Link } from '@inertiajs/vue3';
export default {
  components: {
    Link
  },
  props: {
    canLogin: {
      type: Boolean,
      required: true
    },
    canRegister: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      isSticky: false
    }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll() {
      this.isSticky = window.scrollY > 0
    }
  }
}
</script>


<template>
    <!-- <link rel="stylesheet" href="/css/header.css"> -->
       <div id="globalnav-container" data-registration-modal="true">
        <nav :class="['GlobalNav', { 'GlobalNav--sticky': isSticky }]" role="navigation">
            <a class="GlobalNav-logo--desktop w--current" data-track="mixpanel" data-target="Home" data-link-type="Header" href="/">
              <img src="/logo_no_background.png" alt="Gen.Z" class="h-20" />
            </a>
            <a class="GlobalNav-menu-button" href="#" data-track="mixpanel" data-target="Toggle Menu" data-link-type="Header" aria-label="Open Menu">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" class="svg-inline--fa fa-bars GlobalNav-menu-hamburger" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM0 256C0 238.3 14.33 224 32 224H416C433.7 224 448 238.3 448 256C448 273.7 433.7 288 416 288H32C14.33 288 0 273.7 0 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z"></path>
                </svg>
            </a>
            <ul class="GlobalNav-menu">
              <li class="GlobalNav-menu-item">
                    <a class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" href="/for-organizations/">&nbsp;&nbsp;&nbsp;&nbsp;</a>
                </li>     <li class="GlobalNav-menu-item">
                    <a class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" href="/">Home </a>
                </li>
                <li class="GlobalNav-menu-item GlobalNav-menu-item--mobile">
                    <a class="GlobalNav-menu-link GlobalNav-menu-link--default w--current" data-track="mixpanel" data-target="Home" data-link-type="Header" href="/">{{ __('navigation.home') }}</a>
                </li>
                <li class="GlobalNav-menu-item">
                    <a class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="Career Test" data-link-type="Header" href="/career-test/">{{ __('navigation.Career_Test') }}</a>
                </li>
                <li class="GlobalNav-menu-item">
                    <a class="GlobalNav-menu-link GlobalNav-menu-link--default" data-track="mixpanel" data-target="For Organizations" data-link-type="Header" href="/for-organizations/">{{ __('navigation.Organization') }}</a>
                </li>





            </ul>
            <div class="GlobalNav-menu-item--login">
                <template v-if="$page.props.auth.user">
                    <Link :href="route('dashboard')" class="GlobalNav-menu-link GlobalNav-menu-link--default">
                        {{ __('navigation.dashboard') }}
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="GlobalNav-menu-link GlobalNav-menu-link--default" data-qa-id="login-button">
                        {{ __('navigation.log_in') }}
                    </Link>
                </template>
              </div>
                <Link :href="route('main-test')" class="GlobalNav-button GlobalNav-button--main alans-butt--grey">{{ __('navigation.take_test') }}</Link>
        </nav>
        <div class="progress-container" id="progress-bar-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
    </div>
</template>


<style scoped>
@import '/resources/css/header.css';
/* @import '/resources/css/global.css'; */
</style>
