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
                :items          = "users"
                  class         = "elevation-1"
                :items-per-page = "25"
            >
                <template v-slot:top>
                    <v-card-title>
                        <v-btn color="green darken-3" dark @click.native="addItem()" class="mb-2" small rounded><v-icon small left>add</v-icon>New</v-btn>
                        <v-spacer></v-spacer>  
                        <v-layout row wrap>
                            <v-flex shrink pa-1 :key="1">
                                <v-select
                                :items       = "filterActived"
                                  item-text  = "name"
                                  item-value = "value"
                                  v-model    = "search.isActive"
                                  label      = "Actived"
                                  @change    = "find"
                                ></v-select>
                            </v-flex>
                            <v-flex shrink pa-1 :key="2">
                                <v-select
                                :items       = "filterBanned"
                                  item-text  = "name"
                                  item-value = "value"
                                  v-model    = "search.isBan"
                                  label      = "Banned"
                                  @change    = "find"
                                ></v-select>
                            </v-flex>
                            <v-flex pa-1 :key="3">
                                <v-text-field
                                        v-model      = "search.keywords"
                                        append-icon  = "search"
                                        label        = "Search name, email, phone..."
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
                    <div>{{ item.id }}</div>
                            
                    <div>
                        <v-card-actions>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <span  v-on="on">
                                        <v-icon size="18">scatter_plot</v-icon> {{item.points}}
                                    </span>                                    
                                </template>
                                <span>Total points: {{item.points}}</span>
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
                   <v-chip :color="item.isBan ? 'red' : 'transparent'">
                        <v-tooltip top v-if="item.isActive">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" color="green darken-3" size="18">check</v-icon>
                            </template>                            
                            <span>Actived</span>
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
                    <div>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" size="18">email</v-icon>
                            </template>
                            <span>Email</span>
                        </v-tooltip>	   
                        <strong>{{item.email}}</strong>
                    </div>
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
                <template v-slot:item.created="{ item }">
                    <div>{{item.createdAt | formatDateTime}}</div>
                </template>
                <template v-slot:item.updated="{ item }">
                    <div>{{item.updatedAt | formatDateTime}}</div>
                </template>
                <template v-slot:item.action="{item}">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon small class="mx-0" :to="{name: 'UserDetail', params: {userId: item.id}}">
                                <v-icon color="indigo">person</v-icon>
                            </v-btn>
                        </template>
                        <span>Details</span>
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
import {RepositoryFactory} from '@/services/Repository/index'
const UserRepository = RepositoryFactory.get('users')
export default {
    mixins: [index],
    data() {
        return {
            search: {
                keywords: '',
                isActive: null,
                isBan   : null,
            },
            filterActived: [{name: 'Tất cả', value: null}, {name: 'Kích hoạt', value: true}, {name: 'Chưa kích hoạt', value: false}],
            filterBanned : [{name: 'Tất cả', value: null}, {name: 'Cấm', value: true}, {name: 'Không cấm', value: false}],
            headers      : [
                { text : 'Id', sortable: true, align: 'center', value: 'id', width: '150px'},
                { text: 'Avatar', align: 'center', value: 'avatar', sortable: false, width: '48px'},
                { text: 'Name', sortable: true, value: 'name', width: '200px'},
                { text: 'Contact', value: 'contact', sortable: false, width: '350px' },
                { text: 'Created at', value: 'created', sortable: false },
                { text: 'Updated at', value: 'updated', sortable: false },
                { text: 'Actions', value: 'action', align: 'center', sortable: false },
            ],
            pagination: {}
        }
    },
    components: {
        'd-alert'  : Alert,
        'd-confirm': Confirm
    },
    methods: {        
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editUser', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.email+' user').then(result => {
                if(result) {
                    UserRepository.delete(item.id).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeUser', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.email+' user has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
                        }
                    })
                }
            })
        },
        // //ACTION FIND WHEN CHANGE FILTER
        find() {
            const query = {...this.search, page: 1, key: this.$route.name}
            this.$store.dispatch('searchUser', this.search)
            this.$store.dispatch('fetchUser', query)
        },
        changePage(page) {
            var   vm    = this
            const query = {...this.search, page: page, key: this.$route.name}
            if(this.paginate.currentPage != page) {
                vm.$store.dispatch('fetchUser', query)
            }            
        },
    },
    computed: {
        ...mapState({
            loading : state => state.user.loading,
            users   : state => state.user.users,
            paginate: state => state.user.pagination,
        }),
    },
    watch: {
        paginate: function(val, oldVal) {
            if(val) {
                this.pagination = {...this.paginate}
            }
        },
    }
}
</script>
