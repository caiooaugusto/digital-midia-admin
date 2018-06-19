<template>
    <router-view></router-view>
</template>

<script>
    import Main from './components/home/Main.vue'
    
    export default {
        components: {
            Main: Main
        },
        data () {
            return {}
        }
    }
</script>

<style lang="stylus">  
    @import '../node_modules/vuetify/src/stylus/settings/_colors'
    $theme := {
        primary: $blue.base
        accent: $blue.accent-4
        secondary: $indigo.base
        info: $yellow.base
        warning: $orange.base
        error: $red.base
        success: $green.base
    }
    @import '../node_modules/vuetify/src/stylus/main'
</style>