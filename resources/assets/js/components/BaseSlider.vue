<template>
    <div class="base-slider" ref="slider">
        <slot></slot>
    </div>
</template>

<script>
    import { tns } from 'tiny-slider/src/tiny-slider.module'

    export default {
        name: "base-slider",
        mounted() {
            const slider = tns({
                container: this.$refs.slider,
                items: 1,
                controls: false,
                touch: true,
                // mouseDrag: true,
                nav: false,
            });

            this.$parent.$on('slider:goTo', index => slider.goTo(index))
            this.$parent.$on('slider:goNext', () => slider.goTo('next'))
            this.$parent.$on('slider:goPrev', () => slider.goTo('prev'))
        },

    }
</script>

<style scoped>

</style>