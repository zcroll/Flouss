<template>
    <div>

      <Header  :canLogin="canLogin" :canRegister="canRegister" />
      <Hero />
      <Steps />
      <HowItWork />
      <Gardien />
      <Footer />
      </div>

  </template>

  <script>
  import { Link , usePage } from '@inertiajs/vue3';
  import Footer from "@/Components/Footer.vue"
  import Steps from "@/Components/Home/Steps.vue";
  import HowItWork from "@/Components/Home/HowItWork.vue";
  import Gardien from "@/Components/Home/Gardien.vue";
  import Header from "@/Components/Home/Navbar/Header.vue";
  import Hero from "@/Components/Home/Hero.vue";




  export default {
    name: 'App',
    components: {Header, Steps, HowItWork, Gardien, Footer, Link, usePage, Hero},
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
    @import '/public/css/Home.css';

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
