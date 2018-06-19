<template>
    <v-toolbar light class="toolbar-tasklist">
        <div class="toolbar-title">Mídia Digital Outdoor - Admin</div>
    </v-toolbar>
</template>

<script>
    export default {
        components: {
        },
        data () {
            return {
            }
        },   
        methods: {
        }
    }
</script>

<style lang="stylus" scoped>
    .toolbar-title
        font-size 20px
        font-weight 600

    .toolbar-tasklist
        z-index 1
        top 0px
        position fixed
        display flex
        justify-content center
        align-items center

    .logo-toolbar
        margin-top -8px
        width 300px !important
</style>