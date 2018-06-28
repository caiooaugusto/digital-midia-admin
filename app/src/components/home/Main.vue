<template>
    <v-app
        top-toolbar
        footer
    >
        <toolbar></toolbar>
        <main class="home-main">
            <v-container>
                <vue-progress-bar></vue-progress-bar>
                <component
                    :is="selectedComponent"
                ></component>
            </v-container>
        </main>
        <home-footer
            :selectedComponent='selectedComponent'
            @switchComponent='selectedComponent = $event'
        ></home-footer>
    </v-app>
</template>

<script>
    import Toolbar from './Toolbar.vue'
    import HomeFooter from './Footer.vue'
    import Panels from '../panel/Panels.vue'
    import Incidents from '../panel/Incidents.vue'
    
    export default {
        components: {
            Toolbar,
            HomeFooter,
            Panels,
            Incidents
        },
        data () {
            return {
                selectedComponent: 'Panels'
            }
        }
    }
</script>

<style lang="stylus">
    .home-main
        padding-top 56px
        padding-bottom 120px
        min-height: 100vh
        min-width: 100vw

    .__cov-progress
        top 60px !important
</style>