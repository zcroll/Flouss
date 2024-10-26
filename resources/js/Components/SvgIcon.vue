<template>
  <svg :class="['svg-inline--fa', className]" aria-hidden="true" focusable="false" role="img" :viewBox="viewBox" xmlns="http://www.w3.org/2000/svg">
    <path :d="pathData" :fill="fillColor" />
  </svg>
</template>

<script>
export default {
  name: 'SvgIcon',
  props: {
    icon: {
      type: String,
      required: true,
    },
    className: {
      type: String,
      default: '',
    },
    viewBox: {
      type: String,
      default: '0 0 448 512',
    },
    pathData: {
      type: String,
      default: '',
    },
    fillColor: {
      type: String,
      default: 'currentColor',
    },
  },
  computed: {
    computedPathData() {
      // Fetch path data based on the icon prop.  This would require a mapping
      // from icon names to path data, either in a data object or fetched from an API.
      switch (this.icon) {
        case 'chevron-up':
          return 'M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z';
        case 'chevron-down':
          return 'M4.251 181.1C7.392 177.7 11.69 175.1 16 175.1c3.891 0 7.781 1.406 10.86 4.25l197.1 181.1l197.1-181.1c6.5-6 16.64-5.625 22.61 .9062c6 6.5 5.594 16.59-.8906 22.59l-208 192c-6.156 5.688-15.56 5.688-21.72 0l-208-192C-1.343 197.7-1.749 187.6 4.251 181.1z';
        default:
          return '';
      }
    },
  },
};
</script>
