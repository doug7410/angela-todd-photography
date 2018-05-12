<template>
  <div class="modal" v-show="visible">
    <div class="modal-mask" @click="closeModal()" :style="{backgroundColor: maskColor}"></div>
    <span class="modal-close" @click="closeModal()">X</span>
    <div class="modal-content" :style="{backgroundColor: modalBackgroundColor}">
    <slot></slot>
    </div>
  </div>
</template>

<script>
  export default {
    name: "base-modal",
    model: {
      prop: 'visible',
      event: 'close:modal'
    },
    props: {
      visible: { default: false },
      modalBackgroundColor: {
        type: String,
        default: '#fff'
      },
      maskColor: {
        type: String,
        default: '#fff'
      },
    },
    methods: {
      closeModal() {
        this.$emit('close:modal');
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/colors";

  .modal {
    &-mask {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      z-index: 997;
      opacity: .8;
    }

    &-close {
      z-index: 998;
      color: $dark;
      font-size: 2.5rem;
      position: fixed;
      top: .5rem;
      right: 2.5rem;
      cursor: pointer;
      font-weight: bold;
    }

    &-content {
      border: solid 1px $dark;
      width: 80%;
      height: 93%;
      z-index: 998;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border-radius: 3px;
    }
  }
</style>