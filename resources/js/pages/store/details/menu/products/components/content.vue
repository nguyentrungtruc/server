<template>
    <div>
        <v-card-text>
            <d-alert :index="0" />
             <v-data-table
                :loading       = "loading"
                  loading-text = "Loading... Please wait"
                :headers       = "headers"
                dense
                fixed-header
                :items          = "filterData"
                  class         = "elevation-1"
                :items-per-page = "25"
            >
                <template v-slot:top>
                    <v-card-title>
                        <v-btn color="green darken-3" dark @click.native="addItem()" class="mb-2" small rounded><v-icon small left>add</v-icon>New</v-btn>
                        <v-spacer></v-spacer>  
                        <v-layout >
                            <v-flex shrink pa-1 :key="0">
                                <v-select
                                    :items       = "filterCatalogue"
                                      item-text  = "name"
                                      item-value = "id"
                                      v-model    = "search.catalogueId"
                                      label      = "Catalogue"
                                      max-height = "400"
                                >
                                    <template slot="item" slot-scope="data">							
                                        <v-list-item-content>
                                            <v-list-item-title>{{data.item.name}}</v-list-item-title>
                                        </v-list-item-content>
                                    </template>
                                </v-select>
                            </v-flex>
                            <v-flex shrink pa-1 :key="1">
                                <v-select
                                    :items       = "filterTopping"
                                      item-text  = "name"
                                      item-value = "value"
                                      v-model    = "search.topping"
                                      label      = "Topping"
                                />
                            </v-flex>
                            <v-flex pa-1 :key="2">
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
                <template v-slot:item.id="{item}">
                    <div>{{item.catalogueName}} </div>
                    {{ item.id }}
                    <v-card-actions>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <span v-on="on" class="mx-1"><v-icon size="18">shopping_cart</v-icon> {{item.count}}</span>
                            </template>                                
                            <span>Sold: {{item.count}}</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <span v-on="on"><v-icon size="18">stars</v-icon> {{item.priority}}</span>
                            </template>                                
                            <span>Priority: {{item.priority}}</span>
                        </v-tooltip>
                    </v-card-actions>           
                </template>
                <template v-slot:item.avatar="{item}">
                    <v-hover v-slot:default="{ hover }">
                        <v-card  class = "card-radius">
                            <v-img
                            :src           = "image(item.avatar)"
                              aspect-ratio = "2.75"
                              height       = "48px"
                              width        = "48px"
                            >
                                <v-expand-transition>
                                    <div
                                                                                            v-if           = "hover || $vuetify.breakpoint.smAndDown"
                                                                                            class          = "d-flex transition-fast-in-fast-out black lighten-2 v-card--reveal white--text"
                                                                                            style          = " cursor: pointer;"
                                                                                            @click.prevent = "updatingAvatar(item)"
                                                                                          :style           = "cameraOverlay"
                                    >
                                        <v-layout column justify-center align-center>
											<v-flex>
												<v-icon color="white">camera_alt</v-icon>
											</v-flex>
										</v-layout>
                                    </div>
                                </v-expand-transition>
                            </v-img>
                        </v-card>
                    </v-hover>     
                </template>
                <template v-slot:item.name="{item}">
                    <v-chip :color="item.statusId === 3 ? 'red' : item.statusId === 2 ? 'yellow' : 'transparent'">
                        {{item.name}} <span v-if="item.haveTopping" class="font-italic font-weight-bold green--text">(topping)</span>
                    </v-chip>
                    <div v-if="item._name" class="grey--text">{{ item._name}}</div>
                    <div v-if="item.description" class="grey--text">Description: {{ item.description }}</div>
                </template>
                <template v-slot:item.price="{ item }">
                    <div class="caption" v-for="(size, i) in item.sizes" :key="i">
                        {{size.name}}: <strong>{{size.price | formatPrice}}</strong>
                    </div>
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
        <d-image ref="avatar"></d-image>
    </div>
</template>

<script>
import {Alert, Confirm, ImageDialog} from '@/components'
import index from '@/mixins'
import {mapState} from 'vuex'
import {RepositoryFactory} from '@/services/Repository/index'
const ProductRepository = RepositoryFactory.get('products')
export default {
    mixins: [index],
    data() {
        return {
            search: {
                catalogueId: -1,
                topping    : null,
                keywords   : ''
            },
            filterCatalogue: [{name: 'Tất cả', id: -1}],
            filterTopping  : [{name: 'Tất cả', value: null}, {name: 'Có', value: true}, {name: 'Không', value: false}],
            filterSize     : [{name: 'Tất cả', value: null}, {name: 'Có', value: true}, {name: 'Không', value: false}],
            headers        : [
                {
                    text : 'Id',
                    align: 'center',
                    value: 'id'
                },
                { text: 'Avatar', align: 'center', value: 'avatar', sortable: false, width: '48px' },
                { text: 'Name', value: 'name', sortable: true, },
                { text: 'Price', value: 'price', sortable: true, width: '180px'},
                { text: 'Created at', value: 'created', sortable: false },
                { text: 'Updated at', value: 'updated', sortable: false },
                { text: 'Actions', value: 'action', sortable: false, align: 'center' },
			],
        }
    },
    components: {
        'd-alert'  : Alert,
        'd-confirm': Confirm,
        'd-image'  : ImageDialog
    },
    methods: {    
        //ACTION BUTTON ADD
        addItem() {
            this.$store.commit('OPEN_PRODUCT_DIALOG')
        },    
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editProduct', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.name+' product').then(result => {
                if(result) {
                    const data = []
                    const url  = `/Product/${item.id}/Remove`
                    ProductRepository.delete(item.id).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeProduct', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.name+' product has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
                        }
                    })
                }
            })
        },
         filterByCatalogue(list, id) {
            const catalogueId = id

            if(catalogueId === -1) {
                return list
            }

            return list.filter(item => item.catalogueId === catalogueId)

        },
        filterByTopping(list, value) {

            const search = value

            if(search == null) {
                return list
            }

            return list.filter(item => item.haveTopping === search)
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
        },
        //UPDATING AVATAR
        updatingAvatar(item) {
			var   vm   = this
			const size = { width: 350, height: 350 }
            this.$store.dispatch('editAvatar', item)
			this.$refs.avatar.open('Change avatar of product '+item.name, size).then(response => {
				if(response.status) {
					vm.updateAvatar(response.avatar, item)
                } else {
                    this.$store.commit('CLOSE_PRODUCT_DIALOG')
                }
			})
        },
        //REQUEST UPDATE AVATAR TO SERVER
        updateAvatar(avatar, product) {
			var   vm      = this
			const {id}    = product
			const storeId = this.$route.params.storeId
			const data    = { avatar: avatar, storeId: storeId }
			ProductRepository.updateAvatar(id, data).then(response => {
				if(response.status === 200 ){
                    this.$store.dispatch('updateProduct', response.data.product)
                    this.$store.commit('CLOSE_PRODUCT_DIALOG')
				}
			}).finally(() => {

			})
		},
    },
    computed: {
        ...mapState({
            loading   : state => state.product.loading,
            products  : state => [...state.product.products],
            catalogues: state => [...state.catalogue.catalogues]
        }),
        filterData() {
            if(this.products.length > 0) {
                return this.filterByTopping(this.filterByCatalogue(this.filterByText(this.products, this.search.keywords), this.search.catalogueId), this.search.topping)
            }
        }
    },
    watch: {
        catalogues: function(val, oldVal) {
            if(this.filterCatalogue.length == 1) {
				val.forEach(item => {
					this.filterCatalogue.push(item)
				})
			}
        }
    }
}
</script>