<template>
    <section class="text-gray-600 bg-white rounded body-font overflow-hidden">
        <div class="container px-2 py-4 mx-auto">
            <div class="mx-auto flex flex-wrap px-2">
                <div class="w-full md:w-2/3 px-2 py-3">
                    <div :class="formGroup">
                        <h2 :class="labelClass">Title</h2>
                        <input type="text" :class="inputClass"
                               v-model="model.title"
                               @change="clearTitleError"/>
                        <span :class="errorClass"></span>
                    </div>
                    <div :class="formGroup">
                        <h2 :class="labelClass">Description</h2>
                        <Description v-model="model.content" :value="model.content"></Description>
                        <span :class="errorClass"></span>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-2 py-3">
                    <featured-image v-model:file="model.file"
                                    v-model:crop="model.crop"
                                    v-model:src="model.imgSrc"
                                    v-if="'cropper' === props.uploader"
                                    ></featured-image>
                    <FilePond v-if="'filepond' === props.uploader" @flleid-created="fileUploaded"></FilePond>
                    <div :class="formGroup">
                        <h2 :class="labelClass">Tags</h2>
                        <tags v-model="model.meta.tags" :value="tags"></tags>
                    </div>
                    <div :class="formGroup">
                        <h2 :class="labelClass">Category</h2> 
                        <Categories :items="categoriesItems" :value="checkedCategories" v-model="model.category"></Categories>
                        <span :class="errorClass"></span>
                    </div>
                </div>
            </div>
            <div v-for="field in props.meta">

            </div>
            <slot name="meta"/>
        </div>
        <div>
            <upload-button @click="save" :disabled="loading">
                <template #after>
                    <span v-if="loading">Loading...</span>
                </template>
            </upload-button>
        </div>
        <Notifications position="bottom right">
            SAVED!  
        </Notifications>
    </section>
</template>
<script setup>
    import { reactive, watch, computed, onBeforeMount } from 'vue';
    import Notifications, { useNotification  } from '@kyvg/vue3-notification';
    import { useToast } from "vue-toastification";
    import Description from './attributes/Description.vue';
    import Tags from './attributes/Tags.vue';
    import Categories from './attributes/Categories.vue';
    import FeaturedImage from './attributes/FeaturedImage.vue';
    import FilePond from './attributes/FilePond.vue';
    import UploadButton from '../global/UploadButton.vue';
//    import Spinnner from '../global/Spinner.vue';
    import axios from 'axios';

    import { faker } from '@faker-js/faker';
    const props = defineProps({
        type: {
            type: String,
            default: 'model'
        },
        alias: {
            type: String,
            default: ''
        },
        uploader: {
            type: String,
            default: 'cropper'
        },
        saveUrl: {
            type: String,
            default: '/admin/{type}'
        },
        meta: {
            type: Array,
            default: () => {
                return [];
            }
        },
    });

    const {notify} = useNotification();
    const toast = useToast();

    let saving = false;
    const model = reactive({
        id: 0,
        title: faker.lorem.sentence(3),
        alias: props.alias,
        name: '',
        type: props.type,
        content: faker.lorem.sentence(),
        categories: [],
        category: [],
        fileIds: [],
        meta: {},
        crop: {},
        file: null,
        gallery: [],
        imgSrc: ''

    });

    const loading = computed(() => {
        return true === saving;
    });

    const save = () => {
        saving = true;

        var formData = new FormData();

        for (var key in model) {
            if (model[key] instanceof File || ['boolean', 'string', 'number'].includes(typeof model[key])) {
                formData.append(key, model[key]);
            } else if ((typeof model[key] === 'object') || Array.isArray(model[key])) {
                const obj = model[key];

                for (let name in obj) {
                    formData.append(`${key}[${name}]`, obj[name]);
                }

            }

        }

        axios.post(route(`admin::${model.type}.store`), formData, {headers: {
                "Content-Type": "multipart/form-data",
            }}).then(response => {

            toast("Saved Successfuly");

            model.id = response.data.model.id;
        }).catch(error => {
                    toast.error("NOT SAVED");
                }).then(() => {
            saving = false;
        });
    };

    const fileUploaded = (id) => {
        model.fileID = id;
    }

    const categoriesItems = computed(() => {
        if (!Array.isArray($categories)) {
            return [];
        }

        const fn = (category, depth = 0) => {
            const isParent = Array.isArray(category.children);
            const item = {
                id: `${category.id}`,
                name: category.name,
                label: category.title,
                type: isParent ? 'folder' : 'item',
                checkedStatus: category.id === model.category,
                data: { depth },
            };

            if (Array.isArray(category.children)) {
                item.children = category.children.map(fn, depth + 1);
            }

            return item;
        };

        return $categories.map(fn);
    });

    const categories = computed(() => {
        if (!Array.isArray($categories)) {
            return [];
        }

        let list = $categories, categories = [];
//                if (!this.model.category_id) {
//                    this.model.category_id = list[0].id;
//                }

        const fn = (category, depth = 0) => {
            const item = {
                id: category.id,
                text: category.name,
                checkable: true,
                selectable: true,
                expandable: true,
                depth,
                state: {
                    checked: category.id === model.category,
                    selected: false,
                    expanded: false
                }
            };

            if (Array.isArray(category.children)) {
                item.nodes = category.children.map(fn, depth + 1);
            }

            return item;
        };

        return $categories.map(fn);
    });

    const inputClass = {
        'border-2 border-grey-600 px-3 py-3': true,
        'placeholder-blueGray-300 text-blueGray-600': true,
        'relative bg-white bg-white rounded': true,
        'text-sm shadow outline-none': true,
        'focus:outline-none focus:ring w-full': true
    };

    const formGroup = {
        'w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0': true
    };

    const labelClass = {
        'text-md font-bold title-font text-gray-500 tracking-widest': true
    };

    const errorClass = {
        'px-3 text-red-700': true
    };

    const checkedCategories = computed(() => {
        if (!Array.isArray($model.categories)) {
            return [];
        }
        return $model.categories.reduce((result, item) => {
            result.push({
                id: item.id,
                label: item.name
            });
            return result;
        }, []);
    });

    const tags = computed(() => {
        return $model.meta ? $model.meta.tags || [] : [];
    });


    onBeforeMount(() => {
        for (let key in model) {
            model[key] = $model[key] || '';
        }
        
        if (!model.id) {
            model.type = props.type;
        }

        model.alias = props.alias;
        if (Array.isArray($model.categories)) {
            model.category = $model.categories.map(c => c.id);
        }


    });


</script>