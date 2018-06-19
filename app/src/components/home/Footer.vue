<template>
    <v-bottom-nav
        shift
        :value="true"
        :active.sync="e"
        :class="{
            'base': e === 0
        }"
    >
        <v-btn
            dark
            flat
            @click.native="showPanels(0)"
        >
            <span>Painéis</span>
            <v-icon>aspect_ratio</v-icon>
        </v-btn>
    </v-bottom-nav>
</template>

<script>
    import Vue from 'vue'

    export default {
        data () {
            return {
                e: 0
            }
        },
        methods: {
            showPanels (e) {
                this.e = 0
                this.selectedComponent = 'Panel'
                this.$emit('switchComponent', this.selectedComponent)
            }
        }
    }
</script>

<style lang="stylus">
</style>