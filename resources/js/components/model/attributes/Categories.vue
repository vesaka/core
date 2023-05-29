<template>
    <Treeselect
          :options="cetegories"
          :multiple="true"
          :modelValue="checkedItems"
          value-consists-of="LEAF_PRIORITY"
          @updated:modelValue="(v) => $emit('update:modelValue', v)"></Treeselect>
</template>
<script setup>
    import { ref, computed } from 'vue';
    import Treeselect from 'vue3-treeselect'
    import 'vue3-treeselect/dist/vue3-treeselect.css'

    const checkedItems = ref([]);
    const props = defineProps({
        items: {
            type: Array,
            default() {
                return  [];
            }
        },
        single: {
            type: Boolean,
            default: true
        },
        checkable: {
            type: Boolean,
            default: true
        },
        checkedItems: {
            type: Array,
            default: []
        }
    });
    
    const cetegories = computed(() => props.items);
    
    const select = (v) => {
        console.log(v);
        //$emit('update:modelValue')
    }

</script>
