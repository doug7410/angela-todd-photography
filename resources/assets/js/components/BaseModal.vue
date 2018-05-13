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
            visible: {default: false},
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
            }
        },
        mounted() {
            const closeOnEscKey = (e) => {
                if (e.key === 'Escape' && this.visible) {
                    this.closeModal()
                }
            }

            document.addEventListener('keyup', closeOnEscKey)
            this.$once('hook:destroyed', () => document.removeEventListener('keyup', closeOnEscKey))
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
            z-index: 999;
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

            /* ----------- iPhone 6, 6S, 7 and 8 ----------- */
            /* Portrait and Landscape */
            @media only screen
            and (min-device-width: 375px)
            and (max-device-width: 667px)
            and (-webkit-min-device-pixel-ratio: 2) {
                width: 100%;
                height: 100%;
            }

            /* ----------- iPhone 6+, 7+ and 8+ ----------- */
            /* Portrait and Landscape */
            @media only screen
            and (min-device-width: 414px)
            and (max-device-width: 736px)
            and (-webkit-min-device-pixel-ratio: 3) {
                width: 100%;
                height: 100%;
            }

            /* ----------- iPhone X ----------- */
            /* Portrait and Landscape */
            @media only screen
            and (min-device-width: 375px)
            and (max-device-width: 812px)
            and (-webkit-min-device-pixel-ratio: 3) {
                width: 100%;
                height: 100%;
            }

            z-index: 998;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 3px;
        }
    }
</style>