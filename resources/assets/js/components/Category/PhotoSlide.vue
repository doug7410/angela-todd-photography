<template>
    <div ref="image" class="slide" :style="{backgroundImage: `url(${image.path})`}">
        <meta-data :image="image" />
    </div>
</template>

<script>
    import MetaData from './MetaData'
    import { debounce } from 'lodash'


    export default {
        name: "photo-slide",
        props: {
            image: Object
        },
        components: {
            MetaData
        },
        methods: {
            setHeight() {
                if(
                    window.innerWidth <= 414 && window.innerHeight <= 736 ||
                    window.innerWidth <= 736 && window.innerHeight <= 414
                ) {
                    this.$refs.image.style.height = `${window.innerHeight}px`
                } else {
                    this.$refs.image.style.height = `${window.innerHeight * .925}px`
                }
            },
            handleResize() {
                return debounce(this.setHeight, 250)
            }
        },
        mounted() {
            setTimeout(this.setHeight(), 100);
            window.addEventListener('resize', this.handleResize());
        },
        beforeDestroy: () => {
            window.removeEventListener('resize', this.handleResize())
        },
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