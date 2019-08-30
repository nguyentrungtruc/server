<template>
    <div>
        <v-card-text>
            <d-alert :index="0" />
            <div class="text-xs-center pt-2" v-if="pagination.lastPage>1">
                <v-pagination circle v-model="pagination.currentPage" :length="pagination.lastPage" @input="changePage(pagination.currentPage)"></v-pagination>
            </div>
            <v-data-table
                :loading       = "loading"
                  loading-text = "Loading... Please wait"
                :headers       = "headers"
                dense
                :items = "stores"
                hide-default-footer
                class = "elevation-1"
            >
                <template v-slot:top>
                    <v-card-title>
                        <v-btn color="green darken-3" dark @click.native="addItem()" class="mb-2" small rounded><v-icon small left>add</v-icon>New</v-btn>
                        <v-spacer></v-spacer>  
                        <v-layout row wrap  >
                            <v-flex shrink pa-1 :key="0">
                                <v-select
                                    :items       = "cities"
                                      item-text  = "name"
                                      item-value = "id"
                                      v-model    = "search.cityId"
                                      label      = "City"
                                      max-height = "400"
                                      @change    = "find"
                                >
                                <template slot="item" slot-scope="data">							
                                    <v-list-item-content>
                                        <v-list-item-title>{{data.item.name}}</v-list-item-title>
                                    </v-list-item-content>
                                </template></v-select>
                            </v-flex>
                            <v-flex shrink pa-1 :key="1">
                                <v-select
                                :items       = "filterTypes"
                                  item-text  = "name"
                                  item-value = "id"
                                  v-model    = "search.typeId"
                                  label      = "Type"
                                  max-height = "400"
                                  @change    = "find"
                                >
                                    <template slot="item" slot-scope="data">							
                                        <v-list-item-content>
                                            <v-list-item-title>{{data.item.name}}</v-list-item-title>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            {{data.item.storeCount}}
                                        </v-list-item-action>
                                    </template>
                                </v-select>
                            </v-flex>
                            <v-flex shrink pa-1 :key="2">
                                <v-select
                                :items       = "filterShow"
                                  item-text  = "name"
                                  item-value = "value"
                                  v-model    = "search.isShow"
                                  label      = "Show/Hide"
                                  @change    = "find"
                                ></v-select>
                            </v-flex>
                            <v-flex pa-1 :key="3">
                                <v-text-field
                                    v-model      = "search.keywords"
                                    append-icon  = "search"
                                    label        = "Search"
                                    @keyup.enter = "find"
                                    single-line
                                    hide-details
                                    @click:append = "find"
                                />
                            </v-flex>
                        </v-layout>
                    </v-card-title>    
                </template>
                <template v-slot:item.id="{item}">
                    <div>{{item.typeName}} </div>
                            {{ item.id }}
                    <div>
                        <v-card-actions>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <span  v-on="on">
                                        <v-icon size="18">visibility</v-icon> {{item.views}}
                                    </span>                                    
                                </template>
                                <span>Total views: {{item.views}}</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <span  v-on="on">                                        
                                        <v-icon size="18">favorite</v-icon> {{item.likes}}
                                    </span>
                                </template>
                                <span>Total likes: {{item.likes}}</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <span  v-on="on">                                        
                                        <v-icon size="18">stars</v-icon> {{item.priority}}
                                    </span>
                                </template>
                                <span>Priority: {{item.priority}}</span>
                            </v-tooltip>
                        </v-card-actions>
                    </div>           
                </template>
                <template v-slot:item.avatar="{item}">
                     <v-card  class = "card-radius">
                        <v-img
                        :src           = "image(item.avatar)"
                          aspect-ratio = "2.75"
                          height       = "48px"
                          width        = "48px"
                        />
                    </v-card>
                </template>
                <template v-slot:item.name="{item}">
                   
                    <v-chip :color="item.isShow ? 'transparent' : 'red'">
                        <v-tooltip top v-if="item.isVerify">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" color="green darken-3" size="18">verified_user</v-icon>
                            </template>                            
                            <span>Verified partner</span>
                        </v-tooltip>
                        {{item.name}}
                    </v-chip>
                </template>
                <template v-slot:item.contact="{item}">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on" size="18">phone</v-icon>
                        </template>                            
                        <span>Phone number</span>
                    </v-tooltip>	                             
                    <strong>{{item.phone | formatPhone}}</strong> 
                    <div>{{item.cityName}} - {{item.districtName}}</div>
                    <div>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" size="18">place</v-icon>
                            </template>
                            <span>Address</span>
                        </v-tooltip>	   
                        <strong>{{item.address}}</strong>
                    </div>
                </template>
                <template v-slot:item.discount="{item}">
                    {{item.discount}}%
                </template>
                <template v-slot:item.action="{item}">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon small class="mx-0" :to="{name: 'About', params: {storeId: item.id}}">
                                <v-icon color="indigo">store</v-icon>
                            </v-btn>
                        </template>
                        <span>Details</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon small class="mx-0">
                                <v-icon color="black">link</v-icon>
                            </v-btn>
                        </template>
                        <span>Link with user</span>
                    </v-tooltip>
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
            <div class="text-xs-center pt-2" v-if="pagination.lastPage>1">
                <v-pagination 
                                      v-model = "pagination.currentPage"
                                    :length   = "pagination.lastPage"
                                      @input  = "changePage(pagination.currentPage)"
                circle
                />
            </div>
        </v-card-text>          
        <d-confirm ref="confirm"/>
    </div>
</template>

<script>
import {Alert, Confirm} from '@/components'
import index from '@/mixins'
import {mapState} from 'vuex'
export default {
    mixins: [index],
    data() {
        return {
            search: {
                keywords: '',
                typeId  : -1,
                cityId  : 10001,
                isShow  : null,
            },
            filterTypes: [{name: 'Tất cả', id: -1}],
            filterShow : [{name: 'Tất cả', value: null}, {name: 'Hiện', value: true}, {name: 'Ẩn', value: false}],
            headers    : [
                { text : 'Id', sortable: true, align: 'center', value: 'id', width: '150px'},
                { text: 'Avatar', align: 'center', value: 'avatar', sortable: false, width: '48px'},
                { text: 'Store', sortable: true, value: 'name', width: '200px'},
                { text: 'Contact', value: 'contact', sortable: false, width: '350px' },
                { text: 'Discount', value: 'discount', align: 'center', sortable: true, width: '100px' },
                { text: 'Status', value: 'statusName', sortable: false, width: '100px' },
                { text: 'Actions', value: 'action', align: 'center', sortable: false },
            ],
            pagination: {

            }
        }
    },
    components: {
        'd-alert'  : Alert,
        'd-confirm': Confirm
    },
    methods: {        
         //ACTION BUTTON ADD
        addItem() {
            this.$store.commit('OPEN_STORE_DIALOG')
        },
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editStore', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.name+' store').then(result => {
                if(result) {
                    const data = []
                    const url  = `/Store/${item.id}/Remove`
                    this.axios.post(url, data, {withCredentials: true}).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeStore', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.name+' store has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
                        }
                    })
                }
            })
        },
        //ACTION FIND WHEN CHANGE FILTER
        find() {
            const query = {...this.search, page: 1}
            this.$store.dispatch('searchStore', this.search)
            this.$store.dispatch('fetchStore', query)
        },
        changePage(page) {
            var   vm    = this
            const query = {...this.search, page: page}
            if(this.paginate.currentPage != page) {
                vm.$store.dispatch('fetchStore', query)
            }            
        },
    },
    computed: {
        ...mapState({
            cities  : state => [...state.city.cities],
            loading : state => state.store.loading,
            stores  : state => state.store.stores,
            types   : state  => [...state.type.types],
            paginate: state => state.store.pagination
        }),
    },
    watch: {
        paginate: function(val, oldVal) {
            if(val) {
                this.pagination = {...this.paginate}
            }
        },
        types: function(val, oldVal) {
            var count = 0
			if(this.filterTypes.length==1) {
				val.forEach(type => {
					this.filterTypes.push(type)
				})
			}
        }
    }
}
</script>
