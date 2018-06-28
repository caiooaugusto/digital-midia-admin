<template>
    <v-bottom-nav
        shift
        :value="true"
        :active.sync="e"
        :class="{
            'base': e === 0,
            'red': e === 1
        }"
    >
        <v-btn
            dark
            flat
            @click.native="showPanels()"
        >
            <span>Painéis</span>
            <v-icon>aspect_ratio</v-icon>
        </v-btn>
        <v-btn
            dark
            flat
            @click.native="showIncidents()"
        >
            <span>Incidentes</span>
            <v-icon>bug_report</v-icon>
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
                this.selectedComponent = 'Panels'
                this.$emit('switchComponent', this.selectedComponent)
            },
            showIncidents (e) {
                this.e = 1
                this.selectedComponent = 'Incidents'
                this.$emit('switchComponent', this.selectedComponent)
            }
        }
    }
</script>

<style lang="stylus">
</style>