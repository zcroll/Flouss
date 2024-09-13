<template>
    <header class="DashboardPage__header relative">
        <div class="header-content" :style="headerContentStyles">
            <div class="grid-container">
                <h1 class="DashboardPage__header__heading custom-heading" :style="headingStyles">
                    {{ title }}
                </h1>
                <p v-if="subTitle" :style="paragraphStyles">{{ subTitle }}</p>
                
                <!-- Search bar -->
                <div v-if="showSearch" class="search-bar" :style="searchBarStyles">
                    <input 
                        type="text" 
                        v-model="searchQuery" 
                        :placeholder="`Search ${name}...`"
                        @input="$emit('search', searchQuery)"
                        class="search-input"
                    >
                    <button @click="$emit('search', searchQuery)" class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
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
import { ref } from 'vue';

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
    computed: {
        headerContentStyles() {
            return {
                position: 'relative',
                zIndex: 6,
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
                textAlign: 'left',
                marginLeft: '233px'
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
                backgroundImage: `url('/images_options/student5.webp')`,
                zIndex: 3,// Update with your image path
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
                backgroundImage: `url('/images_options/student3.webp')`,
                zIndex: 4,// Update with your image path
            }
        },
        circularImage4() {
            return {
                width: '100px', // Adjust size as needed
                height: '100px', // Adjust size as needed
                borderRadius: '50%',
                bottom: '-4%',
                right: '18%',
                backgroundImage: `url('/images_options/student2.webp')`,
                zIndex: 5,// Update with your image path
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
        },
        searchBarStyles() {
            return {
                marginLeft: '233px',
                marginTop: '10px',
                width: '300px'  // Adjust this value to match your desired width
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
