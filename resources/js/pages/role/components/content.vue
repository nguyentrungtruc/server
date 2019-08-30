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
                        <v-flex pa-1 :key="1">
                            <v-text-field
                            v-model     = "keywords"
                            append-icon = "search"
                            label       = "Search"
                            single-line
                            hide-details
                            ></v-text-field>
                        </v-flex> 
                    </v-card-title>        
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
export default {
    data() {
        return {
            keywords: '',
            headers : [
                {
                    text : 'Role name',
                    align: 'left',
                    value: 'name'
                },
                { text: 'Description', value: 'description', sortable: false },
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
            this.$store.commit('OPEN_ROLE_DIALOG')
        },
        //ACTION BUTTON EDIT
        editItem(item) {
            this.$store.dispatch('editRole', item)
        },
        //ACTION BUTTON REMOVE
        removeItem(item) {
            this.$refs.confirm.open('Remove Item', 'delete '+item.name+' role').then(result => {
                if(result) {
                    const data = []
                    const url  = `/Role/${item.id}/Remove`
                    this.axios.post(url, data, {withCredentials: true}).then(response => {
                        if(response.status === 204) {
                            this.$store.dispatch('removeRole', item)
			                this.$store.dispatch('onAlert', {close: true, index: 0, message: item.name+' role has been deleted.', routeName: this.$route.name, show: true, type: 'success'})
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
                return item.name.toLowerCase().includes(keywords)
            })
        }
    },
    computed: {
        ...mapState({
            loading: state => state.role.loading,
            roles  : state => [...state.role.roles],
        }),
        filterData() {
            if(this.roles.length > 0) {
                return this.filterByText(this.roles, this.keywords)
            }
        }
    },
}
</script>