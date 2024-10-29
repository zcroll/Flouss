<template>
    <div>

      <Header  :canLogin="canLogin" :canRegister="canRegister"/>
      <HomePage />

      <Footer />
    </div>

  </template>

  <script>
  import { Link , usePage } from '@inertiajs/vue3';
  import Content from "@/Components/Content.vue";
  import NavBar from "@/Pages/NavBar.vue";
  import Footer from "@/Components/Footer.vue"
  import Section from "@/Pages/HomePage/Section.vue";
  import HowItWork from "@/Components/Home/HowItWork.vue";
  import Im from "@/Pages/HomePage.vue";
  import HomePage from "@/Pages/HomePage.vue";
  import Header from "@/Components/Home/Navbar/Header.vue";




  export default {
    name: 'App',
    components: {Header, HomePage, Im, HowItWork, Section, NavBar, Content, Footer, Link, usePage},
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
<style>

.module {
  margin-top: 20px;
  max-width: 250px;
  padding: 1rem;
  color: white;
  border-width: 3px;
  border-style: solid;
  border-image:
    linear-gradient(
      to bottom,
      #fb6302,
      rgba(0, 0, 0, 0)
    ) 1 100%;
}
</style>
