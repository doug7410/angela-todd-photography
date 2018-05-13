<template>
    <div class="photo-slider">
        <base-slider>
            <photo-slide v-for="image in images" :key="image.id" :image="image" :style="{height: '400px'}"/>
        </base-slider>
        <a href="#" @click.prevent="$emit('slider:goPrev')" class="photo-slider__prev">
            <i class="fas fa-angle-left"></i>
        </a>
        <a href="#" @click.prevent="$emit('slider:goNext')" class="photo-slider__next">
            <i class="fas fa-angle-right" ></i>
        </a>
    </div>
</template>

<script>
    import BaseSlider from '../BaseSlider'
    import PhotoSlide from './PhotoSlide'

    export default {
        name: "photo-slider",
        props: {
            images: {
                type: Array,
                required: true
            },
        },
        components: {
            BaseSlider,
            PhotoSlide
        },
        mounted() {
            const imageList = this.$parent.$parent

            imageList.$on('openModalOnImage', index => {
                this.$emit('slider:goTo', index)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import "../../../sass/colors";

    .photo-slider {
        &__prev, &__next {
            font-size: 5rem;
            color: $dark;
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
        }

        &__prev {
            left: -10%;
        }

        &__next {
            right: -10%;
        }
    }
</style>