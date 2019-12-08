<template>
    <div>
        <div class="thumbnails">
            <a
                v-for="(image, index) in images"
                :key="image.id"
                href="#"
                class="thumbnail"
                @click.prevent="openModal(index)"
            >
                <img :src="image.path" :alt="image.caption">
                <small>{{ image.caption }}</small>
            </a>
        </div>
        <base-modal v-model="modalVisible" modal-background-color="#000" ref="modal">
            <photo-slider :images="images" :initial-image-index="currentImageIndex"/>
        </base-modal>
    </div>
</template>

<script>
    import BaseModal from '../BaseModal'
    import PhotoSlider from './PhotoSlider'

    export default {
        name: "image-list",
        data() {
          return {
              modalVisible: false,
              currentImageIndex: 0,
              scrollPosition: 0
          }
        },
        components: {
          BaseModal,
          PhotoSlider
        },
        watch: {
          modalVisible(isVisible) {
              if(isVisible) {
                  this.scrollPosition = window.pageYOffset
                  window.scrollTo(0, 300)
                  document.querySelector('body').style.overflow = 'hidden';
                  document.querySelector('body').style.height = '100%';
              } else {
                  window.scrollTo(0, this.scrollPosition)
                  document.querySelector('body').style.overflow = '';
                  document.querySelector('body').style.height = '';
              }
          }
        },
        props: {
            images: {
                type: Array,
                required: true
            }
        },
        methods: {
            openModal(imageIndex) {
                this.modalVisible = true
                this.$emit('openModalOnImage', imageIndex)
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import "../../../sass/colors";

    .thumbnails {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
        grid-gap: .5rem;
        margin-top: 10.5rem;
    }

    .thumbnail {
        color: $light;
        position: relative;
        z-index: 1;
        height: 15rem;
        overflow: hidden;
        display: block;

        @media screen and (min-width: 451px) {
            padding-top: 100%;
        }

        img {
            position: absolute;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 104%;
            z-index: -1;
            background-size: cover;
            background-position: center;
            transition: .3s
        }

        &:hover {
            cursor: pointer;

            img {
                top: 50%;
            }

            small {
                opacity: 1;
            }
        }

        a, a:hover {
            color: $light;
        }

        small {
            position: absolute;
            top: 40%;
            width: 100%;
            display: block;
            text-align: center;
            font-size: 1.1rem;
            padding: .5rem 10%;
            opacity: 0;
            transition: .3s;
            line-height: 1.5rem;
            text-shadow: 0 0 10px rgba(0,0,0, .7);
        }
    }
</style>