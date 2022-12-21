<template>
    <button :class="btnClass" @click="save" disabled="">
        <slot>Save</slot>
    </button>
</template>
<script>
    export default {
        props: {
            classList: {
                type: [Object, String],
                default() {
                    return {}
                }
            }
        },
        methods: {
            save(ev) {
                this.$emit('click', ev);
            }
        },
        computed: {
            btnClass() {
                let classList = this.classList;
                if (typeof classList === 'string') {
                    classList = {};
                    classList[this.classList] = true;
                }
                return Object.assign({
                    'flex flex-wrap text-white bg-primary border-0 py-1 px-6': true,
                    'inline rounded-sm': true,
                    'focus:outline-none hover:bg-indigo-600': true,
                    'text-lg': true,
                    'disabled:bg-indigo-400': true,

                }, classList)
            },
        }
    }
</script>
