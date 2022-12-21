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
                        <description v-model="model.content"></description>
                        <span :class="errorClass"></span>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-2 py-3">
                    <featured-image v-model:file="model.file"
                                    v-model:crop="model.crop"
                                    v-model:src="model.imgSrc"
                                    ></featured-image>
                    <div :class="formGroup">
                        <h2 :class="labelClass">Tags</h2>
                        <tags v-model="model.meta.tags"></tags>
                        <!--                        <span :class="errorClass" v-validate:post.tags="`required::Tags are required`"></span>-->
                    </div>
                    <div :class="formGroup">
                        <h2 :class="labelClass">Category</h2> 
                        <Categories :items="categoriesItems" v-model="model.category"></Categories>
                        <span :class="errorClass"></span>
                    </div>
                </div>
            </div>

            <slot name="meta"/>
        </div>
        <div>
            <upload-button @click="save" :disabled="loading"></upload-button>
        </div>
    </section>
</template>
<script setup>
    import { reactive, watch, computed, onBeforeMount } from 'vue';
    import Description from './attributes/Description.vue';
    import Tags from './attributes/Tags.vue';
    import Category from './attributes/Category.vue';
    import Categories from './attributes/Categories.vue';
    import FeaturedImage from './attributes/FeaturedImage.vue';
    import Gallery from './attributes/Gallery.vue';
    import ValidationMixin from '../mixins/main/validation-mixin';
    import ClassMixin from '../mixins/main/class-mixin';
    import UploadButton from '../global/UploadButton.vue';

    import axios from 'axios';
    const props = defineProps({
        type: {
            type: String,
            default: 'model'
        },
        saveUrl: {
            type: String,
            default: '/admin/{type}'
        }
    });
    
    

    let saving = false;
    const model = reactive({
        id: 0,
        title: 'test',
        name: '',
        type: 'model',
        content: 'test',
        category_id: 0,
        categories: [],
        category: [],
        fileIds: [],
        meta: {},
        crop: {},
        file: null,
        gallery: []

    });
    watch(model, (n, o) => {
        console.log(n.category);
        if (n.category !== o.category) {
            console.log(n.category);
        }
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
                    console.log(obj[name]);
                    formData.append(`${key}[${name}]`, obj[name]);
                }

            }

        }

        axios.post(route(`admin::${model.type}.store`), formData, {headers: {
                "Content-Type": "multipart/form-data",
            }})
                .then(response => {
                    //model = Object.assign(model, response.model);
                })
                .catch(error => {

                }).then(() => {
            saving = false;
        })
    };

    const threeOptions = computed({
        events: {
            expanded: {
                state: true,
                fn: null
            },
            selected: {
                state: false,
                fn: null
            },
            checked: {
                state: false,
                fn: null
            },
            editableName: {
                state: false,
                fn: null,
                calledEvent: null
            }
        },
        addNode: {state: false, fn: null, appearOnHover: false},
        editNode: {state: false, fn: null, appearOnHover: false},
        deleteNode: {state: false, fn: null, appearOnHover: false},
        showTags: false
    });
    
    const categoriesItems = computed(() => {
        if (!Array.isArray($categories)) {
            return [];
        }
        
        const fn = (category, depth = 0) => {
            const isParent = Array.isArray(category.children);
            const item = {
                id: `${category.id}`,
                name: category.name,
                type: isParent ? 'folder' : 'item',
                checkedStatus: category.id === model.category,
                data: {
                    depth
                },
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
    
    

    onBeforeMount(() => {
        Object.assign(model, window[props.type] || {});
        model.type = props.type;
    });


</script>