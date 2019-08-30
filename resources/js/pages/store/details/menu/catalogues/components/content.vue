<template>
    <div>
        <v-card-text>
            <d-alert :index="0" />
             <v-data-table
                :loading       = "loading"
                  loading-text = "Loading... Please wait"
                :headers       = "headers"
                dense
                :items = "filterData"
                hide-default-footer
                class = "elevation-1"
            >
                <template v-slot:top>
                    <v-card-title>
                        <v-btn color="green darken-3" dark @click.native="addItem()" class="mb-2" small rounded><v-icon small left>add</v-icon>New</v-btn>
                        <v-spacer></v-spacer>  
                        <v-layout row wrap  >
                            <v-flex pa-1 :key="1">
                                <v-text-field
                                v-model     = "search.keywords"
                                append-icon = "search"
                                label       = "Search"
                                single-line
                                hide-details
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-title>  
                </template>
                <template v-slot:item.name="{item}">
                    <v-chip :color="item.isShow ? 'transparent' : 'red'">
                        {{item.name}}
                    </v-chip>
                </template>
                 <template v-slot:item.created="{ item }">
                    <div>{{item.createdAt | formatDateTime}}</div>
                </template>
                <template v-slot:item.updated="{ item }">
                    <div>{{item.updatedAt | formatDateTime}}</div>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon small class="mx-0" @click="editItem(item)">
                                <v-icon color="teal">edit</v-icon>
                            </v-btn>
                        </template>
                        <span>Edit</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon small class="mx-0" @click="removeItem(item)">
                                <v-icon color="red accent-4">delete</v-icon>
                            </v-btn>
                        </template>
                        <span>Remove</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card-text>          
        <d-confirm ref="confirm"/>
    </div>
</template>

<script>
import {Alert, Confirm} from '@/components'
import {mapState} from 'vuex'
export default {
    data() {
        return {
            search: {
                keywords: ''
            },
            headers: [
                {
                    text : 'Id',
                    align: 'left',
                    value: 'id'
                },
                { text: 'Name', value: 'name', sortable: true },
                { text: 'Priority', value: 'priority', sortable: true },
                { text: 'Created at', value: 'created', sortable: false },
                { text: 'Updated at', value: 'updated', sortable: false },
                { text: 'Actions', value: 'action', sortable: false, align: 'center' },
			],
        }
    },
    components: {
        'd-alert'  : Alert,
        'd-confirm': Confirm
    },
    methods: {       
        //ACTION BUTTON ADD
        addItem() {
            this.$store.commit('OPEN_CATALOGUE_DIALOG')
        }, 
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editCatalogue', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.name+' catalogue').then(result => {
                if(result) {
                    const data = []
                    const url  = `/Catalogue/${item.id}/Remove`
                    this.axios.post(url, data, {withCredentials: true}).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeCatalogue', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.name+' catalogue has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
                        }
                    })
                }
            })
        },
        // FIND BY TEXT
        filterByText(list, value) {
            const keywords = value.toLowerCase()
            if(keywords === '') {
                return list
            }
            return list.filter((item) => {
                return item.name.toLowerCase().includes(keywords) || item._name.toLowerCase().includes(keywords) || item.id === parseInt(keywords)
            })
        }
    },
    computed: {
        ...mapState({
            loading   : state => state.catalogue.loading,
            catalogues: state => [...state.catalogue.catalogues],
        }),
        filterData() {
            if(this.catalogues.length > 0) {
                return this.filterByText(this.catalogues, this.search.keywords)
            }
        }
    },
}
</script>