<template>
  <div class="modal" v-show="initialImageId">
    <span class="modal-nav" href="#" @click="previous()">
      <i class="fas fa-angle-left prev-image"></i>
    </span>
    <div class="modal-content" ref="modal" :style="{backgroundImage: `url(${currentImagePath})`}">
      <div v-if="showInfo" class="meta-data">
        <h2>Photo Info</h2>
        <small>HDR photo data may not be accurate</small>
        <table>
          <tr><th>Description</th><td>{{ currentImage.caption }}</td></tr>
          <tr v-for="(field, label) in metaDataFields">
            <th>{{ label }}</th><td>{{ field }}</td>
          </tr>
        </table>
        <span class="meta-data-close" @click="closeInfo">X</span>
      </div>
      <a href="#" class="modal-info" @click.prevent="showImageInfo">
        <i class="fas fa-info-circle"></i>
      </a>
    </div>
    <div class="modal-nav">
      <span class="modal-close" @click="closeModal()">X</span>
      <span @click.prevent="next()" class="next-image">
        <i class="fas fa-angle-right" ></i>
      </span>

    </div>
  </div>
</template>

<script>
  import { find, findIndex } from 'lodash'
  import metaDataParser from '../utils/metaDataParser'
  import Hammer from 'hammerjs';

  export default {
    name: 'photo-modal',
    data() {
      return {
        currentImageId: this.initialImageId,
        showInfo: false
      }
    },
    props: {
      initialImageId: {
        type: Number
      },
      images: Array
    },
    watch: {
      initialImageId(id) {
        this.currentImageId = id
      }
    },
    methods: {
      closeModal() {
        this.closeInfo()
        this.$emit('close:modal')
      },
      next() {
        this.closeInfo()
        if(this.currentImageIndex === this.images.length -1) {
          this.currentImageId = this.images[0].id
        } else {
          this.currentImageId = this.images[this.currentImageIndex+1].id
        }
      },
      previous() {
        this.closeInfo()
        if(this.currentImageIndex === 0) {
          this.currentImageId = this.images[this.images.length-1].id
        } else {
          this.currentImageId = this.images[this.currentImageIndex-1].id
        }
      },
      showImageInfo() {
        this.showInfo = true
      },
      closeInfo() {
        this.showInfo = false
      }
    },
    mounted() {
      this.hammer = new Hammer(this.$refs.modal, { threshold: 0, pointers: 0 });
      this.hammer.on('swipeleft', () => this.next());
      this.hammer.on('swiperight', () => this.previous());
    },
    computed: {
      currentImageIndex() {
        return findIndex(this.images, image => image.id === this.currentImageId)
      },
      currentImagePath() {
        const image = find(this.images, image => image.id === this.currentImageId)

        if (image) {
          return image.path
        }
      },
      currentImage() {
        return find(this.images, image => image.id === this.currentImageId)
      },
      metaDataFields() {
        if(this.currentImage.meta_data) {
          return metaDataParser(JSON.parse(this.currentImage.meta_data))
        }
        return []
      }
    }
  }
</script>

<style lang="scss">
  @import "../../sass/colors";

  .modal {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-color: rgba($white, .8);
    z-index: 9999;
    display: flex;
    justify-content: space-around;

    &-content {
      position: relative;
      background: $white center no-repeat;
      width: 80%;
      height: 95%;
      z-index: 99999;
      border: solid 1px $dark;
      align-self: center;
      border-radius: 3px;
      background-size: contain;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    &-nav {
      align-self: center;
      font-size: 5rem;
      color: $dark;
    }

    &-info {
      position: absolute;
      bottom: 1rem;
      right: 1rem;
      color: $dark;
      font-size: 1.8rem;
    }

    &-close {
      position: absolute;
      top: 1rem;
      color: $dark;
      font-size: 2.5rem;
      font-weight: bold;
      cursor: pointer;
    }

    .fa-angle-right, .fa-angle-left {
      cursor: pointer;
    }

    .meta-data {
      background-color: rgba($white, .75);
      width: 50%;
      border: solid 1px $dark;
      padding: 1rem 1.8rem;
      min-height: 50%;
      max-height: 70%;
      border-radius: 3px;
      align-self: center;
      overflow: scroll;
      position: relative;
      color: #000;


      h2 {
        text-transform: uppercase;
        margin: 0 0 .3rem 0;
      }

      table {
        margin-top: .5rem;
        width: 100%;
        font-size: .9rem;
      }

      th, td {
        border: solid 1px $dark;
        padding: .5rem;
        max-width: 1px;
        word-wrap: break-word;
      }

      th {
        text-align: right;
      }

      &-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
      }
    }
  }
</style>