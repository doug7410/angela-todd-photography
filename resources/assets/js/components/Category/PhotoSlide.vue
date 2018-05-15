<template>
    <div ref="image" class="slide" :style="{backgroundImage: `url(${image.path})`}">
        <meta-data :image="image" />
    </div>
</template>

<script>
    import MetaData from './MetaData'

    export default {
        name: "photo-slide",
        props: {
            image: Object
        },
        components: {
            MetaData
        }
    }
</script>

<style lang="scss" scoped>
    .slide {
        text-align: center;
        height: 100%;
        background: no-repeat center;
        background-size: contain;
    }
</style>