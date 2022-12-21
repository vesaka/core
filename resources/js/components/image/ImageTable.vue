<template>
    <ModelList :options="options" :headers="headers"></ModelList>
</template>
<script setup>
    import ModelList from '@core/components/model/ModelList.vue';

    const headers = [
        {text: "Name", value: "name"},
        {text: "Title", value: "title"},
        {text: "Date added", value: "created_at", sortable: true},
        {text: "Last update", value: "updated_at", sortable: true},
        {text: "Options", value: "favouriteSport"},
    ];
    
    const options = {
        
    };
</script>
