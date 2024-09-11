<template>
    <header class="DashboardPage__header relative">
        <div class="header-content" :style="headerContentStyles">
            <div class="grid-container" :style="gridContainerStyles">
                <h1 class="DashboardPage__header__heading custom-heading" :style="headingStyles">
                    {{ title }}
                </h1>
                <p v-if="subTitle" :style="paragraphStyles">{{ subTitle }}</p>
            </div>
        </div>
        <div class="circular-image" :style="[circularImageStyles, circularImage1]"></div>
        <div class="circular-image" :style="[circularImageStyles, circularImage2]"></div>
        <div class="circular-image" :style="[circularImageStyles, circularImage3]"></div>
        <div class="circular-image" :style="[circularImageStyles, circularImage4]"></div>
        <div class="circular-image" :style="[circularImageStyles, circularImage5]"></div>

        <div class="header-background" :style="[backgroundStyles, backgroundImage1]"></div>
        <slot name="navigation"></slot>

        <!-- Circular divs with images -->

    </header>
</template>

<script>
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
        }
    },
    computed: {
        headerContentStyles() {
            return {
                position: 'relative',
                zIndex: 2,
                padding: '80px 0 40px', // Increased top padding to accommodate navigation
            }
        },
        headingStyles() {
            return {

                fontSize: '48px',
                fontWeight: '600',
                color: '#ffffff',
                textAlign: 'left',
                marginTop: '60px',
                marginLeft: '233px',
            }
        },
        paragraphStyles() {
            return {
                fontSize: '18px',
                color: '#ffffff',
                textAlign: 'center',
            }
        },
        backgroundStyles() {
            return {
                position: 'absolute',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundSize: 'cover',
                backgroundPosition: 'center center', // Make sure background is behind the circular images
            }
        },
        backgroundImage1() {
            return {
                backgroundImage: `url("${this.backgroundImage}")`
            }
        },
        circularImageStyles() {
            return {

                backgroundSize: 'cover',
                backgroundPosition: 'center',
                position: 'absolute',
                zIndex: 2,
                // Ensure images are on top of the header background
            }
        },
        circularImage1() {
            return {
                width: '150px', // Adjust size as needed
                height: '150px', // Adjust size as needed
                borderRadius: '50%',
                top: '30%',
                right: '10%',
                backgroundImage: `url('/images_options/student5.webp')` // Update with your image path
            }
        },
        circularImage2() {
            return {
                width: '130px', // Adjust size as needed
                height: '130px', // Adjust size as needed
                borderRadius: '50%',
                top: '10%',
                right: '38%',
                backgroundImage: `url('/images_options/student.webp')` // Update with your image path
            }
        },
        circularImage3() {
            return {
                width: '130px', // Adjust size as needed
                height: '130px', // Adjust size as needed
                borderRadius: '50%',
                bottom: '-10%',
                right: '30%',
                backgroundImage: `url('/images_options/student3.webp')` // Update with your image path
            }
        },
        circularImage4() {
            return {
                width: '100px', // Adjust size as needed
                height: '100px', // Adjust size as needed
                borderRadius: '50%',
                bottom: '-4%',
                right: '18%',
                backgroundImage: `url('/images_options/student2.webp')` // Update with your image path
            }
        },
        circularImage5() {
            return {
                width: '200px', // Adjust size as needed
                height: '200px', // Adjust size as needed
                borderRadius: '50%',
                bottom: '25%',
                right: '21%',
                backgroundImage: `url('/images_options/student1.webp')` // Update with your image path
            }
        }
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

</style>
