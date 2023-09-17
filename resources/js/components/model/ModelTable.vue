<template>
    <TableLite :is-loading="table.loading"
               :is-slot-mode="true"
               :columns="table.columns"
               :rows="table.rows"
               :sortable="table.sortable"
               :total="table.total"
               @do-search="doSearch"
               @is-finished="table.loading = false"
               >
        
        <template v-slot:title="{ value }">
            <div class="flex flex-row items-center">
                <div class="bg-center bg-no-repeat bg-contain w-12 h-12" :style="{ backgroundImage: `url(${value.thumbnail})`}"></div>
                <strong v-html="value.title"></strong>
            </div>
        </template>

        <template v-slot:modified="{ value }">
            <span v-html="formatDate(value.modified)"></span>
        </template>
        <template v-slot:options="{ value }">
            <div class="inline-flex rounded-md shadow-sm" role="group">
            <a :href="getLink(value)" type="button" :class="btn('bg-yellow-600 hover:bg-yellow-100')">
                <PencilSquareIcon class="w-6 h-6"/>
            </a>
            <button type="button" :class="btn('bg-red-600 hover:bg-red-700')" @click="confirmDelete(value.id)">
                <TrashIcon class="w-6 h-6" />
            </button>
            </div>
        </template>
        

    </TableLite>
</template>
<script setup>
    import { onBeforeMount, onMounted, reactive, ref, computed, getCurrentInstance } from "vue";
    import {serverOptions, total, load, pager, formatDate } from '@core/utils/table';
    import { PencilSquareIcon, TrashIcon  } from '@heroicons/vue/24/solid';
    import { btn } from '@core/utils/classes';
    import TableLite from 'vue3-table-lite';
    import axios from 'axios';
    
    const props = defineProps({
        type: {
            type: String,
            default: 'model'
        },
        alias: {
            type: String,
            default: ''
        },
        headers: {
            type: Array,
            default() {
                    return [];
            }
        }
    });
    
    const columnClasses = ['p-0'];
    const columnStyles = { padding: 0};
    const table = reactive({
        page: 1,
        limit: 10,
        loading: false,
        columns: [
            {label: "ID", field: "id", sortable: true, width: '1%', isKey: true, columnStyles, columnClasses: ['text-center', ...columnClasses]},
            {label: "Title", field: "title", sortable: true, width: '45%', columnStyles},
            {label: "Last Update", field: "modified", sortable: true, width: '25%', columnStyles},
            {label: "", field: "options", sortable: false, columnStyles},
        ],
        rows: [],
        total: 0,
        sortable: {
            order: "id",
            sort: "asc",
        }
    });

    const doSearch = (offset, limit, order, sort) => {
        table.loading = true;
        table.page = offset;
        // if (window.event) {
        //     table.page = getPage(window.event);
        // }

        return axios.get(route(`admin::paginate.items`), {
            params: {
                page: table.page,
                limit,
                order,
                sort,
                type: props.type,
                alias: props.alias
            }
        }).then(response => {
                    const {current_page, data, per_page, to, total} = response.data;
                    table.rows = data;
                    table.total = total;
                    //table.page = current_page;
                    table.limit = limit;
                })
                .catch(err => {

                }).then(() => {
            table.loading = false;
        });
    };
    
    const getPage = ev => {
        const { target } = ev;
            let page = table.page;
            if (target.classList && target.classList.contains('page-link') && !target.getAttribute('aria-label')) {
                page = Number(target.textContent);                
            } else  {
                const parent = target.closest('.page-item:not(.disbled)');
                if (!parent) {
                    return page;
                }
                const type = parent.querySelector('.sr-only').textContent.toLowerCase();
                const lastPage = Math.ceil(table.total / table.limit);

                switch(type) {
                    case 'next':
                        page = Math.min(page+1, lastPage);
                        break;
                    case 'prev':
                        page = Math.max(1, page-1);
                        break;
                    case 'first':
                        page = 1;
                        break;
                    case 'last':
                        page = lastPage;
                        break;
                }
            }

            return page;
    };
    
    const getLink = (id) => {
        return route(`admin::${props.type}.edit`, { [props.type]: id });
    };
    
    const confirmDelete = (id) => {
        const response = confirm('Delete?');
        if (response) {
            axios.delete(route(`admin::${props.type}.destroy`, {[props.type]: id}))
                    .then(() => {
                        doSearch(table.page, );
                    });
        }
    }

    onBeforeMount(() => {
        doSearch(table.page, table.limit, 'id', 'asc');
    });


</script>
