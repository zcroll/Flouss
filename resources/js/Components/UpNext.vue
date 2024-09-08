<template>
    <div id="up-next-container" style="box-sizing: border-box;">
        <div
            class="UpNextCard UpNextCard--overview transform transition-all duration-300 hover:shadow-2xl"
            :style="{ ...cardStyle, backgroundColor: backgroundColor }"
        >
            <div
                class="UpNextCard__copy"
                :style="copyStyle"
            >
                <h2
                    class="UpNextCard__title"
                    :style="titleStyle"
                >
                    {{ title }}
                </h2>
                <p
                    class="UpNextCard__description"
                    :style="descriptionStyle"
                >
                    {{ description }}
                </p>
                <span style="box-sizing: border-box;">
                    <a
                        class="UpNextCard__cta"
                        :href="ctaLink"
                        :style="ctaStyle"
                    >
                        {{ ctaText }}
                    </a>
                </span>
            </div>
            <img
                class="UpNextCard__art"
                :src="imageSrc"
                :alt="title"
                height="337"
                width="267"
                aria-hidden="true"
                role="presentation"
                :style="imgStyle"
            />
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
        default: 'Default Title'
    },
    description: {
        type: String,
        required: true,
        default: 'Default description for the card.'
    },
    ctaText: {
        type: String,
    },
    ctaLink: {
        type: String,
        default: '#',
    },
    imageSrc: {
        type: String,

        default: 'https://www.careerexplorer.com/static/compiled/images/up-next-blobs/4-vert.svg'
    },
    backgroundColor: {
        type: String,
        default: '#008264'
    }
});

const cardStyle = {
    boxSizing: "border-box",
    overflow: "hidden",
    transition: "transform 0.5s, scale 2",
    margin: "1em 0px 3em",
    maxHeight: "400px",
    maxWidth: "100%",
    borderRadius: "24px",
    position: "relative",
    color: "rgb(255, 255, 255)",
    flexDirection: "row",
    gridTemplateColumns: "66% max-content",
    gridTemplateRows: "auto",
    display: "flex",
    boxShadow:
        "rgba(123, 110, 97, 0.2) 0px 12px 56px 0px, rgba(0, 0, 0, 0.15) 0px 12px 24px 0px",
};

const copyStyle = {
    boxSizing: "border-box",
    flex: "2 1 0%",
    minWidth: "50%",  // Adjusted to make the card smaller
    display: "flex",
    flexDirection: "column",
    justifyContent: "center",
    padding: "30px 12px 48px 44px",
};

const titleStyle = {
    boxSizing: "border-box",
    letterSpacing: "-0.5px",
    fontWeight: 300,
    color: "rgb(255, 255, 255)",
    paddingTop: "0px",
    margin: "20px 0px 10px",
    fontSize: "36px",
    lineHeight: "41px",
    fontFamily: "Helvetica"
};

const descriptionStyle = {
        overflow: "hidden",
    webkitLineClamp: 2,
webkitBoxOrient: "vertical",
  paddingBottom: "5px",
    boxSizing: "border-box",
    marginBlock: "1.5em",
    letterSpacing: "-0.3px",
    color: "rgb(255, 255, 255)",
    textOverflow: "ellipsis",
    marginBottom: "16px",
    display: "-webkit-box",
    WebkitBoxOrient: "vertical",
    fontSize: "16px",
    lineHeight: "32px",
    maxHeight: "auto",
    fontFamily:
        'aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif',
};

const ctaStyle = {
    boxSizing: "border-box",
    backgroundColor: "transparent",
    textDecoration: "none",
    fontSize: "14px",
    lineHeight: "16px",
    letterSpacing: "1px",
    textTransform: "uppercase",
    display: "inline",
    marginTop: "30px",
    transition: "color 0.2s ease-in-out, border-bottom 0.2s ease-in-out",
    fontWeight: 500,
    borderBottom: "1px solid white",
    color: "rgb(255, 255, 255)",
    fontFamily:
        'aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif',
};

const imgStyle = {
    boxSizing: "border-box",
    borderStyle: "none",
    width: "100%",
    maxHeight: "100%",  // Adjusted to fit the new card size
};
</script>

<style scoped>
html {
    box-sizing: border-box;
    text-size-adjust: 100%;
    overflow-x: hidden;
    color: rgb(36, 36, 36);
    font-family: aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    font-size: 16px;
}

body {
    box-sizing: border-box;
    margin: 0px;
}
</style>
