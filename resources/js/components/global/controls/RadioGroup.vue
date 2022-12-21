<template>
    <div :class="baseClass" :key="key">
        <div  v-for="(item, index) in listOptions" :class="rootClass">
            <slot name="label" :item="item" :value="item.__value__" :index="index"></slot>
            <div :class="wrapperClass(item.__value__)" @click="select(item.__value__)">
                <div :class="toggleClass(item.__value__)">
                    <slot name="center" :item="item" :value="item.__value__" :index="index"></slot>
                </div>
                <slot name="enabled" v-show="value === item.__value__"></slot>
                <slot name="disabled" v-show="value !== item.__value__"></slot>
            </div>
        </div>
    </div>
</template>
<script>
    import Toggle from '$m/media/game/components/common/controls/Toggle';

    export default {
        data() {
            return {
                key: 1,
                value: ''
            };
        },
        components: {Toggle},
        props: {
            label: {
                type: String,
                default: 'label'
            },
            name: {
                type: String,
                default: 'value'
            },
            options: {
                type: Array,
                default() {
                    return [];
                }
            },
            withDefault: {
                type: Boolean,
                default: true
            },
            selected: {
                type: [String, Number],
                default: ''
            },
            classNames: {
                type: Object,
                default() {
                    return {
                        base: {
                            'flex flex-row': true
                        },
                        root: {
                            'flex flex-col justify-between items-center px-8': true
                        },
                        label: {
                            'w-32 h-10 flex-grow-0 p-1 inline cursor-pointer': true,
                        },
                        wrapper: {
                            'w-16 h-10 bg-gray-300 rounded-full flex flex-shrink-0 p-1 inline cursor-pointer': true,
                            'transform duration-300 ease-in-out': true,
                        },
                        toggle: {
                            'bg-white w-8 h-8 rounded-full shadow-md': true,
                            'transform': true,
                            'duration-300 ease-in-out': true,
                        },
                        active_wrapper: {
                            'bg-green-400': true
                        },
                        active_toggle: {
                            'translate-x-6': true
                        }
                    };
                }
            },
            classes: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        methods: {
            select(value) {
                this.value = value;
            },
            getOption(value) {

            },
            getValue(value) {

            },
            labelClass(value) {
                return Object.assign({}, this.classNames.label, this.classes.label || {});
            },
            wrapperClass(value) {
                return this.activeClass('wrapper', value);
            },
            toggleClass(value) {
                return this.activeClass('toggle', value);
            },
            activeClass(name, value) {
                let className = Object.assign({}, this.classNames[name], (this.classes[name] || {})[value] || {});
                if (this.value === value) {
                    Object.assign(className, this.classNames[`active_${name}`]);
                }
                return className;
            },
        },
        computed: {
            baseClass(value) {
                return Object.assign({}, this.classNames.base, this.classes.base || {});
            },
            rootClass(value) {
                return Object.assign({}, this.classNames.root, this.classes.root || {});
            },
            hasLabelSlot() {
                return !!this.$slots.label;
            },
            listOptions() {
                if (this.withDefault) {
                    const defaultOption = {};
                    defaultOption[this.label] = '';
                    defaultOption[this.name] = '';
                    defaultOption.__value__ = '';
                    this.options.unshift(defaultOption);
                }

                for (let i in this.options) {
                    this.options[i].__value__ = this.options[i][this.name];
                }
                return this.options;
            }

        },
        watch: {
            value(n, o) {
                this.key++;
                this.$emit('input', n, o);
            }
        },
        created() {
            if (this.selected) {
                this.value = this.selected;
            }
        },
        mounted() {
        }
    }
</script>
