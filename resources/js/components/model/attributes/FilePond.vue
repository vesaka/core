<template>
    <FilePond
        name="file"
        ref="pond"
        :label-idle="props.label"
        v-bind:allow-multiple="true"
        accepted-file-types="image/jpeg, image/png"
        :server="server"
        v-bind:files="myFiles"
        @processfile="afterProcess"
        @init="onInit"
        ></FilePond>
</template>
<script setup>
    import vueFilePond  from "vue-filepond";
    import "filepond/dist/filepond.min.css";
    import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
    import FilePondPluginImagePreview from "filepond-plugin-image-preview";

    const $emit = defineEmits(['flleid-created']);
    const server = {
        url: '/filepond/api',
        process: { 
            url: '/process',
            onload: (id) => {
                $emit('flleid-created', id);
            }
        },
        revert: '/process',
        patch: "?patch=",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content')
        }
    };
    
    const afterProcess = (res) => {
        console.log(res);
    }
    
    const onInit = () => {
        console.log('INIT');
    };
    
    const FilePond = vueFilePond(
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview
            );

    const props = defineProps({
        name: {
            type: String,
            default: 'file'
        },
        label: {
            type: String,
            default: ''
        }
    });
</script>
