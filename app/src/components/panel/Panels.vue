<template>
    <div>
        <v-toolbar
            class="search-toolbar"
            color="white"
            dense
        >
            <v-text-field v-model="search" prepend-icon="search" hide-details single-line></v-text-field>
        </v-toolbar>

        <v-data-table
            class="users-data-table elevation-4"
            :headers="headers"
            :items="items"
            hide-actions
            item-key="name"            
        >
            <template slot="items" scope="props">                
                <tr
                    @click="props.expanded = !props.expanded"
                >
                    <td>{{ props.item.name }}</td>
                    <td class="text-xs-right">{{ props.item.position }}</td>
                </tr>
            </template>
            <template slot="expand" scope="props">
                <v-card flat>
                    <form class="form-user">
                        <v-text-field
                            v-model="props.item.name"
                            label="Nome"                          
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.position"
                            label="Localização"                      
                            required
                        ></v-text-field>                        
                    </form>                    
                </v-card>
                <v-card flat>
                    <div class="form-user">
                        <div class="title">Incidentes</div>
                        <br>
                        <br>
                        <v-layout row>
                            <v-flex xs12>
                                <v-toolbar
                                    color="white"
                                    dense
                                >
                                    <v-text-field v-model="searchIncident" @change="selectedPanel = props.item" placeholder="Pesquise pelo titulo, descricao, categoria ou palavras chaves dos incidentes." prepend-icon="search" hide-details single-line></v-text-field>
                                </v-toolbar>     
                            </v-flex>
                        </v-layout>
                    </div>                
                </v-card>
                <v-card>         
                    <div class="form-user">
                        <div v-for="(incident, index) in props.item.incidents" :key="incident.title">
                            {{index+1}}. {{incident.title}} - {{incident.description}} - {{incident.category}} - {{incident.keyWords}} - {{incident.module}} - {{incident.solution}}
                        </div>
                        <v-btn color="primary" @click="updatePanel(props.item)">Salvar</v-btn>
                        <v-btn color="red" style='color: white' @click="deletePanel(props.item)">Apagar</v-btn>
                    </div>
                </v-card>
            </template>
        </v-data-table>

        <v-dialog fullscreen v-model="showIncidentsSearch">
            <v-card>
                <v-card-title>
                    Resultado da Pesquisa
                </v-card-title>
                <v-card-text>
                    <v-toolbar
                        color="white"
                        dense
                    >
                        <v-text-field v-model="searchIncident" placeholder="Pesquise pelo titulo, descricao, categoria ou palavras chaves dos incidentes." prepend-icon="search" hide-details single-line></v-text-field>
                    </v-toolbar>  
                    <v-data-table
                        class="users-data-table elevation-4"
                        :headers="headersIncidents"
                        :items="searchIncidentResult"
                        hide-actions
                        item-key="title"            
                    >
                        <template slot="items" scope="props">                
                            <tr
                                @click="props.expanded = !props.expanded"
                            >
                                <td>{{ props.item.category }}</td>
                                <td class="text-xs-right">{{ props.item.keyWords }}</td>
                                <td class="text-xs-right">{{ props.item.title }}</td>
                                <td class="text-xs-right">{{ props.item.description }}</td>
                                <td class="text-xs-right">{{ props.item.module }}</td>
                                <td class="text-xs-right">{{ props.item.solution }}</td>
                            </tr>
                        </template>                        
                        <template slot="expand" scope="props">
                            <v-card flat>
                                <form class="form-user">
                                    <v-layout row>
                                        <v-flex xs12>
                                            <v-select
                                                disabled
                                                v-model="props.item.category"
                                                :items="incidentsCategories"
                                                :key="props.item.category"
                                                label="Categoria"                                    
                                                @change="onSelectIncidentCategory($event, props.item)"
                                                single-line
                                                required
                                                chips
                                            ></v-select>    
                                        </v-flex>
                                    </v-layout>
                                    <v-text-field
                                        disabled
                                        v-model="props.item.keyWords"
                                        label="Palavras chave"                      
                                        required
                                    ></v-text-field>
                                    <v-text-field
                                        disabled
                                        v-model="props.item.title"
                                        label="Titulo"                          
                                        required
                                    ></v-text-field>
                                    <v-text-field
                                        disabled
                                        v-model="props.item.description"
                                        label="Descricao"                      
                                        required
                                    ></v-text-field>   
                                    <v-text-field
                                        disabled
                                        v-model="props.item.module"
                                        label="Modulo"                      
                                        required
                                    ></v-text-field>
                                    <v-text-field
                                        disabled
                                        v-model="props.item.solution"
                                        label="Solução"                          
                                        required
                                    ></v-text-field>                                        
                                    <v-btn color="primary" @click="selectIncident(props.item)">Selecionar</v-btn>    
                                </form>                                
                            </v-card>
                        </template>
                    </v-data-table>
                                                  
                    <v-card-actions>                        
                        <v-btn color="primary" flat @click.stop="showIncidentsSearch=false">Cancelar</v-btn>
                    </v-card-actions> 
                </v-card-text>     
            </v-card>
        </v-dialog>

        <v-dialog v-model="showForm" max-width="500px">
            <v-card>
                <v-card-title>
                    Novo painel
                </v-card-title>
                <v-card-text>
                    <form class="form-user">
                        <v-text-field
                            v-model="panelForm.name"
                            label="Nome"
                            :counter="100"                           
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="panelForm.position"
                            label="Localização"
                            required
                        ></v-text-field>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="createPanel">Salvar</v-btn>
                    <v-btn color="warning" @click="clearPanelForm">Limpar campos</v-btn>
                    <v-btn color="primary" flat @click.stop="showForm=false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-btn
            class="fab-sync"
            dark
            fab
            @click.stop="showForm = true"
        >
            <v-icon class='white--text'>add</v-icon>
        </v-btn>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                search: "",                
                searchIncident: "",
                showForm: false, 
                showIncidentsSearch: false,     
                panelForm: {name: '', position: '', incidents: []},             
                items: [],
                panels: [],
                incidents: [],
                selectedPanel: null,
                searchIncidentResult: [],
                incidentsCategories: [],
                headers: [
                    { text: 'Nome', value: 'name', align: 'left' },
                    { text: 'Localização', value: 'position' }
                ],
                headersIncidents: [                    
                    { text: 'Categoria', value: 'category' },
                    { text: 'Palavras chave', value: 'keyWords' },                    
                    { text: 'Titulo', value: 'title', align: 'left' },
                    { text: 'Descricao', value: 'description', align: 'left' },
                    { text: 'Modulo', value: 'module', align: 'left' },
                    { text: 'Solucao', value: 'solution', align: 'left' },
                ]
            }
        },
        mounted(){
            this.getLocalStorages()
        },
        watch: {
            search (query, queryOld) {
                if(query.length) {
                    this.searchPanels()
                }else{
                    this.items = this.panels
                }
            },
            searchIncident (query, queryOld) {
                if(query.length) {
                    this.searchIncidents()
                }else{
                    this.searchIncidentResult = this.incidents
                }
            }
        },
        methods: {
            updateLocalStorage(){
                localStorage.setItem('panels', JSON.stringify(this.items))
                localStorage.setItem('incidents', JSON.stringify(this.incidents))
                localStorage.setItem('incidentsCategories', JSON.stringify(this.incidentsCategories))
            },
            getLocalStorages(){
                this.panels = JSON.parse(localStorage.getItem('panels')) ? JSON.parse(localStorage.getItem('panels')) : []
                this.items = this.panels
                this.incidents = JSON.parse(localStorage.getItem('incidents')) ? JSON.parse(localStorage.getItem('incidents')) : []
                this.incidentsCategories = JSON.parse(localStorage.getItem('incidentsCategories')) ? JSON.parse(localStorage.getItem('incidentsCategories')) : []                    
            },
            getLocalStorageIncidents(){                
                this.incidents = JSON.parse(localStorage.getItem('incidents')) ? JSON.parse(localStorage.getItem('incidents')) : []
            },

            searchPanels(){
                this.items = this.panels.filter( (panel) => {
                    console.log(panel)
                    console.log(this.search.toLowerCase())
                    if(panel.name.toLowerCase().includes(this.search.toLowerCase()) || panel.position.toLowerCase().includes(this.search.toLowerCase())) {
                        return panel
                    }
                })         
            },
            searchIncidents(){
                this.searchIncidentResult = this.incidents.filter( (incident) => {
                    if(incident.title.toLowerCase().includes(this.searchIncident.toLowerCase()) || incident.description.toLowerCase().includes(this.searchIncident.toLowerCase()) ||
                    incident.keyWords.toLowerCase().includes(this.searchIncident.toLowerCase()) || incident.category.toLowerCase().includes(this.searchIncident.toLowerCase())) {
                        return incident
                    }
                })
                this.showIncidentsSearch = true
            },

            createPanel () {           
                this.items.push(this.panelForm)
                this.updateLocalStorage() 
                this.showForm = false                             
                this.clearPanelForm()
                this.getLocalStorages()
            },

            selectIncident(item) {                
                console.log('selected panel')                
                console.log(this.selectedPanel)
                console.log('selected incident')
                console.log(item)

                this.items.map( (panel) => {
                    if(panel.name === this.selectedPanel.name){
                        panel.incidents.push(item)
                    }
                })
                this.updateLocalStorage()
                this.showIncidentsSearch = false
            },

            onSelectIncidentCategory(e, item){
                this.items = this.items.map(currentItem => {
                    if(currentItem == item.id){
                        currentItem = {
                            ...currentItem,
                            category: e
                        }
                    }
                    return currentItem
                })          
                console.log(`this.items on onSelectIncidentCategory`)
                console.log(this.items)
                this.updateLocalStorage()
            },
            deletePanel (item) {      
                this.items = this.items.filter(currentItem => currentItem =! item.id)          
                this.updateLocalStorage()
            },
            deleteIncident (item) {
                this.items = this.items.map(currentItem => {
                    if(currentItem == item.id){
                        currentItem.incident = {      
                            id: item.id,                      
                            category: "",
                            keyWords: "",
                            title: "",
                            description: "",
                            module: "",
                            solution: ""
                        }
                    }
                    return currentItem
                })          
                this.updateLocalStorage()
            },
            updatePanel () {
                this.updateLocalStorage()
            },
            showIncident(item) {
                this.items = this.items.map(currentItem => {
                    if(currentItem == item.id){
                        currentItem.incident = {
                            id: this.items.length ? this.items[this.items.length-1].id + 1 : 1,
                            category: "",
                            keyWords: "",
                            title: "",
                            description: "",
                            module: "",
                            solution: ""
                        }
                    }
                    return currentItem
                })                   
                this.updateLocalStorage()
            },
            clearPanelForm () {
                this.panelForm.name = ''
                this.panelForm.position = ''
                this.panelForm.incidents = []
            },
            clearIncidentCategoryForm () {
                this.incidentCategoryForm.name = ''
            }
        }
    }
</script>

<style lang="stylus" scoped>
    .fullscreen-modal
        width 90vw
        height 90vh

    .fab-sync
        position fixed
        bottom 75px
        right 15px

    .search-toolbar
        z-index 1
        position fixed
        width 90%
        top 80px
        left 5%
        margin 0px

    .users-data-table
        z-index 0
        position absolute
        width 90%
        top 145px
        left 5%
        margin-bottom 70px

    .form-user
        margin 10px 30px 30px 30px

    .hide
        visibility hidden
</style>