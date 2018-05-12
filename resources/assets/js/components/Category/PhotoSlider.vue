<template>
    <div class="photo-slider">

        <base-slider>
            <div ref="image"
                 class="photo-slider__slide"
                 v-for="image in images"
                 :key="image.id"
                 :style="{backgroundImage: `url(${image.path})`}"
            >
                <meta-data :image="image" />
            </div>
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
    import MetaData from './MetaData'

    export default {
        name: "photo-slider",
        props: {
            images: {
                type: Array,
                required: true
            },
            initialImageIndex: {
                type: Number,
                required: true
            }
        },
        components: {
            BaseSlider,
            MetaData
        },
        methods: {
            setHeight() {
                Array.from(document.querySelectorAll('.photo-slider__slide'))
                    .forEach(image => image.style.height = `${window.innerHeight * .925}px`);
            },
            handleResize() {
                return _.debounce(this.setHeight, 250)
            }
        },
        mounted() {
            setTimeout(this.setHeight(), 100);
            window.addEventListener('resize', this.handleResize());
            this.$parent.$parent.$on('openModalOnImage', index => this.$emit('slider:goTo', index))
        },
        beforeDestroy: () => {
            window.removeEventListener('resize', this.handleResize())
        },
    }
</script>

<style lang="scss" scoped>
    @import "../../../sass/colors";

    .photo-slider {
        &__slide {
            text-align: center;
            height: 100%;
            background: no-repeat center;
            background-size: contain;
        }

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