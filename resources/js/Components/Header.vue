<template>
    <header class="DashboardPage__header relative">
        <div class="header-content" :style="headerContentStyles">
            <div class="grid-container" :style="gridContainerStyles">
                <h1 class="DashboardPage__header__heading" :style="headingStyles">
                    {{ title }}
                </h1>
                <p :style="paragraphStyles">{{ subTitle }}</p>
            </div>
        </div>
        <div class="header-background" :style="[backgroundStyles, backgroundImage1]"></div>
        <slot name="navigation"></slot>
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
                textAlign: 'center',
                marginBottom: '16px',
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
                backgroundPosition: 'center center',
                zIndex: 1,
            }
        },
        backgroundImage1() {
            return {
                backgroundImage: `url("${this.backgroundImage}")`
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
</style>
