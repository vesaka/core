<template>
    <div v-show="active">
        <slot>{{ title }}</slot>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                active: false,
                key: 0
            };
        },
        props: {
            selected: {
                type: Boolean,
                default: false
            },
            name: {
                type: String,
                default() {
                        return 'tab' + this.key;
                }
            },
            title: {
                type: String,
                default: 'Tab'
            }
        },
        mounted() {
            //this.active = this.selected;
        }
    }
</script>
