<template>
    <DataTable :server-options="props.options"
               :server-items-length="total"
               :headers="props.headers"
               :items="items">
        <slot name="name" :item="n">
            <template #item-name="item">
                <span>test</span>
            </template>
        </slot>
        
    </DataTable>
</template>
<script setup>
    import { ref, reactive, computed, watch, onBeforeMount } from "vue";
    import DataTable from "vue3-easy-data-table";
    import 'vue3-easy-data-table/dist/style.css';
    import axios from 'axios';
    const items = ref([]);
    const loading = ref(false);
    const total = ref(0);
    const props = defineProps({
        type: {
            type: String,
            default: 'model'
        },
        headers: {
            type: Array,
            default() {
                return [];
            }
        },
        options: {
            type: Object,
            default() {
                return {
                    page: 1,
                    rowsPerPage: 5,
                    sortBy: 'age',
                    sortType: 'desc',
                };
            }
        }
    });

    const serverOptions = reactive({
        page: 1,
        rowsPerPage: 5,
        sortBy: 'age',
        sortType: 'desc',
    });

    const load = () => {
        loading.value = true;
        axios.get(route(`admin::${props.type}.items`))
                .then(response => {
                    items.value = response.data.data;
                    total.value = data.total;
                })
                .catch(err => {
                    
                });
//        items.value = data.items;
//        total.value = data.total;
//        loading.value = false;
    };
    
    onBeforeMount(() => {
        load();
    });
    
</script>
