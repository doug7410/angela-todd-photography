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
        toggleMenu() {
            const menu = this.$refs.menu
            if (menu.classList.contains('active')) {
                menu.classList.remove('active')
            } else {
                menu.classList.add('active')
            }
        },
        closeMenuIfActive() {
            const menu = this.$refs.menu
            if (menu.classList.contains('active')) {
                menu.classList.remove('active')
            }
        }
    }
});
