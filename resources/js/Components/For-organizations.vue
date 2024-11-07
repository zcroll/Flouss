<script setup>
import { onMounted } from 'vue';
import anime from 'animejs';

onMounted(() => {
  // Animate SVG paths
  const timeline = anime.timeline({
    loop: true,
    direction: 'alternate'
  });

  timeline
    .add({
      targets: '.Organizations__header__bg path:nth-child(1)', 
      translateX: [-10, 10],
      translateY: [-5, 5],
      rotate: [-1, 1],
      duration: 4000,
      easing: 'easeInOutQuad'
    })
    .add({
      targets: '.Organizations__header__bg path:nth-child(2)',
      translateX: [5, -5],
      translateY: [10, -10], 
      rotate: [1, -1],
      duration: 3500,
      easing: 'easeInOutQuad'
    }, '-=4000')
    .add({
      targets: '.Organizations__header__bg path:nth-child(3)',
      translateX: [-8, 8],
      translateY: [8, -8],
      rotate: [-2, 2], 
      duration: 4500,
      easing: 'easeInOutQuad'
    }, '-=3500');
});
</script>

<template>
  <div class="mehero__wrap mehero__wrap--orgs">
    <div class="mehero__wrap__inner">
      <svg class="Organizations__header__bg" width="766" height="706" viewBox="0 0 766 706" fill="none">
        <path opacity="0.65" fill-rule="evenodd" clip-rule="evenodd" d="M444.131 327.152C500.409 290.742 545.935 248.683 607.834 261.181C666.907 273.107 714.796 299.465 762.798 328.921C826.817 368.207 923.52 390.452 933.546 461.056C943.525 531.328 861.491 594.814 809.433 657.326C760.175 716.477 712.175 776.703 640.1 813.433C562.095 853.186 467.266 895.586 391.356 870.382C315.879 845.322 322.068 750.247 277.528 693.051C238.601 643.062 130.284 622.052 147.891 557.733C167.015 487.872 297.14 486.778 350.104 427.922C394.555 378.526 386.292 364.572 444.131 327.152Z" fill="#5E5141"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M579.037 275.789C611.709 317.722 648.446 350.67 641.416 400.844C634.706 448.728 615.62 488.834 594.005 529.268C565.178 583.194 551.969 661.79 494.751 675.208C437.802 688.563 381.663 628.243 327.864 591.679C276.957 557.081 225.237 523.568 191.489 469.101C154.965 410.153 115.409 338.035 132.108 275.742C148.712 213.803 226.776 211.388 271.252 171.567C310.124 136.763 321.725 49.0294 375.226 58.0647C433.339 67.8786 440.936 171.245 491.792 208.811C534.474 240.339 545.459 232.693 579.037 275.789Z" fill="#E4D5C6" fill-opacity="0.65"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M622.228 573.148C583.029 598.6 551.393 628.291 507.974 618.343C466.537 608.848 432.848 589.1 399.062 567.112C354.003 537.788 286.154 520.281 278.723 469.147C271.326 418.254 328.404 373.634 364.496 329.271C398.647 287.293 431.911 244.519 482.17 219.072C536.564 191.531 602.725 162.33 656.029 181.663C709.03 200.886 705.249 269.449 736.771 311.415C764.321 348.094 840.295 364.883 828.339 411.066C815.353 461.228 724.236 460.075 687.488 501.785C656.647 536.79 662.514 546.99 622.228 573.148Z" fill="#7B6E61"/>
      </svg>

      <div class="mehero__text" 
           data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c20" 
           style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
        <h1 class="productive-heading-07 override">
          CareerExplorer for Organizations<br>
        </h1>
        <div class="mehero__text__floating">
          <p class="productive-heading-04">
            Empower your students with comprehensive career discovery tools.<br>
          </p>
          <p class="body-short-02">
            A self-serve platform for universities, high schools, independent educational consultants, career counselors, non-profits, and other organizations.
          </p>
        </div>
      </div>
      <div class="mehero__img mehero__img--discord mehero__img--orgs"
           data-w-id="77c9ae79-3fb1-a629-2d1d-1b9d0a9d8c34"
           style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg); transform-style: preserve-3d;">
        <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits.png"
             loading="lazy"
             sizes="(max-width: 991px) 100vw, 45vw"
             width="570"
             alt="CareerExplorer for Organizations"
             srcset="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-500.png 500w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-800.png 800w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits-p-1080.png 1080w, https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/609d67af9f365d9ba4e158e2_portraits.png 1140w"
             class="mehero__img__img mehero__img__img--discord">
      </div>
    </div>
  </div>
</template>

<style scoped>
.Organizations__header__bg {
  will-change: transform;
  position: absolute;
  top: 0;
  right: 0;
  z-index: -1;
}

.Organizations__header__bg path {
  transform-origin: center;
  will-change: transform;
}
</style>
