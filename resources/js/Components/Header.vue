<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <link rel="stylesheet" media="all"
        href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css" />
    <link rel="stylesheet" media="all"
        href="//cdn3.careerhunter.io/assets/main_tests-bd884139ec646feab4ac86d2f494e405033a3d476b5b9ac820fc8fbc6576c466.css" />
    <div class="grid-container results-page" id="testResults">
        <div class="grid-x">
            <div class="small-12 cell">
                <div class="breadcrumbs-nav">
                    <a href="/tests" class="">Tests</a>
                    <div class="small-icon arrow-breadcrumbs"></div>
                    <a href="/tests/interests/take" class="">Career Interests</a>
                    <div class="small-icon arrow-breadcrumbs"></div>
                    <span>Results</span>
                </div>
                <h1 class="results__test">Career Interests Results</h1>
                <p class="results__tip show-for-large"><span class="small-icons grey-tooltip-icon"></span><span
                        class="">1 of 6 tests complete.</span> Finish all to reveal your career matches.</p>
            </div>
        </div>
        <div class="grid-x results">
            <div class="large-7 medium-12 small-12 cell">
                <p class="ci-test-subtitle"><span>Lillith,</span> thank you for taking our test! You have completed one
                    of six psychometric tests and are a step closer to finding your ideal career.</p>
                <p class="results__tip hide-for-large"><span class="small-icons grey-tooltip-icon"></span><span>1 of 6
                        tests complete.</span> Finish all to reveal your career matches.</p>
            </div>
            <div class="large-5 medium-12 small-12 results__top-buttons cell">
                <div>

                </div>
                <div class="flex large-flex-end">
                    <div data-react-class="share/popup"
                        data-react-props="{&quot;score&quot;:96,&quot;title&quot;:&quot;Entertainment&quot;,&quot;share_text&quot;:&quot;I scored 96% for CareerHunter's Entertainment career interests.&quot;,&quot;email_body&quot;:&quot;I scored 96% for CareerHunter's Entertainment career interests\nAssess your ability to quickly identify logics of pattern by taking the test:\n&quot;,&quot;url&quot;:&quot;https://www.careerhunter.io/tests/interests&quot;,&quot;image&quot;:&quot;https://www.careerhunter.io/uploads/images/169/Entertainment.jpg&quot;,&quot;user_name&quot;:&quot;Lillith&quot;}"
                        data-react-cache-id="share/popup-0">
                        <div class="career-details__report career-details__report--share">
                            <p class="">Share my results</p>
                            <div class="small-icons share-white"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  
        <div class="grid-x tabs-slider results-tabs-slider">
            <div class="small-12 cell results__industries">
                <div class="slick-slider results-slider is-blur slick-initialized" dir="ltr">
                    <div class="slick-list">
                        <div class="slick-track"
                            style="width: 2128px; opacity: 1; transform: translate3d(0px, 0px, 0px);">
                            <div style="outline: none;" data-index="0" class="slick-slide slick-active slick-current"
                                tabindex="-1" aria-hidden="false">
                                <div class="">
                                    <div class="tabs-title is-active" tabindex="-1"
                                        style="width: 100%; display: inline-block;">
                                        <div class="card card--results card__overview-results" style="height: 80px;">
                                            <div class="card__overview">
                                                <div class="circle half-top"></div>
                                                <div class="circle small-right"></div>
                                                <div class="circle small-left"></div>
                                                <div class="circle half-bot"></div>
                                            </div>
                                            <div class="card__section">
                                                <div class="card__overview-icon">
                                                    <div class="large-icon white-search-paper"></div>
                                                </div>
                                                <h3 class="card-industry-name">Overview</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="1" class="slick-slide slick-active" tabindex="-1"
                                aria-hidden="false">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;">
                                        <div class="tabs-title">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/169/small-Entertainment.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_9">
                                                        <div class="inner">
                                                            <div class="fill fillAction_9"
                                                                style="transform: translate(0px, 2.4px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">96%</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name">Entertainment</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="2" class="slick-slide slick-active" tabindex="-1"
                                aria-hidden="false">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;">
                                        <div class="tabs-title">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/165/small-Construction-and-Trades.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_8">
                                                        <div class="inner">
                                                            <div class="fill fillAction_8"
                                                                style="transform: translate(0px, 7.2px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">88%</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name">Construction and Trades</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="3" class="slick-slide" tabindex="-1"
                                aria-hidden="true">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;" class="">
                                        <div class="tabs-title">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/159/small-Agriculture-and-Farming.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_8">
                                                        <div class="inner">
                                                            <div class="fill fillAction_8"
                                                                style="transform: translate(0px, 9.6px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">84%</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name">Agriculture and Horticulture</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="4" class="slick-slide" tabindex="-1"
                                aria-hidden="true">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;" class="">
                                        <div class="tabs-title blur-box">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/169/small-Entertainment.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_9">
                                                        <div class="inner">
                                                            <div class="fill fillAction_9"
                                                                style="transform: translate(0px, 6px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">?</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name"></h3>
                                                </div>
                                            </div><a href="/upgrade" class="results__full-access">Get access to reveal
                                                full results <span class="small-icon lock-bold-green"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="5" class="slick-slide" tabindex="-1"
                                aria-hidden="true">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;">
                                        <div class="tabs-title blur-box">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/165/small-Construction-and-Trades.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_9">
                                                        <div class="inner">
                                                            <div class="fill fillAction_9"
                                                                style="transform: translate(0px, 6px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">?</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name"></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="outline: none;" data-index="6" class="slick-slide" tabindex="-1"
                                aria-hidden="true">
                                <div>
                                    <div tabindex="-1" style="width: 100%; display: inline-block;">
                                        <div class="tabs-title blur-box">
                                            <div class="card card--results" data-equalizer-watch="true"
                                                style="height: 80px;">
                                                <div class="card__image"
                                                    style="background: url(&quot;https://www.careerhunter.io/uploads/images/159/small-Agriculture-and-Farming.jpg&quot;) center center / cover;">
                                                </div>
                                                <div class="card__section">
                                                    <div class="match-tank pulse_9">
                                                        <div class="inner">
                                                            <div class="fill fillAction_9"
                                                                style="transform: translate(0px, 6px);"><svg
                                                                    version="1.1" x="0px" y="0px" width="88px"
                                                                    height="88px" viewBox="0 0 88 88"
                                                                    enable-background="new 0 0 88 88">
                                                                    <path class="wave-shape" d="M300,300V2.5c0,0-0.6-0.1-1.1-0.1c0,0-25.5-2.3-40.5-2.4c-15,0-40.6,2.4-40.6,2.4
c-12.3,1.1-30.3,1.8-31.9,1.9c-2-0.1-19.7-0.8-32-1.9c0,0-25.8-2.3-40.8-2.4c-15,0-40.8,2.4-40.8,2.4c-12.3,1.1-30.4,1.8-32,1.9
c-2-0.1-20-0.8-32.2-1.9c0,0-3.1-0.3-8.1-0.7V300H300z"></path>
                                                                </svg></div>
                                                        </div>
                                                        <div class="match-percent hidden ">
                                                            <div class="qm">?</div>match
                                                        </div>
                                                    </div>
                                                    <h3 class="card-industry-name"></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              
                </div>
               
                </div>
        </div>

</template>
