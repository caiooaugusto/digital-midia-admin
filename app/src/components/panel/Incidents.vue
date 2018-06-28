<template>
    <div>
        <v-toolbar
            class="search-toolbar"
            color="white"
            dense
        >
            <v-text-field v-model="searchIncident" prepend-icon="search" hide-details single-line></v-text-field>
        </v-toolbar>

        <v-data-table
            class="users-data-table elevation-4"
            :headers="headersIncidents"
            :items="items"
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
                        <v-btn color="primary" @click="updateIncident(props.item)">Atualizar</v-btn>
                        <v-btn color="red" style='color: white' @click="deleteIncident(props.item)">Apagar</v-btn>                            
                    </form>                                
                </v-card>
            </template>
        </v-data-table>

        <v-dialog v-model="showIncidentForm" max-width="500px">
            <v-card>
                <v-card-title>
                    Novo Incidente
                </v-card-title>
                <v-card-text>
                     <form class="form-user">
                    <v-layout row>
                        <v-flex xs8>
                            <v-select
                                v-model="incidentForm.category"
                                :items="incidentsCategories"
                                :key="incidentForm.category"
                                label="Categoria"
                                single-line
                                required
                                chips
                            ></v-select>    
                        </v-flex>
                        <v-flex xs4>
                            <v-btn color="primary" flat @click.stop="showIncidentCategoryForm = true">Nova Categoria</v-btn> 
                        </v-flex>
                    </v-layout>
                    <v-text-field
                        v-model="incidentForm.keyWords"
                        label="Palavras chave"                      
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="incidentForm.title"
                        label="Titulo"                          
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="incidentForm.description"
                        label="Descricao"                      
                        required
                    ></v-text-field>   
                    <v-text-field
                        v-model="incidentForm.module"
                        label="Modulo"                      
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="incidentForm.solution"
                        label="Solução"                          
                        required
                    ></v-text-field>                               
                </form>
                </v-card-text>                
                <v-card-actions>
                    <v-btn color="primary" @click="createIncident">Salvar</v-btn>
                    <v-btn color="warning" @click="clearIncidentForm">Limpar Campos</v-btn>
                    <v-btn color="primary" flat @click.stop="showIncidentForm=false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showIncidentCategoryForm" max-width="500px">
            <v-card>
                <v-card-title>
                    Novo Categoria de Incidente
                </v-card-title>
                <v-card-text>
                    <form class="form-user">
                        <v-text-field
                            v-model="incidentCategoryForm"
                            label="Nome"
                            :counter="100"                           
                            required
                        ></v-text-field>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="createIncidentCategory">Salvar</v-btn>
                    <v-btn color="warning" @click="clearIncidentCategoryForm">Limpar campos</v-btn>
                    <v-btn color="primary" flat @click.stop="showIncidentCategoryForm=false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-btn
            class="fab-sync"
            dark
            fab
            @click.stop="showIncidentForm = true"
        >
            <v-icon class='white--text'>add</v-icon>
        </v-btn>

    </div>
</template>

<script>
    export default {
        data () {
            return {
                searchIncident: "",  
                showIncidentForm: false, 
                showIncidentCategoryForm: false,
                incidentForm: {title: '', description: '', category: '', keyWords: '', module: '', solution: ''},                              
                incidentCategoryForm: '', 
                items: [],
                incidents: [],
                incidentsCategories: [],
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
            searchIncident (query, queryOld) {
                if(query.length) {
                    this.searchIncidents()
                }else{
                    this.items = this.incidents
                }
            }
        },
        methods: {
            updateLocalStorageIncidents(){
                localStorage.setItem('incidents', JSON.stringify(this.incidents))
            },
            updateLocalStorageIncidentCategories(){
                localStorage.setItem('incidentsCategories', JSON.stringify(this.incidentsCategories))
            },
            getLocalStorages(){
                this.incidents = JSON.parse(localStorage.getItem('incidents')) ? JSON.parse(localStorage.getItem('incidents')) : []
                this.items = this.incidents
                this.incidentsCategories = JSON.parse(localStorage.getItem('incidentsCategories')) ? JSON.parse(localStorage.getItem('incidentsCategories')) : []                    
            },
            getLocalStorageIncidents(){                
                this.incidents = JSON.parse(localStorage.getItem('incidents')) ? JSON.parse(localStorage.getItem('incidents')) : []
            },
            searchIncidents(){
                this.items = this.incidents.filter( (incident) => {
                    if(incident.title.toLowerCase().includes(this.searchIncident.toLowerCase()) || incident.description.toLowerCase().includes(this.searchIncident.toLowerCase()) ||
                    incident.keyWords.toLowerCase().includes(this.searchIncident.toLowerCase()) || incident.category.toLowerCase().includes(this.searchIncident.toLowerCase())) {
                        return incident
                    }
                })
                this.showIncidentsSearch = true
            },
            createIncident () {   
                console.log('novo incidente', this.incidentForm)
                this.incidents.push(this.incidentForm)
                this.items = this.incidents
                this.updateLocalStorageIncidents()
                this.showIncidentForm = false
            },
            createIncidentCategory() {
                console.log('nova categoria', this.incidentCategoryForm)
                this.incidentsCategories.push(this.incidentCategoryForm)                
                this.updateLocalStorageIncidentCategories()                   
                this.clearIncidentCategoryForm() 
                this.showIncidentCategoryForm = false 
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
            clearIncidentForm () {                
            },
            clearIncidentCategoryForm () {
                this.incidentCategoryForm = ''
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