<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-image: linear-gradient(#ffffff, #ddd3ed);">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-lg mt-6 overflow-hidden sm:rounded-lg px-6 py-12 sm:px-6 lg:px-8">
            <slot />
        </div>
    </div>
</template>
