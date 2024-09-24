<template>
    <link
        rel="stylesheet"
        media="all"
        href="/css/landingPage/index.css"
    />
    <header class="relative overflow-x-auto min-h-[200px] ">
        <div class="header-content mt-20 mb-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap">
                    <div class="w-full">
                        <h1 class="text-3xl font-normal text-white font-sofia-pro-semi-bold mt-2.5">
                            {{ title }}
                        </h1>
                        <p class="float-right text-xs text-white/50 font-sofia-pro-regular mb-4 hidden lg:block">
                            <span class="inline-block w-4 h-4 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-502px_-14px] mr-1.5 -mb-0.5"></span>
                            <span class="font-sofia-pro-bold">1 of 6 tests complete.</span>
                            Finish all to reveal your career matches.
                        </p>
                    </div>
                </div>
                <p class="text-xl text-white font-sofia-pro-regular mb-0 max-w-[713px]">
                    <span v-if="name" class="font-sofia-pro-bold">
                        {{ $page.props.auth.user.name }},
                    </span>
                    {{ subTitle }}
                </p>

                <div v-if="show" >
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
        Jobs
    }
}
</script>

<style scoped>
/* You can keep any necessary scoped styles here */
</style>
