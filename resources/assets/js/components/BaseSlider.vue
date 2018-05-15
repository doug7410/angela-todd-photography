<template>
    <div class="base-slider" ref="slider">
        <slot></slot>
    </div>
</template>

<script>
    import { tns } from 'tiny-slider/src/tiny-slider.module'
    import Hammer from 'hammerjs';

    export default {
        name: "base-slider",
        mounted() {
            const slider = tns({
                container: this.$refs.slider,
                items: 1,
                controls: false,
                arrowKeys: true,
                nav: false,
                loop: false
            });
            this.$parent.$on('slider:goTo', index => slider.goTo(index))
            this.$parent.$on('slider:goNext', () => slider.goTo('next'))
            this.$parent.$on('slider:goPrev', () => slider.goTo('prev'))

            this.hammer = new Hammer(this.$refs.slider, { threshold: 0, pointers: 0 })
            this.hammer.on('swipeleft', () => slider.goTo('next'))
            this.hammer.on('swiperight', () => slider.goTo('prev'))
        },

    }
</script>

<style scoped>

</style>