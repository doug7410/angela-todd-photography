<template>
    <div class="photo-slider">
        <base-slider ref="slider">
            <photo-slide v-for="image in images" :key="image.id" :image="image" :style="{height: '400px'}"/>
        </base-slider>
        <a href="#" @click.prevent="$emit('slider:goPrev')" class="photo-slider__prev">
            <i class="fas fa-angle-left"></i>
        </a>
        <a href="#" @click.prevent="$emit('slider:goNext')" class="photo-slider__next">
            <i class="fas fa-angle-right"></i>
        </a>
    </div>
</template>

<script>
    import BaseSlider from '../BaseSlider'
    import PhotoSlide from './PhotoSlide'
    import {debounce} from 'lodash'


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
        methods: {
            setHeight() {
                if (
                    window.innerWidth <= 414 && window.innerHeight <= 736 ||
                    window.innerWidth <= 736 && window.innerHeight <= 414
                ) {
                    Array.from(this.$refs.slider.$el.children).forEach(slide => {
                        slide.style.height = `${window.innerHeight}px`
                    })
                } else {
                    Array.from(this.$refs.slider.$el.children).forEach(slide => {
                        slide.style.height = `${window.innerHeight * .925}px`
                    })
                }
            },
            handleResize() {
                return debounce(this.setHeight, 250)
            }
        },
        mounted() {
            const imageList = this.$parent.$parent

            imageList.$on('openModalOnImage', index => {
                this.$emit('slider:goTo', index)
            })

            setTimeout(this.setHeight(), 100);
            window.addEventListener('resize', this.handleResize());
        },
        beforeDestroy: () => {
            window.removeEventListener('resize', this.handleResize())
        },
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