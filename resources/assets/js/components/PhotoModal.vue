<template>
  <div class="photo-modal" v-if="initialImageId">
    <a class="modal-nav" href="#">
      <i class="fas fa-angle-left prev-image"></i>
    </a>
    <div class="modal-content" :style="{backgroundImage: `url(${currentImagePath})`}">
      <a href="#" class="modal-info ">
        <i class="fas fa-info-circle"></i>
      </a>
    </div>
    <div class="modal-nav">
      <span class="modal-close" @click="closeModal()">X</span>
      <a href="#" @click.prevent="next()" class="next-image">
        <i class="fas fa-angle-right" ></i>
      </a>        {{ currentImageId }}

    </div>
  </div>
</template>

<script>
  import { find, findIndex } from 'lodash'

  export default {
    name: 'photo-modal',
    data() {
      return {
        currentImageId: this.initialImageId
      }
    },
    props: {
      initialImageId: {
        type: Number
      },
      images: Array
    },
    methods: {
      closeModal() {
        this.$emit('close:modal')
      },
      next() {
        const index = findIndex(this.images, image => image.id === this.currentImageId)
        this.currentImageId = this.images[index+1].id
      }
    },
    computed: {
      currentImagePath() {
        const image = find(this.images, image => image.id === this.currentImageId)

        if (image) {
          return image.path
        }

      }
    }
  }
</script>