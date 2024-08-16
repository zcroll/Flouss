<template>

    <header
        class="DashboardPage__header DashboardPage__header--first-time"
        :style="headerStyles"
    >
        <div
            class="page-head page-head--first-time-dash"
            :style="pageHeadStyles"
            v-if="route().current('results')"
        >
            <div
                class="grid-container"
                :style="gridContainerStyles"
            >
                <h1
                    class="DashboardPage__header__heading"
                    :style="headingStyles"
                >
                    Your results
                </h1>
                <p  :style="paragraphStyles">
                    You’ve completed all the assessments. Take a look at your results below.
                </p>
            </div>

            <div
                :style="[backgroundStyles,backgroundImage1]"
            />
        </div>

        <div
            class="page-head page-head--first-time-dash"
            :style="pageHeadStyles"
            v-else-if="route().current('dashboard')"
        >
            <div
                class="grid-container"
                :style="gridContainerStyles"
            >
                <h1
                    class="DashboardPage__header__heading"
                    :style="headingStyles"
                >
                    Welcome to your Orientation Center
                </h1>
                <p  :style="paragraphStyles">
                    Explore beyond your limitations !
                </p>
            </div>

            <div
                :style="[backgroundStyles,backgroundImage3]"
            />
        </div>

        <div
            class="page-head page-head--first-time-dash"
            :style="pageHeadStyles"
            v-else-if="route().current('activities')"
        >
            <div
                class="grid-container"
                :style="gridContainerStyles"
            >
                <h1
                    class="DashboardPage__header__heading"
                    :style="headingStyles"
                >
                    Career Test
                </h1>
                <p  :style="paragraphStyles">
                    take your time and answer with ease !
                </p>
            </div>

            <div
                :style="[backgroundStyles,backgroundImage2]"
            />
        </div>
    </header>
</template>

<script>
export default {
    data() {
        return {
            headerStyles: {
                boxSizing: "border-box",
                background: "rgb(27, 27, 27)",
                textAlign: "center",

            },
            pageHeadStyles: {
                boxSizing: "border-box",
                margin: "0px",
                color: "white",
                backgroundColor: "rgb(27, 27, 27)",
                padding: "80px 12px 50px",
                paddingTop: "80px",
                paddingBottom: "80px",
                position: "relative",
            },
            gridContainerStyles: {
                margin: "0px",
                boxSizing: "border-box",
                width: "100%",
                marginLeft: "auto",
                marginRight: "auto",
                padding: "0px",
                maxWidth: "100%",
                paddingRight: "0px",
                paddingLeft: "0px",
                position: "relative",
                zIndex: 1,
            },
            headingStyles: {
                boxSizing: "border-box",
                padding: "0px",
                fontStyle: "normal",
                textRendering: "optimizelegibility",
                margin: "21px auto",
                color: "rgb(255, 255, 255)",
                maxWidth: "1200px",
                marginBottom: "21px",
                textShadow: "rgba(0, 0, 0, 0.1) 0px 2px 4px",
                fontWeight: 300,
                marginTop: "70px",
                fontSize: "56px",
                lineHeight: "66px",
                textAlign: "center",
                letterSpacing: "-1.41px",
                fontFamily: 'aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif',
            },
            paragraphStyles: {
                boxSizing: "border-box",
                margin: "0px",
                padding: "0px",
                fontWeight: "normal",
                marginBottom: "1.42857rem",
                textRendering: "optimizelegibility",
                textShadow: "rgba(0, 0, 0, 0.1) 0px 2px 4px",
                fontSize: "16px",
                lineHeight: "25px",
                fontFamily: 'aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif',
            },
            backgroundStyles: {
                boxSizing: "border-box",
                margin: "0px",
                padding: "0px",
                inset: "0px",
                backgroundPosition: "center center",
                position: "absolute",
                width: "100%",
                backgroundRepeat: "no-repeat",
                backgroundSize: "cover",
            },
            backgroundImage1:{
                backgroundImage: 'url("https://res.cloudinary.com/hnpb47ejt/image/upload/v1597868368/gskikddqvxyveneusb9y")'
            },
            backgroundImage2:{
                backgroundImage: 'url("https://res.cloudinary.com/hnpb47ejt/image/upload/v1591305946/rm6cdoxhlhtormnrlamm")'
            },
            backgroundImage3:{
                backgroundImage: 'url("https://res.cloudinary.com/hnpb47ejt/image/upload/v1591305342/k5k3lhmaqvajchyaiqfr")'
            }

        };
    },
};
</script>

<style scoped>
html {
    box-sizing: border-box;
    height: 100%;
    overflow-x: hidden;
    color: rgb(36, 36, 36);
    font-family: aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    font-size: 16px;
}

body {
    box-sizing: border-box;
    height: 100%;
    background: rgb(242, 242, 242);
    margin: 0px;
    padding: 0px;
    color: rgb(121, 121, 115);
    cursor: auto;
    font-style: normal;
    font-weight: normal;
    line-height: 1.5;
    position: relative;
    overflow-x: hidden;
    font-size: 100%;
    background-color: rgb(248, 248, 241);
    font-family: aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>







