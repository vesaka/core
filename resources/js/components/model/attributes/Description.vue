<template>
    <div class="form-group">
        <div ref="editor"></div>
    </div>
</template>
<script>
    import Quill from 'quill';
    import "quill/dist/quill.snow.css";
    export default {
        data() {
            return {
               
            };
        },
        props: {
            value: {
                type: String,
                default: 'test lorem ipsum'
            }
        },
        methods: {
            updateText() {
                this.$emit('update:modelValue', this.editor.root.innerHTML);
            }
        },
        watch: {
            text(n, o) {
                
            }
        },
        mounted() {
            this.editor = new Quill(this.$refs.editor, {
                modules: {
                    toolbar: [
                        [{header: [1, 2, 3, 4, false]}],
                        ['bold', 'italic', 'underline']
                    ]
                },
                theme: 'snow',
                formats: ['bold', 'underline', 'header', 'italic']
            });
//

            this.editor.root.innerHTML = this.value;

            // We will add the update event here
            this.editor.on('text-change', this.updateText);
        }

    }
</script>
<style>
    .ql-container {
        min-height: 360px;
    }
    
    .ql-editor {
        height: 360px;
    }
</style>
