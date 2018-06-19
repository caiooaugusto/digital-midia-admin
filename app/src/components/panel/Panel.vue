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
                        Incidente
                        <br>
                        <v-toolbar
                            color="white"
                            dense
                        >
                            <v-text-field v-model="searchIncident" placeholder="Pesquise pelo titulo e descricao dos incidentes ou palavras chaves das soluções." prepend-icon="search" hide-details single-line></v-text-field>
                        </v-toolbar>
                    </div>
                    <form class="form-user">                       
                        <v-text-field
                            //v-model="props.item.incident.category"
                            label="Categoria"                          
                            required
                            class="elevation-1"
                        ></v-text-field>
                        <v-text-field
                            //v-model="props.item.incident.title"
                            label="Titulo"                          
                            required
                        ></v-text-field>
                        <v-text-field
                            //v-model="props.item.incident.description"
                            label="Descricao"                      
                            required
                        ></v-text-field>   
                        <v-text-field
                            //v-model="props.item.incident.module"
                            label="Modulo"                      
                            required
                        ></v-text-field>                      
                        <v-btn color="primary" @click="updatePanel(props.item)">Adicionar novo incidente</v-btn>
                    </form>                    
                </v-card>
                <v-card flat>
                    <div class="form-user">
                        Soluções
                        <br> 
                        <v-toolbar
                            color="white"
                            dense
                        >
                            <v-text-field v-model="searchIncident" placeholder="Pesquise pela descricao ou palavras chaves das soluções." prepend-icon="search" hide-details single-line></v-text-field>
                        </v-toolbar>
                    </div>
                    <form class="form-user"> 
                       
                        <v-text-field
                            v-model="props.item.name"
                            label="Descricao"                          
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.position"
                            label="Palavras chave"                      
                            required
                        ></v-text-field>
                        <v-btn color="primary" @click="updatePanel(props.item)">Adicionar nova solução</v-btn>
                    </form>                    
                </v-card>
            </template>
        </v-data-table>

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
        $validates: true,
        data () {
            return {
                search: "",
                showForm: false,
                items: [],
                panelForm: [],
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
            this.panelForm = [{name: '', position: ''}];
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
            searchPanels(){
                this.items = this.items.filter(panel => panel.name.toLowerCase().includes(this.search.toLowerCase()))
            },
            createPanel () {
                this.items.push({name: this.panelForm.name, position: this.panelForm.position})   
                this.showForm = false    
                this.clearPanelForm()         
                /*this.$http.post('panel/create', { title: this.title_form, description: this.description_form, deadline_date_string: this.deadline_date_string_form_formatted, status: this.status_form})
                    .then(response => {
                        this.dialog2 = false
                        this.getTasks()
                        return response.json()
                    }, error => {
                        alert("Oops, try again!")
                    })
                    .then(data => {})*/
            },
            deletePanel (panel) {
                /*this.$http.post('panel/delete', { id: task.id })
                    .then(response => {
                        this.getTasks()
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    })
                    .then(data => {})*/
            },
            updatePanel (panel) {
                /*this.$http.put('panel/update', task)
                    .then(response => {
                        this.getTasks()
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    })
                    .then(data => {})*/
            },
            getPanels(){
                /*this.$http.get('panel/get')
                    .then(response => {
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    }).then(data => {
                    this.items = data;
                })*/
            },
            clearPanelForm () {
                this.panelForm.name = ''
                this.panelForm.position = ''
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
</style>