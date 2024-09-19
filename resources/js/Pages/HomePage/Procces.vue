<template>
  <div>
    <div class="grid-container process">
      <h2 class="text-center-left process__title">
        The process
      </h2>
      <div v-for="(step, index) in steps" :key="index" class="grid-x grid-padding-x align-middle process__step">
        <div :class="['large-5 small-12 cell', index % 2 === 0 ? 'large-order-1 medium-order-2 small-order-2' : 'right-step']">
          <div :class="['large-icon', step.iconClass, 'before-fade-in fade-in']" />
          <h3 class="process__step-title before-fade-in fade-in">
            {{ step.title }}
          </h3>
          <p v-for="(paragraph, pIndex) in step.description" :key="pIndex" class="before-fade-in fade-in">
            {{ paragraph }}
          </p>
          <div class="process__step--numbers">
            {{ index + 1 }}
          </div>
        </div>
        <div :class="['large-7 cell', index % 2 === 0 ? 'large-order-2 medium-order-1 small-order-1' : '', 'process__step-img-blk']">
          <img
            :class="['process__step-img', index % 2 === 0 ? 'float-right' : '', 'before-fade-in fade-in']"
            height="351"
            width="589"
            :alt="step.imageAlt"
            :src="step.imageSrc"
          />
        </div>
      </div>
    </div>
    <FooterProcces />
  </div>
</template>

<script>
import FooterProcces from './FooterProcces.vue';

export default {
  name: 'Procces',
  components: {
    FooterProcces
  },
  data() {
    return {
      steps: [
        {
          title: "Complete the six assessments",
          description: [
            "CareerHunter is made up of a series of tests which examine different areas of your personal profile, specifically your interests, personality, motivations, and abstract, numerical and verbal reasoning skills.",
            "Each test is taken separately, and you can see personalized results as you complete each one, but you'll need to complete all six tests to generate your career, course and job matches."
          ],
          iconClass: "how-works-one",
          imageSrc: "https://www.careerhunter.io/images/how_works/1.png",
          imageAlt: "A user taking one of our six psychometric career tests"
        },
        {
          title: "Our algorithm processes your results",
          description: [
            "Using our unrivaled and ever-evolving algorithm, our advanced technology compiles and analyzes all your aptitude, interest, personality and motivator results to match your scores against the specific attributes and requirements of each career profile in our database."
          ],
          iconClass: "how-works-two",
          imageSrc: "https://www.careerhunter.io/images/how_works/2.png",
          imageAlt: "The algorithm working on processing the users tests to provide them with their perfect career results"
        },
        {
          title: "Your career matches are revealed",
          description: [
            "You'll then get accurate career matches that are uniquely suited to your combination of test results. We match you to relevant and popular careers that best fit your interests, personality, motivations and skills.",
            "Each match is accompanied by a percentage score, explaining how you match, and you can explore each match further with our detailed career profiles."
          ],
          iconClass: "how-works-three",
          imageSrc: "https://www.careerhunter.io/images/how_works/3.png",
          imageAlt: "User discovering their new found career after completing our tests"
        },
        {
          title: "View course and job suggestions",
          description: [
            "You'll also receive relevant course and job recommendations based on your career matches and test scores.",
            "We've partnered with some of the world's leading online learning platforms, like Coursera and FutureLearn, to bring you relevant study options, while our jobs come direct from top providers and recruiters."
          ],
          iconClass: "how-works-four",
          imageSrc: "https://www.careerhunter.io/images/how_works/4.png",
          imageAlt: "Take our recommended courses to start working towards a new career"
        },
        {
          title: "Get your career report",
          description: [
            "Your full and personalized career report can be viewed within your dashboard or downloaded as a PDF once you've completed all six tests.",
            "The report compiles and details all your career matches, how you match, along with other helpful insights into each career profile."
          ],
          iconClass: "how-works-fifth",
          imageSrc: "https://www.careerhunter.io/images/how_works/5.png",
          imageAlt: "A happy CareerHunter user downloading their personalized career report"
        }
      ]
    };
  }
};
</script>

<style scoped>
@import url('//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css');
</style>