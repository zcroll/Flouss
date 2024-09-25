<template>
  <div>
    <div class="grid-container process">
      <h2 class="text-center-left process__title">
        {{ __('process.title') }}
      </h2>
      <div v-for="(step, index) in steps" :key="index" class="grid-x grid-padding-x align-middle process__step">
        <div :class="['large-5 small-12 cell', index % 2 === 0 ? 'large-order-1 medium-order-2 small-order-2' : 'right-step']">
          <div :class="['large-icon', step.iconClass, 'before-fade-in fade-in']" />
          <h3 class="process__step-title before-fade-in fade-in">
            {{ __(`process.steps.${step.key}.title`) }}
          </h3>
          <p v-for="(paragraph, pIndex) in step.descriptionKeys" :key="pIndex" class="before-fade-in fade-in">
            {{ __(`process.steps.${step.key}.description.${paragraph}`) }}
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
            :alt="__(`process.steps.${step.key}.imageAlt`)"
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
          key: 'complete_assessments',
          iconClass: "how-works-one",
          imageSrc: "https://www.careerhunter.io/images/how_works/1.png",
          descriptionKeys: ['paragraph1', 'paragraph2']
        },
        {
          key: 'algorithm_processes',
          iconClass: "how-works-two",
          imageSrc: "https://www.careerhunter.io/images/how_works/2.png",
          descriptionKeys: ['paragraph1']
        },
        {
          key: 'career_matches',
          iconClass: "how-works-three",
          imageSrc: "https://www.careerhunter.io/images/how_works/3.png",
          descriptionKeys: ['paragraph1', 'paragraph2']
        },
        {
          key: 'course_job_suggestions',
          iconClass: "how-works-four",
          imageSrc: "https://www.careerhunter.io/images/how_works/4.png",
          descriptionKeys: ['paragraph1', 'paragraph2']
        },
        {
          key: 'career_report',
          iconClass: "how-works-fifth",
          imageSrc: "https://www.careerhunter.io/images/how_works/5.png",
          descriptionKeys: ['paragraph1', 'paragraph2']
        }
      ]
    };
  }
};
</script>

<style scoped>
@import url('//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css');
</style>