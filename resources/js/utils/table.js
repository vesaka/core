import { ref, reactive } from "vue";
import DataTable from "vue3-easy-data-table";
import 'vue3-easy-data-table/dist/style.css';
import dayjs from 'dayjs';
import axios from 'axios';

export const items = reactive([]);
export const loading = ref(false);
export const total = ref(0);
export const pager = ref({
    current_page: 1,
    per_page: 10,
    to: 10,
    total: 0,
    data: []
});

export const formatDate = (value) => {
    return dayjs(value).format('DD-MMM-YYYY');
}

export const load = (options = {}) => {
    loading.value = true;
    return axios.get(route(`admin::paginate.items`), { params: options})
            .then(response => {
                const { current_page, data, per_page, to, total} = response.data;
                pager.value = response.data;
                serverOptions.page = current_page;
                serverOptions.rowsPerPage = per_page;

                //total.value = response.data.total;
                
                return response.data.data;
            })
            .catch(err => {

            });
};

export const serverOptions = reactive({
        page: 1,
        rowsPerPage: 10,
        sortBy: 'age',
        sortType: 'desc',
    });

export default {
        items, total, load, loading, serverOptions, pager, formatDate
}

