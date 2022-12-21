<template>
    <transition name="modal" class="modal" :data-screen="$store.state.activeScreen" :data-key="key">
        <div :class="rootClasses">
            <div :class="wrapperClasses">
                <div :class="containerClasses">
                    <div :class="headerClasses">
                        <slot name="header">
                            <modal-header
                                @selectScreen="selectScreen(backTo)"
                                :title="i18n.menu[screen]">         
                            </modal-header>
                        </slot>
                    </div>
                    <div :class="bodyClasses">
                        <slot name="body">
                            Content
                        </slot>
                    </div>
                    <div :class="footerClasses">
                        <slot name="footer">
                            <button class="modal-default-button" @click="$emit('close')">
                                OK
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script> 
    import BackButton from '$m/media/game/components/buttons/Back';
    import GoTo from '$m/media/game/components/buttons/GoTo';
    import { mapState } from 'vuex';
    import I18nMixin from '$m/media/game/components/mixins/i18n-mixin';
    export default {
        data() {
            return {
                title: '',
                titleText: '',
                key: 0
            };
        },
        props: {
            icon: {
                type: String,
                default: 'success'
            },
            type: {
                type: String,
                default: 'info',
                validator(val) {
                    return ['success', 'warning', 'info', 'error'].indexOf(val) > -1;
                }
            },
            screen: {
                type: String,
                default: ''
            },
            backTo: {
                type: String,
                default: 'menu'
            },
            target: {
                type: [Object, String, HTMLBodyElement],
                default() {
                    return document.body;
                }
            },
            timer: {
                type: [Number, Object],
                default() {
                    return null;
                },
                validator() {
                    return true;
                }
            },
            showClass: {
                type: Object,
                default() {
                    return {
                        popup: '',
                        backdrop: '',
                        icon: ''
                    };
                }
            },

            customClasses: {
                type: Object,
                default() {
                    return {};
                }
            },
            segments: {
                type: Array,
                default() {
                    return ['root', 'wrapper', 'container',
                        'header', 'body', 'footer', 'icon',
                        'confirmButton', 'cancelButton', 'denyButton'];
                }
            },
            allowOutsideClick: {
                type: Boolean,
                default: true
            },
            allowEscapeKey: {
                type: Boolean,
                default: true
            },
            allowEnterKey: {
                type: Boolean,
                default: false
            },
            showConfirmButton: {
                type: Boolean,
                default: true
            },
            showCancelButton: {
                type: Boolean,
                default: true
            },
            showDenyButton: {
                type: Boolean,
                default: false
            },
            willOpen: {
                type: Function,
                default: () => {
                }
            },
            didOpen: {
                type: Function,
                default: () => {
                }
            },

            willRender: {
                type: Function,
                default: () => {
                }
            },
            didRender: {
                type: Function,
                default: () => {
                }
            },
            willClose: {
                type: Function,
                default: () => {
                }
            },
            didClose: {
                type: Function,
                default: () => {
                }
            },
            didDestroy: {
                type: Function,
                default: () => {
                }
            },
            onConfirm: {
                type: Function,
                default: () => {
                }
            },
            onDeny: {
                type: Function,
                default: () => {
                }
            },
            preConfirm: {
                type: Function,
                default: () => {
                }
            },
            preDeny: {
                type: Function,
                default: () => {
                }
            },

        },
        components: {BackButton, GoTo},
        mixins: [I18nMixin],
        computed: {
            visible() {
                return this.$store.state.activeScreen === this.screen;
            },
            rootClasses() {
                return Object.assign({
                    'vc-root': true,
                    'z-10 h-screen w-screen overflow-hidden flex items-center justify-center': true,

                }, this.parseClasses('root'));
            },
            wrapperClasses() {
                return Object.assign({
                    'vc-wrapper': true,
                    'h-screen w-screen bg-opacity-30 bg-gray-300 py-6 flex flex-col justify-center sm:py-12': true,

                }, this.parseClasses('wrapper'));
            },
            containerClasses() {
                return Object.assign({
                    'vc-container': true,
                    'py-3 sm:max-w-xl sm:mx-auto': true,
                    'bg-white min-w-1xl flex flex-col rounded-xl shadow-lg': true,
                }, this.parseClasses('container'));
            },
            headerClasses() {
                return Object.assign({
                    'vc-header': true,
                    'relative': true,
                    'px-8 py-3 text-center': true
                }, this.parseClasses('header'));
            },
            titleClasses() {
                return Object.assign({
                    'vc-title': true
                }, this.parseClasses('title'));
            },
            bodyClasses() {
                return Object.assign({
                    'vc-wrapper': true,
                    'bg-gray-200 w-full flex flex-col items-center': true
                }, this.parseClasses('body'));
            },
            footerClasses() {
                return Object.assign({
                    'vc-footer': true,
                    'flex items-center justify-center': true
                }, this.parseClasses('footer'));
            },
            iconClasses() {
                return Object.assign({
                    'vc-icon': true
                }, this.parseClasses('icon'));
            }
        },
        watch: {
            activeScreen(screen, oldScreen) {     
                this.$emit('selectScreen', screen);
            },
        },
        methods: {
            close() {
                
            },
            parseClasses(type) {
                let $set = this.customClasses[type];

                if (typeof $set === 'string') {
                    $set = $set.split('\s+');
                } else if (Array.isArray($set)) {
                    let newSet = {};
                    for (let i in $set) {
                        newSet[$set[i]] = true;
                    }
                    $set = newSet;
                } else if (null === $set || (typeof $set !== 'object')) {
                    $set = {};
                }
                $set[`${type}`] = true;
                return $set;
            },
            mapSegments(callback) {
                for (let i = 0; i < this.segments.length; i++) {
                    callback(this.segments[i], i);
                }
            },
            selectScreen(item) {
                this.$store.commit('activeScreen', item);
                this.$emit('selectScreen', item);
            },
            emit(...args) {
                const action = args.shift();
                this.$emit(action, args);
                this.key++;
            }
        },
        mounted() {
            this.$store.commit('activeScreen', this.screen);
        }
    }
</script>