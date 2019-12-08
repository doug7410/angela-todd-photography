<template>
  <div class='carousel-container' ref="carousel">
    <transition-group
        class='carousel'
        :style="{
        width: `${carouselWidth}px`,
        height: `${carouselHeight}px`
      }"
        tag="div">
      <div
          v-for="image in images"
          class='slide'
          :key="image.id"
          :style="{
          backgroundImage: `url(${image.path})`,
          height: `${carouselHeight}px`
        }">
      </div>
    </transition-group>
    <div class='carousel-controls' :style="{height: `${carouselHeight}px`}">
      <a href="#" @click.prevent="navigatePrev">
        <img src="https://res.cloudinary.com/dsteinberg/image/upload/v1575669622/06_left.png" class='carousel-controls__button prev'/>
      </a>
      <a href="#" @click.prevent="navigateNext">
        <img src="https://res.cloudinary.com/dsteinberg/image/upload/v1575669622/05_right.png" class='carousel-controls__button next'/>
      </a>
    </div>
  </div>
</template>

<script>
  import Hammer from 'hammerjs';

  export default {
    data() {
      return {
        images: JSON.parse(this.slides),
        autoPlayTimer: null,
        hammer: null
      }
    },
    props: {
      slides: String
    },
    computed: {
      carouselWidth() {
        return window.innerWidth * this.images.length
      },
      carouselHeight() {
        if (window.innerWidth < 670) {
          return window.innerHeight * .66
        }
        return window.innerHeight
      }
    },
    methods: {
      next() {
        const first = this.images.shift()
        this.images = this.images.concat(first)
      },
      previous() {
        const last = this.images.pop()
        this.images = [last].concat(this.images)
      },
      navigateNext() {
        this.clearAutoPlay();
        this.next()
      },
      navigatePrev() {
        this.clearAutoPlay();
        this.previous()
      },
      clearAutoPlay() {
        if(this.autoPlayTimer) {
          window.clearInterval(this.autoPlayTimer);
        }
      }
    },
    mounted() {
      this.autoPlayTimer = setInterval(() => this.next(), 4000);
      this.hammer = new Hammer(this.$refs.carousel, { threshold: 0, pointers: 0 });
      this.hammer.on('swipeleft', () => this.navigateNext());
      this.hammer.on('swiperight', () => this.navigatePrev());
    },
    destroyed() {
      this.clearAutoPlay()
    }
  }
</script>

<style lang="scss">
  .carousel-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .carousel {
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
  }

  .slide {
    width: 100%;
    background: no-repeat center;
    background-size: cover;
    transition: transform 0.3s ease-in-out;
  }

  .slide:first-of-type {
    opacity: 0;
  }

  .slide:last-of-type {
    opacity: 0;
  }

  .carousel-controls {
    position: absolute;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 2rem;

    img {
      cursor: pointer;
    }

    @media screen and (max-width: 480px) {
      img {
        width: 60%;
        height: 60%;
      }

      .carousel-controls__button.next {
        float: right;
      }
    }
  }
</style>