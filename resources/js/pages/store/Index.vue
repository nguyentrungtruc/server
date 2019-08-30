<template>
    <div>
        <d-breadcrumbs :items="items" />
        <v-card>
            <v-card-title primary-title>
                <div class="headline">{{title}}</div>
            </v-card-title>
            <d-content/>
        </v-card>
        <d-dialog/>
    </div>
</template>

<script>
import {Breadcrumbs} from '@/components'
import {Content, Dialog} from './components'
import {mapState} from 'vuex'
export default {
    data(){
        return {
            title: 'List of Stores',
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
        'd-content'    : Content,
        'd-dialog'     : Dialog
    },
    mounted() {
        this.fetch()
    },
    beforeDestroy() {
        this.$store.dispatch('clearCity')
        this.$store.dispatch('clearType')
        this.$store.dispatch('clearStoreStatus')
        this.$store.dispatch('clearStore')
        this.$store.dispatch('offAlert')
    },
    methods: {
        async fetch() {
            const query = {...this.search, page: 1}
            await this.$store.dispatch('fetchCity')
            await this.$store.dispatch('fetchType')
            await this.$store.dispatch('fetchStoreStatus')
            this.$store.dispatch('fetchStore', query)
        }
    },
    computed: {
        ...mapState({
            search: state => state.store.search
        }),
    },
}
</script>