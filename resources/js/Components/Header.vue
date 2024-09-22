<template>
    <header class="DashboardPage__header relative">
        <div class="header-content" :style="headerContentStyles">
            <div class="grid-container">
               <div
                    class="grid-x"
                    style="
                        box-sizing: inherit;
                        margin: 0px;
                        padding: 0px;
                        flex-flow: wrap;
                        display: flex;
                        -moz-box-orient: horizontal;
                        -moz-box-direction: normal;
                    "
                >
                    <div
                        class="small-12 cell"
                        style="
                            box-sizing: inherit;
                            margin: 0px;
                            padding: 0px;
                            min-height: 0px;
                            min-width: 0px;
                            flex: 0 0 auto;
                            -moz-box-flex: 0;
                            width: 100%;
                        "
                    >
                   
                        <h1
                            class="results__test"
                            style="
                                box-sizing: inherit;
                                font-weight: 400;
                                text-rendering: optimizelegibility;
                                padding: 0px;
                                color: rgb(255, 255, 255);
                                font-family: sofia-pro-semi-bold, sans-serif;
                                margin: 10px 0px 0px;
                                font-size: 34px;
                                line-height: 40px;
                            "
                        >
                            Career Interests Results
                        </h1>
                        <p
                            class="results__tip show-for-large"
                            style="
                                box-sizing: inherit;
                                font-weight: 400;
                                text-rendering: optimizelegibility;
                                margin: 0px;
                                padding: 0px;
                                margin-bottom: 16px;
                                float: right;
                                color: rgba(255, 255, 255, 0.5);
                                font-size: 12px;
                                line-height: 16px;
                                font-family: sofia-pro-regular, sans-serif;
                            "
                        >
                            <span
                                class="small-icons grey-tooltip-icon"
                                style="
                                    box-sizing: inherit;
                                    background: url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg');
                                    font-family: sofia-pro-bold, sans-serif;
                                    background-position: -502px -14px;
                                    width: 16px;
                                    height: 16px;
                                    display: inline-block;
                                    margin: 0px 5px -2px 0px;
                                "
                            ></span>
                            <span
                                style="
                                    box-sizing: inherit;
                                    font-family: sofia-pro-bold, sans-serif;
                                "
                                >1 of 6 tests complete.</span
                            >
                            Finish all to reveal your career matches.
                        </p>
                    </div>
                </div>
               <p
                    class="ci-test-subtitle"
                    style="
                        box-sizing: inherit;
                        font-weight: 400;
                        text-rendering: optimizelegibility;
                        padding: 0px;
                        color: rgb(255, 255, 255);
                        margin: 0px;
                        font-size: 20px;
                        line-height: 28px;
                        font-family: sofia-pro-regular, sans-serif;
                        margin-bottom: 0px;
                        max-width: 713px;
                    "
                >
                    <span
                        style="
                            box-sizing: inherit;
                            font-family: sofia-pro-bold, sans-serif;
                        "
                    >
                        Lillith,
                    </span>
                    thank you for taking our test! You have completed one of six
                    psychometric tests and are a step closer to finding your ideal career.
                </p>
              

               <Jobs/>
               
            </div>
        </div>
    

        <div class="header-background" :style="[backgroundStyles, backgroundImage1]"></div>
        <slot name="navigation"></slot>

        <!-- Circular divs with images -->

    </header>
</template>

<script>
import Jobs from '@/Components/JObs.vue';
export default {
    props: {
        title: {
            type: String,
        },
        subTitle: {
            type: String,
        },
        backgroundImage: {
            type: String,
            default: 'https://res.cloudinary.com/hnpb47ejt/image/upload/v1597868368/gskikddqvxyveneusb9y'
        },
        showSearch: {
            type: Boolean,
            default: false
        },
        name: {
            type: String,
        }
      },
    components: {
        Jobs
    }
}
</script>

<style scoped>
.DashboardPage__header {
    position: relative;
    overflow: hidden;
    min-height: 300px;
}
.custom-heading {
    box-sizing: border-box;
    margin: 0px 0px 10px;
    font-weight: 300;
    color: #797973;
    padding-top: 0px;
    letter-spacing: -0.3px;
    font-size: 32px;
    line-height: 36px;
    margin-bottom: 30px;
    font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;
}

.search-bar {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 20px;
    padding: 0.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.search-input {
    flex-grow: 1;
    border: none;
    outline: none;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    background: transparent;
}

.search-button {
    background-color: #4a90e2;
    color: white;
    border: none;
    border-radius: 50%;
    padding: 0.25rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-button:hover {
    background-color: #3a7bc8;
}
</style>