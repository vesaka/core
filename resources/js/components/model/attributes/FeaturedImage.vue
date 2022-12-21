<template>
    <div id="cropper" class="mx-auto">
        <vue-cropper
            ref="cropper"
            :src="imgSrc"
            alt="Source Image"
            :aspect-ratio="16 / 9"
            :view-mode="2"
            :center="false"
            :guides="false"
            :auto-crop="true"
            :auto-crop-area="0.9"
            :container-style="styles"
            class="h-64 w-64 mx-auto"
            @crop="cropUpdated"
            @ready="cropperIsReady"
            ></vue-cropper>

        <div class="text-center">
            <div class="overflow-hidden relative w-64 mx-auto my-4">
                <button :class="btnUpload">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="iconClass" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </button>
                <input class="cursor-pointer absolute top-0 block py-1 px-4 w-full opacity-0 pin-r pin-t"
                       type="file"
                       accept="image/*"
                       @change="selectedFile"/>
            </div>
            <div class="flex justify-center rounded-lg text-lg mb-4">
                <button type="button"
                        :class="firstBtnClass"
                        @click="invokeMethod"
                        data-method="relativeZoom"
                        data-option="0.1"
                        data-title="Zoom In">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="iconClass" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </button>
                <button type="button"
                        :class="btnClass"
                        @click="invokeMethod"
                        data-method="relativeZoom"
                        data-option="-0.1"
                        data-title="Zoom Out">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="iconClass" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                    </svg>
                </button>
                <button type="button"
                        :class="btnClass"
                        @click="invokeMethod"
                        data-method="rotate"
                        data-option="-45"
                        data-title="Rotate Left">
                    <svg :class="rotateLeftClass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" aria-labelledby="title"
                         aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path
                            d="M60.693 22.023a3 3 0 0 0-4.17.784l-2.476 3.622A27.067 27.067 0 0 0 28 6C13.443 6 2 18.036 2 32.584a26.395 26.395 0 0 0 45.066 18.678 3 3 0 1 0-4.244-4.242A20.395 20.395 0 0 1 8 32.584C8 21.344 16.752 12 28 12a21.045 21.045 0 0 1 20.257 16.059l-4.314-3.968a3 3 0 0 0-4.062 4.418l9.737 8.952c.013.013.03.02.043.033.067.06.143.11.215.163a2.751 2.751 0 0 0 .243.17c.076.046.159.082.24.12a3.023 3.023 0 0 0 .279.123c.08.03.163.05.246.071a3.045 3.045 0 0 0 .323.07c.034.006.065.017.1.022.051.006.102-.002.154.002.063.004.124.017.187.017.07 0 .141-.007.212-.012l.08-.004.05-.003c.06-.007.118-.03.179-.04a3.119 3.119 0 0 0 .387-.087c.083-.027.16-.064.239-.097a2.899 2.899 0 0 0 .314-.146 2.753 2.753 0 0 0 .233-.151 2.944 2.944 0 0 0 .263-.2c.07-.06.135-.124.199-.19a3.013 3.013 0 0 0 .224-.262c.03-.04.069-.073.097-.114l7.352-10.752a3.001 3.001 0 0 0-.784-4.17z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <button type="button"
                        :class="btnClass"
                        @click="invokeMethod"
                        data-method="rotate"
                        data-option="45"
                        data-title="Rotate Right">
                    <svg :class="iconClass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" aria-labelledby="title"
                         aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path
                            d="M60.693 22.023a3 3 0 0 0-4.17.784l-2.476 3.622A27.067 27.067 0 0 0 28 6C13.443 6 2 18.036 2 32.584a26.395 26.395 0 0 0 45.066 18.678 3 3 0 1 0-4.244-4.242A20.395 20.395 0 0 1 8 32.584C8 21.344 16.752 12 28 12a21.045 21.045 0 0 1 20.257 16.059l-4.314-3.968a3 3 0 0 0-4.062 4.418l9.737 8.952c.013.013.03.02.043.033.067.06.143.11.215.163a2.751 2.751 0 0 0 .243.17c.076.046.159.082.24.12a3.023 3.023 0 0 0 .279.123c.08.03.163.05.246.071a3.045 3.045 0 0 0 .323.07c.034.006.065.017.1.022.051.006.102-.002.154.002.063.004.124.017.187.017.07 0 .141-.007.212-.012l.08-.004.05-.003c.06-.007.118-.03.179-.04a3.119 3.119 0 0 0 .387-.087c.083-.027.16-.064.239-.097a2.899 2.899 0 0 0 .314-.146 2.753 2.753 0 0 0 .233-.151 2.944 2.944 0 0 0 .263-.2c.07-.06.135-.124.199-.19a3.013 3.013 0 0 0 .224-.262c.03-.04.069-.073.097-.114l7.352-10.752a3.001 3.001 0 0 0-.784-4.17z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <button type="button"
                        :class="lastBtnClass"
                        @click="invokeMethod"
                        data-method="reset"
                        data-title="Reset">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="iconClass" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
    import VueCropper from 'vue-cropperjs';
    import 'cropperjs/dist/cropper.css';

    export default {
        data() {
            return {
                value: env.gallery.crop,
                booted: false,
                image: {
                    file: null
                },
                imgSrc: ''
            };
        },
        emits: ['update:file', 'update:crop'],
        props: {
            file: {
                type: Object
            },
            crop: {
                type: [String, Object],
                default() {
                    return window.env.gallery.crop;
                }
            },
            styles: {
                'max-height': '360px'
            },
            src: {
                type: String,
                default: ''
            }
        },
        components: {VueCropper},
        watch: {
            imgSrc(value) {
                this.$refs.cropper.replace(value);
            }
        },
        computed: {
            firstBtnClass() {
                return Object.assign({}, this.btnClass, {
                    'rounded-l': true
                });
            },
            lastBtnClass() {
                return Object.assign({}, this.btnClass, {
                    'rounded-r': true
                });
            },
            btnClass() {
                return {
                    'flex text-white bg-indigo-500 border-0 py-1 px-1': true,
                    'inline': true,
                    'focus:outline-none hover:bg-indigo-600': true,
                    'text-lg': true
                }
            },
            btnUpload() {
                return Object.assign({}, this.btnClass, {
                    'rounded w-64 md:w32 mx-auto cursor-pointer text-center': true
                });
            },
            iconClass() {
                return {
                    'w-6 h-6 mx-auto': true,
                    'pointer-events-none': true
                };
            },
            rotateLeftClass() {
                return Object.assign({}, this.iconClass, {
                    'transform rotate-180': true
                });
            }
        },
        methods: {
            cropperIsReady() {
                if (!this.booted) {
                    this.booted = true;
                    let crop;
                    try {
                        crop = JSON.parse(this.crop);
                    } catch (e) {
                        crop = env.gallery.crop;
                    }
                    this.$refs.cropper.setData(crop);
                }

            },
            invokeMethod(ev) {
                let target = ev.target,
                        data = target.dataset,
                        method = data.method,
                        option = data.option;
                if (typeof this.$refs.cropper[method] === 'function') {
                    this.$refs.cropper[method](option);
                }
            },
            selectedFile(ev) {
                let reader = new FileReader(), $this = this,
                        file = ev.target.files[0];

                reader.onload = function (ev) {
                    $this.imgSrc = ev.target.result;
                };

                reader.readAsDataURL(file);
                this.$emit('update:file', file);
            },
            cropUpdated(ev) {
                this.$emit('update:crop', ev.detail);
            },
        },
        mounted() {
            this.imgSrc = this.src;





            //$on('edit-image', this.setImageData);
        }
    }
</script>
