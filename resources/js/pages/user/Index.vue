<template>
	<v-container grid-list-md >
        <v-toolbar
            tabs
        >
            <v-spacer></v-spacer>
            <template v-slot:extension>
                <v-tabs grow>
                    <v-tab :to="{name: 'Customer'}">
                        Customer
                    </v-tab>
                    <v-tab :to="{name: 'Shipper'}">
                        Shipper
                    </v-tab>
                    <v-tab :to="{name: 'Admin'}">
                        Admin
                    </v-tab>
                    <v-tab :to="{name: 'Employee'}">
                        Employee
                    </v-tab>
                    <v-tab :to="{name: 'Partner'}">
                        Partner
                    </v-tab>
                </v-tabs>
            </template>            
        </v-toolbar>
        <v-layout row wrap>
            <v-flex xs12>
                <router-view/>
            </v-flex>
           
        </v-layout>   
        <div>
            <d-dialog />
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-btn v-on="on" small fixed bottom right fab color="green darken-2" @click.native="addItem">
                        <v-icon>add</v-icon>
                    </v-btn>
                </template>                
                <span>Thêm mới</span>
            </v-tooltip>
        </div>     
    </v-container>
</template>
<script>
import {Breadcrumbs} from '@/components'
import {Dialog} from './components'
import {mapState} from 'vuex'
export default {
    data(){
        return {
            title: 'List of User',
            items: [
                {
                    text    : 'Dashboard',
                    disabled: false,
                    name    : 'Dashboard'
                },
                {
                    text    : 'List of Stores',
                    disabled: true,
                    name    : 'Store'
                }
            ]    
        }
    },
    components: {
        'd-breadcrumbs': Breadcrumbs,
        'd-dialog'     : Dialog
    },
    mounted() {
        this.fetch()
    },
    beforeDestroy() {
        this.$store.dispatch('clearRole')
        this.$store.dispatch('offAlert')
    },
    methods: {
        //ACTION BUTTON ADD
        addItem() {
            this.$store.commit('OPEN_USER_DIALOG')
        },
        async fetch() {
            this.$store.dispatch('fetchRole')
        }
    },
    computed: {
        ...mapState({
            // search: state => state.store.search,
            // loading: state => state.user.loading
        }),
    },
}
</script>