<template>
  <div class='carousel-container'>
    <transition-group
      class='carousel'
      :style="{
        width: `${carouselWidth}px`,
        height: `${carouselHeight}px`
      }"
      tag="div">
      <div
        v-for="slide in slides"
        class='slide'
        :key="slide.id"
        :style="{
          backgroundImage: `url(../../images/${slide.url})`,
          height: `${carouselHeight}px`
        }">
      </div>
    </transition-group>
    <div class='carousel-controls' :style="{height: `${carouselHeight}px`}">
      <a href="#" @click.prevent="previous">
        <img src="../../images/06_left.png" class='carousel-controls__button' />
      </a>
      <a href="#" @click.prevent="next">
        <img src="../../images/05_right.png" class='carousel-controls__button' />
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        slides: [
          {
            url: '01_bg.jpg',
            id: 1
          },
          {
            url: '02_bg.jpg',
            id: 2
          },
          {
            url: '03_bg.jpg',
            id: 3
          },
          {
            url: '04_bg.jpg',
            id: 4
          },
          {
            url: '05_bg.jpg',
            id: 5
          }
        ]
      }
    },
    props: {
      images: Array
    },
    computed: {
      carouselWidth() {
        return window.innerWidth * this.slides.length
      },
      carouselHeight() {
        if(window.innerWidth < 670) {
          return window.innerHeight / 2
        }
        return window.innerHeight
      }
    },
    methods: {
      next () {
        const first = this.slides.shift()
        this.slides = this.slides.concat(first)
      },
      previous () {
        const last = this.slides.pop()
        this.slides = [last].concat(this.slides)
      }
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
  }
</style>