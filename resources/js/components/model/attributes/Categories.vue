<template>
    <tree ref="tree"
          :isCheckable="checkable"
          :items="items"
          v-model:checkedItems="selectedItems"></tree>
</template>
<script setup>
    import { ref } from 'vue';
    import Tree from 'vue3-tree-vue';

    const selectedItems = ref([]);
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
    });

    const check = () => {

    }

</script>
