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
                        <v-btn color="primary" @click="updatePanel(props.item)">Atualizar</v-btn>
                        <v-btn color="red" style='color: white' @click="deletePanel(props.item)">Apagar</v-btn>
                    </form>                    
                </v-card>
                <v-card flat>
                    <div class="form-user">
                        <div class="title">Incidente</div>
                        <br>
                        <br>
                        <v-layout row>
                            <v-flex xs9>
                                <v-toolbar
                                    color="white"
                                    dense
                                >
                                    <v-text-field v-model="searchIncident" placeholder="Pesquise pelo titulo, descricao, categoria ou palavras chaves dos incidentes." prepend-icon="search" hide-details single-line></v-text-field>
                                </v-toolbar>     
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="showIncidentForm = true">Adicionar novo incidente</v-btn>
                        </v-layout>
                    </div>
                    <form class="form-user" :class="{ hide: !showIncidentForm, hide: props.item.incident.id }">
                        <v-layout row>
                            <v-flex xs8>
                                <v-select
                                    v-model="props.item.incident.category"
                                    :items="incidentsCategories"
                                    label="Categoria"
                                    single-line
                                    required
                                ></v-select>    
                            </v-flex>
                            <v-spacer></v-spacer>                            
                            <v-btn color="primary" @click="showIncidentCategoryForm = true">Adicionar nova categoria de incidente</v-btn>
                        </v-layout>
                        <v-text-field
                            v-model="props.item.incident.keyWords"
                            label="Palavras chave"                      
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.incident.title"
                            label="Titulo"                          
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.incident.description"
                            label="Descricao"                      
                            required
                        ></v-text-field>   
                        <v-text-field
                            v-model="props.item.incident.module"
                            label="Modulo"                      
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.incident.solution"
                            label="Solução"                          
                            required
                        ></v-text-field>
                        <v-btn color="primary" @click="createIncident(props.item)">Salvar</v-btn>
                        <v-btn color="red" style='color: white' @click="deleteIncident(props.item)">Remover</v-btn>                                 
                    </form>                    
                </v-card>
            </template>
        </v-data-table>

        <v-dialog v-model="showIncidentCategoryForm" max-width="500px">
            <v-card>
                <v-card-title>
                    Novo Categoria de Incidente
                </v-card-title>
                <v-card-text>
                    <form class="form-user">
                        <v-text-field
                            v-model="incidentCategoryForm.name"
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
                showIncidentCategoryForm: false, 
                showIncidentForm: false,              
                panelForm: [],                              
                incidentCategoryForm: [],
                items: [],                
                incidents: [],
                incidentsCategories: [],
                headers: [
                    { text: 'Nome', value: 'name', align: 'left' },
                    { text: 'Localização', value: 'position' }
                ]
            }
        },
        mounted(){
            //this.items = [
            //    {name: 'oi', position: 'Rua tal tal tal tal'},
            //    {name: 'tchau', position: 'Rua tal tal tal tal'},
            //]
            this.panelForm = [{name: '', position: ''}]
            this.incidentCategoryForm = [{name: ''}]

            this.getLocalStorage()

            //get items
            //get incidents
            //get incidentsCategories

            /*this.$http.get('task/get')
                .then(response => {
                    return response.json()
                }, error => {
                    alert(JSON.stringify(error))
                }).then(data => {
                this.allItems = data;
                this.items = data;
            })*/
        },
        watch: {
            search (query, queryOld) {
                if(query.length) {
                    this.searchPanels()
                }else{
                    this.items = this.allItems
                }
            }
        },
        methods: {
            updateLocalStorage(){
                localStorage.setItem('items', JSON.stringify(this.items))
                localStorage.setItem('incidents', JSON.stringify(this.incidents))
                localStorage.setItem('incidentsCategories', JSON.stringify(this.incidentsCategories))
            },
            getLocalStorage(){
                this.items = JSON.parse(localStorage.getItem('items')) ? JSON.parse(localStorage.getItem('items')) : []
                this.incidents = JSON.parse(localStorage.getItem('incidents')) ? JSON.parse(localStorage.getItem('incidents')) : []
                this.incidentsCategories = JSON.parse(localStorage.getItem('incidentsCategories')) ? JSON.parse(localStorage.getItem('incidentsCategories')) : []                    
            },

            searchPanels(){
                this.items = this.items.filter(panel => panel.name.toLowerCase().includes(this.search.toLowerCase()))
            },

            createPanel () {
                this.items.push({
                    id: this.items.length ? this.items[this.items.length].id + 1 : 1,
                    name: this.panelForm.name,
                    position: this.panelForm.position,
                    incident: {
                        id: null,
                        category: "",
                        keyWords: "",
                        title: "",
                        description: "",
                        module: "",
                        solution: ""
                    }
                })
                this.updateLocalStorage()
                this.showForm = false    
                this.clearPanelForm()
            },
            createIncident (item) {  
                console.log(item.incident)              
                this.incidents.push(item.incident)
                this.items = this.items.map(currentItem => {
                    if(currentItem == item.id){
                        currentItem.incident = item.incident
                    }
                })   
                console.log('this.items')
                console.log(this.items)
                this.updateLocalStorage()
            },
            createIncidentCategory() {
                this.incidentsCategories.push(
                    {
                        text: this.incidentCategoryForm.name
                    }
                )                
                this.showIncidentCategoryForm = false    
                this.clearIncidentCategoryForm() 
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
                })          
                this.updateLocalStorage()
            },
            updatePanel (item) {
                this.updateLocalStorage()
            },
            clearPanelForm () {
                this.panelForm.name = ''
                this.panelForm.position = ''
            },
            clearIncidentCategoryForm () {
                this.incidentCategoryForm.name = ''
            }
        }
    }
</script>

<style lang="stylus" scoped>
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