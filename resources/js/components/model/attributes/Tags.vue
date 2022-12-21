<template>
    <div>
        <input class="form-control" type="text" :name="name" ref="tags" v-if="false">
        <vue-tags-input
            v-model="tag"
            :tags="tags"
            @tags-changed="tagsChanged"
            />
    </div>
</template>
<script>
    import VueTagsInput from '@sipec/vue3-tags-input';
    export default {
        data() {
            return {
                tag: '',
                tags: []
            };
        },
        components: {VueTagsInput},
        props: {
            name: {
                type: String,
                default: ''
            }
        },
        methods: {
            tagsChanged(list) {
                this.tags = list;
                this.$emit('update:modelValue', list.map(t => t.text));
            },
        },
        
        created() {

        }
    }
</script>
