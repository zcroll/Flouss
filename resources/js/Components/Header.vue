<template>
    <!-- <link
        rel="stylesheet"
        media="all"
        href="/css/landingPage/index.css"
    /> -->

    <header class="relative overflow-x-auto min-h-[200px]">
        <div class="header-content mt-20 mb-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="w-full lg:w-3/4">
                        <h1 class="text-3xl font-normal text-white font-sofia-pro-semi-bold mt-2.5 mb-4">
                            {{ title }}
                        </h1>
                        <p class="text-xl text-white font-sofia-pro-regular mb-4 max-w-[713px]">
                            <span v-if="name" class="font-sofia-pro-bold">
                                {{ $page.props.auth.user.name }},
                            </span>
                            {{ subTitle }}
                        </p>
                    </div>
                    <div class="w-full lg:w-1/4 text-right">
                 
                    </div>
                </div>



                <div v-if="show">
                    <Jobs :jobs="jobsData" />
                </div>
            </div>
        </div>

        <div class="header-background" :style="[backgroundStyles, backgroundImage1]"></div>
        <slot name="navigation"></slot>
    </header>
</template>

<script>
import Jobs from '@/Components/JObs.vue';
import { Link } from '@inertiajs/vue3';
export default {
    props: {
        title: {
            type: String,
        },
        subTitle: {
            type: String,
        },
        show: {
            type: Boolean,
            default: true
        },
        name: {
            type: Boolean,
            default: false
        }
    },
    components: {
        Jobs,Link
    }
}
</script>

<style scoped>
/* You can keep any necessary scoped styles here */
</style>
