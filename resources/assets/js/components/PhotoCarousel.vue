<template>
  <div class="my-slider" ref="myslider">
    <slot></slot>
  </div>
</template>

<script>
  import { tns } from 'tiny-slider/src/tiny-slider.module'

  export default {
    name: "photo-carousel",
    mounted() {
      this.setHeight();
      window.addEventListener('resize',  this.handleResize());

      this.slider = tns({
        container: this.$refs.myslider,
        items: 1,
        controls: false,
        touch: true,
        mouseDrag: true,
        nav: false
      });
    },
    beforeDestroy: () => {
      window.removeEventListener('resize', this.handleResize())
    },
  }
</script>

<style scoped>

</style>