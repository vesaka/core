<template>
    <div :class="classes">
        <slot name="viewText"><span v-html="getText"></span></slot>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                key: 0
            }
        },
        props: {
            attribute: {
                type: String,
                default: ''
            },
            text: {
                type: [Function, String],
                default: ''
            },
            classes: {
                type: [String, Object],
                default() {
                    return {
                        'text': true,
                        'font-medium': true,
                        'h-8': true
                    };
                }
            },
            level: {
                type: String,
                default: 'info',
                validator(value) {
                    return ['info', 'success', 'warn', 'danger'].indexOf(value) > -1;
                }
            }
        },
        computed: {
            className() {
                let obj;
                if (typeof this.classes === 'string') {
                    let classes = this.classes.split('\s+'), obj = {};
                    for (let i in classes) {
                        obj[i] = true;
                    }


                } else {
                    obj = Object.assign({}, this.classes);
                }

                return obj;
            },
            getText() {
                if (typeof this.text === 'function') {
                    return this.text();
                }


                return this.text;
            }
        }
    }
</script>
