import Vue from 'vue'
import PhotoModal from './components/PhotoModal.vue'

const app = new Vue({
  el: '#app',
  data: {
    currentImageId: null
  },
  components: {
    PhotoModal
  },
  methods: {
    setCurrentImageId(id) {
      this.currentImageId = id
    }
  }
});

export default app