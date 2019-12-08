import Vue from 'vue'
import Carousel from './components/Carousel.vue'
import ImageList from './components/Category/ImageList'


new Vue({
    el: '#app',
    data: {
        modalVisible: false,
        currentImage: null
    },
    components: {
        Carousel,
        ImageList
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
