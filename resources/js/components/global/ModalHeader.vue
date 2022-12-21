<template>
    <div>
        <back-button v-if="showCancelButton" @selectScreen="$emit('selectScreen', backTo)" :backTo="backTo"></back-button>
        <h4 class="py-2" v-html="title"></h4>
        <logout @loggedOut="$emit('selectScreen', backTo)"></logout>
    </div>
</template>
<script>
    import BackButton from '$m/media/game/components/buttons/Back';
    export default {
        props: {
            showCancelButton: {
                type: Boolean,
                default: true
            },
            title: {
                type: String,
                default: 'Title'
            },
            backTo: {
                type: String,
                default: 'menu'
            },
        },
        components: {BackButton}
    }
</script>
