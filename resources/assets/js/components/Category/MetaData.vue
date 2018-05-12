<template>
    <div class="meta-data">
        <transition name="slide">
            <div class="meta-data__wrapper" v-if="showMetaData">
                <div class="meta-data__info">
                    <h2>Photo Info</h2>
                    <small>HDR photo data may not be accurate</small>
                    <div class="meta-data__table">
                        <table>
                            <tr>
                                <th>Description</th>
                                <td>{{ image.caption }}</td>
                            </tr>
                            <tr v-for="(field, label) in metaDataFields">
                                <th>{{ label }}</th>
                                <td>{{ field }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </transition>
        <a href="#" class="meta-data__show" @click.prevent="toggle">
            <i class="fas fa-info-circle"></i>
        </a>
    </div>

</template>

<script>
    import metaDataParser from '../../utils/metaDataParser'

    export default {
        name: "meta-data",
        data() {
            return {
                showMetaData: false
            }
        },
        props: {
            image: {
                type: Object,
                required: true
            }
        },
        computed: {
            metaDataFields() {
                if (this.image.meta_data) {
                    return metaDataParser(JSON.parse(this.image.meta_data))
                }
                return []
            },
        },
        methods: {
            toggle() {
                this.showMetaData = !this.showMetaData
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "../../../sass/colors";

    .meta-data {
        height: 100%;
        position: relative;

        &__wrapper {
            background-color: rgba($white, .85);
            width: 50%;
            transform: translateX(100%);

            @media screen and (max-width: 900px) {
                width: 75%;
                transform: translateX(33%);
            }

            @media screen and (max-width: 500px) {
                width: 90%;
                transform: translateX(12%);
            }

            padding: 1rem 1.8rem;
            border-radius: 0 3px 3px 0;
            height: 100%;
            overflow: scroll;
            position: relative;
            color: #000;
        }

        &__show {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            color: $dark;
            font-size: 1.8rem;
        }

        &__info {
            h2 {
                text-transform: uppercase;
                margin: 0 0 .3rem 0;
            }

            table {
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
        }

        &__table {
            margin-top: .5rem;
            height: 77vh;
            overflow: scroll;
            position: relative;
            width: 100%;
            border: solid $dark 1px;
        }
    }

    .slide-leave-active, .slide-enter-active {
        transition: all .3s ease-out;
    }

    .slide-enter, .slide-leave-to {
        transform: translateX(200%);
    }
</style>