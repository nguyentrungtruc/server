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
                        <v-btn color="green darken-3" @click.native="addItem()" class="mb-2" small rounded><v-icon small left>add</v-icon>New</v-btn>
                        <v-spacer></v-spacer>  
                        <v-layout row wrap grid-list-md >
                            <v-flex shrink pa-1 :key="0">
                                <v-select
                                :items       = "filterShow"
                                  item-text  = "name"
                                  item-value = "value"
                                  v-model    = "search.isShow"
                                  label      = "Show/Hide"
                                />
                            </v-flex>
                            <v-flex pa-1 :key="1">
                                <v-text-field
                                v-model     = "search.keywords"
                                append-icon = "search"
                                label       = "Search"
                                single-line
                                hide-details
                                />
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
                     <v-btn icon small class="mx-0" @click="editItem(item)">
                        <v-icon color="teal">edit</v-icon>
                    </v-btn>
                    <v-btn icon small class="mx-0" @click="removeItem(item)">
                        <v-icon color="red accent-4">delete</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card-text>          
        <d-confirm ref="confirm"/>
    </div>
</template>

<script>
import {Alert, Confirm} from '@/components'
import {mapState} from 'vuex'
import {RepositoryFactory} from '@/services/Repository/index'
const CityRepository = RepositoryFactory.get('cities')
export default {
    data() {
        return {
            search: {
                keywords: '',
                isShow  : null,
            },
            filterShow: [{name: 'Tất cả', value: null}, {name: 'Hiện', value: true}, {name: 'Ẩn', value: false}],
            headers   : [
                { text : 'City Name', align: 'left', value: 'name'},
                { text: 'Slug', value: 'slug', sortable: false },
                { text: 'Zip Code', value: 'zipCode', sortable: false },
                { text: 'Country Name', value: 'countryName', sortable: false },
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
            this.$store.commit('OPEN_CITY_DIALOG')
        },
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editCity', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.name+' city').then(result => {
                if(result) {
                    CityRepository.delete(item.id).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeCity', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.name+' city has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
                        }
                    })
                }
            })
        },
        // FIND BY BOOLEAN
        filterByShow(list, value) {
			const keywords = value

			if(keywords == null) {
				return list
			}
			return list.filter(item => item.isShow === keywords)
        },
        // FIND BY TEXT
        filterByText(list, value) {
            const keywords = value.toLowerCase()
            if(keywords === '') {
                return list
            }
            return list.filter((item) => {
                return item.name.toLowerCase().includes(keywords) || item.zipCode.toLowerCase().includes(keywords)
            })
        }
    },
    computed: {
        filterData() {
            if(this.cities.length > 0) {
                return this.filterByText(this.filterByShow(this.cities, this.search.isShow), this.search.keywords)
            }
        },
        ...mapState({
            loading: state => state.city.loading,
            cities : state => [...state.city.cities],
        }),
    },
}
</script>