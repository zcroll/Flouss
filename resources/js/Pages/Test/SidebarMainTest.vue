<template>
  <div class="assessment-progress minimized">
    <div id="journey" class="assessment-progress--inner">
      <div class="Journey">
        <div class="Journey--maximized">
          <div class="Journey__toggle">
            <svg class="svg-inline--fa fa-chevron-up" aria-hidden="true" focusable="false" role="img" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
              <path d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" fill="currentColor" />
            </svg>
          </div>
          <h2 class="Journey__head sr-only">Your Progress</h2>
          <div class="Journey__body">
            <div class="Journey__milestone--complete">
              <div class="Journey__milestone__map">
                <div class="Journey__milestone__map__marker">
                  <div class="Journey__milestone__map__marker__icon fas fa-check" />
                </div>
              </div>
              <div class="Journey__milestone__body">
                <h3 class="Journey__milestone__name">Start</h3>
              </div>
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__map">
                <div class="Journey__milestone__map__marker">
                  <div class="Journey__milestone__map__marker__icon fas fa-check" />
                </div>
                <div class="Journey__milestone__map__trail">
                  <div class="Journey__milestone__map__trail__progress" />
                </div>
              </div>
              <div class="Journey__milestone__body">
                <h3 class="Journey__milestone__name">Your personality archetype</h3>
                <div class="Journey__milestone__time">
                  <div class="Journey__milestone__time__text">~ 3 mins</div>
                </div>
                <div>
                  <p class="Journey__milestone__description" />
                  <p class="Journey__milestone__description" />
                </div>
              </div>
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__map">
                <div class="Journey__milestone__map__marker">
                  <div class="Journey__milestone__map__marker__icon fas fa-check" />
                </div>
                <div class="Journey__milestone__map__trail">
                  <div class="Journey__milestone__map__trail__progress" />
                </div>
              </div>
              <div class="Journey__milestone__body">
                <h3 class="Journey__milestone__name">Your career matches</h3>
                <div class="Journey__milestone__time">
                  <div class="Journey__milestone__time__text">~ 3 mins</div>
                </div>
                <div>
                  <p class="Journey__milestone__description" />
                </div>
              </div>
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__map">
                <div class="Journey__milestone__map__marker">
                  <div class="Journey__milestone__map__marker__icon fas fa-check" />
                </div>
                <div class="Journey__milestone__map__trail">
                  <div class="Journey__milestone__map__trail__progress" />
                </div>
              </div>
              <div class="Journey__milestone__body">
                <h3 class="Journey__milestone__name">Your degree matches</h3>
                <div class="Journey__milestone__time">
                  <div class="Journey__milestone__time__text">~ 1 min</div>
                </div>
                <div>
                  <p class="Journey__milestone__description" />
                </div>
              </div>
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__map">
                <div class="Journey__milestone__map__marker">
                  <div class="Journey__milestone__map__marker__icon fas fa-check" />
                </div>
                <div class="Journey__milestone__map__trail">
                  <div class="Journey__milestone__map__trail__progress" />
                </div>
              </div>
              <div class="Journey__milestone__body">
                <h3 class="Journey__milestone__name">Your Results</h3>
                <div class="Journey__milestone__time">
                  <div class="Journey__milestone__time__text">~ 20 mins</div>
                </div>
                <div>
                  <p class="Journey__milestone__description">Final career matches and insights</p>
                  <p class="Journey__milestone__description">Personality report</p>
                  <p class="Journey__milestone__description">Trait report</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="Journey--minimized">
          <div>
            <div class="Journey__head">Up Next</div>
            <div class="Journey__next-name">Your personality archetype</div>
          </div>
          <div class="Journey__right">
            <div class="Journey__milestone__time">
              <div class="Journey__milestone__time__text">~ 3 mins</div>
            </div>
            <div class="Journey__toggle">
              <svg class="svg-inline--fa fa-chevron-up" aria-hidden="true" focusable="false" role="img" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                <path d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" fill="currentColor" />
              </svg>
            </div>
          </div>
          <div class="Journey__progress">
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__trail">
                <div class="Journey__milestone__trail__progress" />
              </div>
              <div class="Journey__milestone__marker" />
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__trail">
                <div class="Journey__milestone__trail__progress" />
              </div>
              <div class="Journey__milestone__marker" />
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__trail">
                <div class="Journey__milestone__trail__progress" />
              </div>
              <div class="Journey__milestone__marker" />
            </div>
            <div class="Journey__milestone--not-started">
              <div class="Journey__milestone__trail">
                <div class="Journey__milestone__trail__progress" />
              </div>
              <div class="Journey__milestone__marker" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SidebarMainTest',
}
</script>

<style scoped>
</style>
