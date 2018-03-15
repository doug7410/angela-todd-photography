import Vue from 'vue'
import PhotoModal from './components/PhotoModal.vue'
import Carousel from './components/Carousel.vue'

const app = new Vue({
  el: '#app',
  data: {
    currentImageId: null
  },
  components: {
    PhotoModal,
    Carousel
  },
  methods: {
    setCurrentImageId(id) {
      this.currentImageId = id
    }
  }
});

export default app