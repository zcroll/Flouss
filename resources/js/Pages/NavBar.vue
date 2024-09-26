<template>
  <div>
    <link rel="stylesheet" media="all" href="/css/landingPage/index.css" />
    <div class="top-menu__main">
      <div class="top-menu__left">
        <nav role="navigation">
          <ul class="top-menu__list space-x-8">
            <li class="top-menu__logo">
              <Link :href="route('home')" class="top-menu__option">
                <img
                    class="top-menu__image"
                    alt="CareerHunter"
                    src="https://cdn1.careerhunter.io/assets/CareerHunter-c5d37d4480b8e9fd358ce4d6f1fe629893627bf89acf827c9c68f5fde015b306.svg"
                />
              </Link>
            </li>
            <li class="show-for-large">
              <Link :href="route('how-it-works')" class="btn_link text-white font-bold py-2 px-3 rounded hover:bg-transparent hover:text-blue-500 transition duration-150 ease-in-out">{{__('navigation.how_it_works')}}</Link>
            </li>
            <li class="show-for-large">
              <Link :href="route('interests')" class="btn_link text-white font-bold py-2 px-3 rounded hover:bg-transparent hover:text-blue-500 transition duration-150 ease-in-out">{{__('navigation.test')}}</Link>
            </li>
          </ul>
        </nav>
      </div>
      <div class="top-menu__right">
        <nav role="navigation" v-if="$page.props.canLogin">
          <ul class="top-menu__list">
            <li>
              <LanguageSelector :languages="$page.props.languages" :selected-language="$page.props.language" />
            </li>
            <template v-if="$page.props.auth.user">
              <li>
                <Link :href="route('dashboard')" class="button button--green hover:bg-transparent transition duration-150 ease-in-out">
                  {{ __('navigation.dashboard') }}
                </Link>
              </li>
            </template>
            <template v-else>
              <li>
                <Link :href="route('login')" class="button button--green before-fade-in fade-in m-1 py-2 px-3 transition duration-150 ease-in-out hide-for-small-only">
                  {{ __('navigation.log_in') }}
                </Link>
              </li>
              <li v-if="$page.props.canRegister">
                <Link :href="route('register')" class="button button--white before-fade-in fade-in transition duration-150 ease-in-out hide-for-small-only">
                  {{ __('navigation.register') }}
                </Link>
              </li>
            </template>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3';
import LanguageSelector from '@/Components/LanguageSelector.vue';

export default {
  components: {
    Link,
    router,
    LanguageSelector,
  },
  props: {
    laravelVersion: {
      type: String,
      required: true
    },
    phpVersion: {
      type: String,
      required: true
    },
  }
}
</script>
