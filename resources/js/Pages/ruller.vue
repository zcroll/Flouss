<template>


  <link rel="icon" type="image/png" href="documentation/assets/img/favicon.png">

  <link href="//cloud.typenetwork.com/projects/2160/fontface.css/" rel="stylesheet" type="text/css">

  <link href="/animejs.css" rel="stylesheet">



  <section id="controls-and-callbacks" class="feature-section section-feature-control inverted-section">

    <div class="grid-container large-grid">

      <!-- <div class="feature-description">
        <h1 class="feature-title">Controls and callbacks</h1>
        <h2 class="feature-subtitle">
          Timing is everything.
        </h2>
      </div> -->

      <div class="feature-animation">

        <div class="time-control" style="transform: translateX(0px);">
          <div class="ruller">
            <div class="time-cursor color-red" style="transform: translateY(-24px) translateX(0px) translateZ(0px);">
              <input value="">
            </div>
          </div>
          <div class="timeline">
            <div class="info-loop-complete info info-large info-right info-top" data-delay="0">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">Technologies Agroalimentaires
              </figcaption>
            </div>
            <div class="info-loop-begin info info-large info-left info-bottom" data-delay="0">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">FST
              </figcaption>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">2 Ans
              </figcaption>
            </div>
            <div class="animation">
              <div class="animation-band animation-delay"></div>
              <div class="info-change-begin info info-left info-bottom" data-delay="100">
                <div class="info-bar" style="transform: scaleY(1);"></div>

                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">BAC+2 Complete
                </figcaption>
              </div>
              <div class="animation-band animation-change"></div>
              <div class="info-change-complete info info-right info-top" data-delay="400">
                <div class="info-bar" style="transform: scaleY(1);"></div>
                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">change complete
                </figcaption>
              </div>
              <div class="animation-band animation-end-delay"></div>
            </div>
            <div class="info-loop-complete info info-large info-right info-top" data-delay="500">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">loop 1 complete
              </figcaption>
            </div>
            <div class="info info-large info-left info-bottom" data-delay="500">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">reverse</figcaption>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">loop 2 begin
              </figcaption>
            </div>
            <div class="animation">
              <div class="animation-band animation-end-delay"></div>
              <div class="info-change-begin info info-left info-bottom" data-delay="600">
                <div class="info-bar" style="transform: scaleY(1);"></div>
                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">change begin
                </figcaption>
              </div>
              <div class="animation-band animation-change"></div>
              <div class="info-change-complete info info-right info-top" data-delay="900">
                <div class="info-bar" style="transform: scaleY(1);"></div>
                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">change complete
                </figcaption>
              </div>
              <div class="animation-band animation-delay"></div>
            </div>
            <div class="info-loop-complete info info-large info-right info-top" data-delay="1000">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">loop 2 complete
              </figcaption>
            </div>
            <div class="info info-large info-left info-bottom" data-delay="1000">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">reverse</figcaption>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">loop 3 begin
              </figcaption>
            </div>
            <div class="animation">
              <div class="animation-band animation-delay"></div>
              <div class="info-change-begin info info-left info-bottom" data-delay="1100">
                <div class="info-bar" style="transform: scaleY(1);"></div>
                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">change begin
                </figcaption>
              </div>
              <div class="animation-band animation-change"></div>
              <div class="info-change-complete info info-right info-top" data-delay="1400">
                <div class="info-bar" style="transform: scaleY(1);"></div>
                <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">change complete
                </figcaption>
              </div>
              <div class="animation-band animation-end-delay"></div>
            </div>
            <div class="info-loop-complete info info-large info-right info-top" data-delay="1500">
              <div class="info-bar" style="transform: scaleY(1);"></div>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">loop 3 complete
              </figcaption>
              <figcaption class="feature-caption" style="opacity: 1; transform: translateY(0px);">animation complete
              </figcaption>
            </div>
          </div>

        </div>

      </div>

      <div class="feature-description feature-description-grid">
        <div class="feature-description-icons">
          <div class="feature-icons-container vertical-icons">
            <figure class="feature-icon timeline-icon">
              <div class="animation-band animation-delay"></div>
              <figcaption class="feature-caption">delay</figcaption>
            </figure>
            <figure class="feature-icon timeline-icon">
              <div class="animation-band animation-end-delay"></div>
              <figcaption class="feature-caption">end-delay</figcaption>
            </figure>
            <figure class="feature-icon timeline-icon">
              <div class="animation-band animation-change"></div>
              <figcaption class="feature-caption">change</figcaption>
            </figure>
          </div>
        </div>
        <div class="feature-description-text">
          <p class="feature-paragraph">
            Play, pause, control, reverse and trigger events in sync using the complete built-in callbacks and controls
            functions.
          </p>
          <p class="feature-paragraph">
            <a href="documentation/#update" class="primary arrow">Learn more about callbacks</a>
          </p>
        </div>
      </div>

    </div>

  </section>
</template>

<script>
import anime from 'animejs';

export default {
  mounted() {
    var timeControlAnimation = (function () {

      var timeControlEl = document.querySelector('.time-control');
      var rullerEl = document.querySelector('.ruller');
      var timeCursorEl = document.querySelector('.time-cursor');
      var timeEl = document.querySelector('.time-cursor input');
      var infoEls = document.querySelectorAll('.info');
      var controlAnimationCanMove = true;

      var numberOfElements = 50;
      for (let i = 0; i <= numberOfElements; i++) {
        var dotEl = document.createElement('div');
        dotEl.classList.add('line');
        if (i % 10 === 0) {
          dotEl.setAttribute('data-value', i);
          dotEl.classList.add('line-marker');
        }
        rullerEl.appendChild(dotEl);
      }

      var animationPXOffset = (timeControlEl.offsetWidth - (timeControlEl.parentNode.offsetWidth - 20)) / 2;
      if (animationPXOffset < 0) animationPXOffset = 0;

      function pxToTime(px) {
        var width = window.innerWidth > rullerEl.offsetWidth ? rullerEl.offsetWidth + 180 : window.innerWidth;
        var percent = px / (width);
        return percent * (timelineAnimation.duration);
      }

      var time = {
        anim: null,
        start: 0,
        end: 0
      };

      function dragElement(el, events) {
        function getPointer(e) {
          var x = 'clientX';
          var evt = e.touches ? e.touches[0] : e;
          return { x: evt[x] };
        }

        var drag = { x: 0, deltaX: 0, active: true, events: events || {} };
        var originalX = 0;
        var pointerX = 0;

        function move(e) {
          if (drag.active) return;
          drag.deltaX = pointerX - getPointer(e).x;
          drag.x = originalX - drag.deltaX;

          var currentTime = time.start + pxToTime(-drag.deltaX);
          var progress = (currentTime / timelineAnimation.duration) * 100;

          if (drag.x <= -5 || drag.x >= 1090) {
            if (drag.x <= 0) {
              drag.x = 0;
              progress = 2024;
            }
            if (drag.x >= 1080) {
              drag.x = 1080;
              progress = 2029;
            }

            timeEl.value = Math.round(progress);
            timeCursorEl.style.transform = `translateY(-24px) translateX(${drag.x}px) translateZ(0px)`;

            drag.active = true;
            if (drag.events.release) drag.events.release(drag);
            document.removeEventListener('mousemove', move, false);
            document.removeEventListener('mouseup', release, false);
            document.removeEventListener('touchmove', move, false);
            document.removeEventListener('touchend', release, false);
            return;
          }

          if (drag.events.move) drag.events.move(drag);
        }

        function release(e) {
          drag.active = true;
          if (drag.events.release) drag.events.release(drag);
          document.removeEventListener('mousemove', move, false);
          document.removeEventListener('mouseup', release, false);
          document.removeEventListener('touchmove', move, false);
          document.removeEventListener('touchend', release, false);
        }

        function start(e) {
          if (!drag.active) return;
          e.preventDefault();
          drag.active = false;
          pointerX = getPointer(e).x;
          originalX = drag.x;
          if (drag.events.begin) drag.events.begin(drag);
          document.addEventListener('mousemove', move, false);
          document.addEventListener('mouseup', release, false);
          document.addEventListener('touchmove', move, false);
          document.addEventListener('touchend', release, false);
        }

        el.addEventListener('mousedown', start, false);
        el.addEventListener('touchstart', start, false);

        return drag;
      }

      var drag = dragElement(timeCursorEl, {
        begin: function (e) {
          anime.remove(time);
          time.start = timelineAnimation.currentTime;
          controlAnimationCanMove = false;
        },
        move: function (e) {
          var currentTime = time.start + pxToTime(-drag.deltaX);
          timelineAnimation.seek(currentTime);
          var progress = Math.round(2024 + (currentTime / timelineAnimation.duration) * (2029 - 2024));
          timeEl.value = Math.round(progress);
          timeCursorEl.style.transform = `translateY(-10px) translateX(${drag.x}px) translateZ(0px)`;
        },
        release: function (e) {
          controlAnimationCanMove = true;
        }
      });

      var timelineAnimation = anime.timeline({
        easing: 'linear',
        autoplay: false
      })
        .add({
          targets: timeControlEl,
          translateX: [animationPXOffset, -animationPXOffset],
          duration: 1500
        }, 0)
        .add({
          targets: timeCursorEl,
          translateZ: 0,
          keyframes: [
            { translateY: [-24, 0], duration: 100, easing: 'easeInQuad' },
            { translateX: 1080, duration: 1500 },
            { translateY: -24, duration: 100, easing: 'easeOutQuad' }
          ],
          duration: 1500
        }, -100)
        .add({
          targets: '.ruller .line',
          translateY: [{ value: 24 }, { value: 0 }],
          duration: 160,
          delay: anime.stagger([0, 1500]),
          easing: 'easeInOutSine'
        }, -80)
        .add({
          targets: timeEl,
          value: [2024, 2030],
          duration: 1500,
          round: 1,
          easing: 'linear'
        }, 0)

      for (var i = 0; i < infoEls.length; i++) {
        var infoEl = infoEls[i];
        var delay = parseFloat(anime.get(infoEl, 'data-delay'));
        var direction = infoEl.classList.contains('info-bottom') ? -1 : 1;
        timelineAnimation
          .add({
            targets: infoEl.querySelector('.info-bar'),
            scaleY: [0, 1],
            duration: 250,
            easing: 'easeOutCirc'
          }, delay)
          .add({
            targets: infoEl.querySelectorAll('.info .feature-caption'),
            opacity: [0, 1],
            translateY: [direction * 10, 0],
            duration: 50,
            delay: anime.stagger(50, { start: 10, direction: direction > 0 ? 'reverse' : 'normal' }),
            easing: 'easeOutSine'
          }, delay)
      }

      var windowHeight = window.innerHeight;

      function isElementInViewport(el, inCB, outCB, rootMargin) {
        var margin = rootMargin || '-10%';
        function handleIntersect(entries, observer) {
          var entry = entries[0];
          if (entry.isIntersecting) {
            if (inCB && typeof inCB === 'function') inCB(el, entry);
          } else {
            if (outCB && typeof outCB === 'function') outCB(el, entry);
          }
        }
        var observer = new IntersectionObserver(handleIntersect, {rootMargin: margin});
        observer.observe(el);
      }

      isElementInViewport(timeControlEl, function (el, entry) {
        windowHeight = window.innerHeight;
        controlAnimationCanMove = true;
      }, function (el, entry) {
        controlAnimationCanMove = false;
      }, '50px');

    })();
  }
};
</script>

