<template>
    <li class="node-tree fex flex-row">
        <label>
            <input type="checkbox" v-model="checked"/>
            <span class="label">{{ node.text }}</span>
            <ul v-if="node.children && node.children.length">
                <node v-for="child in node.children" :node="child" @state:change="(id, v) => $emit('state:change', id, v)"></node>
            </ul>
        </label>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                checked: false
            }
        },
        name: 'node',
        props: {

            node: Object
        },
        watch: {
            checked(v) {
                this.node.checked = v;
                this.$emit('state:change', this.node.id, v);
            }
        },
        methods: {
            stateChange() {
                
            }
        },
        created() {
            this.checked = !!this.node.checked;
        }
    };
</script>
