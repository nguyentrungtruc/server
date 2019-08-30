<template>
    <v-container grid-list-md >
        <v-toolbar
            tabs
        >
            <v-toolbar-title v-if="!loading">{{store.typeName}} | {{store.name}}</v-toolbar-title>

            <v-spacer></v-spacer>
            <template v-slot:extension>
                <v-tabs grow>
                    <v-tab :to="{name: 'About', params: {storeId: $route.params.storeId}}">
                        Giới thiệu
                    </v-tab>
                    <v-tab :to="{name: 'Menu', params: {storeId: $route.params.storeId}}">
                        Thực đơn
                    </v-tab>
                    <v-tab>
                        Nhận xét (comming soon)
                    </v-tab>
                    <v-tab>
                        Không gian (comming soon)	
                    </v-tab>
                </v-tabs>
            </template>            
        </v-toolbar>
        <v-layout row wrap>
            <v-flex xs12>
                <router-view v-if="!loading"/>
            </v-flex>
        </v-layout>        
        <d-loading :loading="loading" />
    </v-container>
</template>

<script>
import {mapState} from 'vuex'
import Loading from '@/components/Loading'
export default {
    components: {
        'd-loading': Loading
    },
    created() {
        this.showStore()
    },
    methods: {
        showStore() {
            this.$store.dispatch('showStore', this.$route.params.storeId)
        }
    },
    computed: {
        ...mapState({
            loading: state => state.store.loading,
            store  : state => state.store.store
        })
    }
}
</script>