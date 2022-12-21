<template>
    <div class="flex flex-row justify-between py-4">
        <div class="w-1/4">
            <v-select :options="perPage" v-model="value.length" :with-default="false"></v-select>
        </div>
        <input-field class="w-1/2" v-model="value.search" placeholder="Search..."></input-field>
    </div>
</template>
<script>
    import InputField from '@/components/global/InputField';
    import vSelect from '@/components/global/Select';
    import VueSelect from 'vue-select';
    export default {
        data() {
            return {
                url: '',
                params: {
                    
                }
            };
        },
        components: {vSelect, InputField, VueSelect},
        props: {
            value: {
                type: Object,
                default() {
                    return {}
                }
            },
            perPage: {
                type: Array,
                default() {
                    return [10, 25, 50];
                }
            }
        },
    }
</script>