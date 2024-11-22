<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#e4e2dc]">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-lg mt-6 bg-white shadow-md overflow-hidden sm:rounded-lg py-12 sm:px-6 lg:px-8">
            <slot />
        </div>
    </div>
</template>
