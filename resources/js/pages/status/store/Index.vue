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
export default {
    data(){
        return {
            title: 'List of Stores Status',
            items: [
                {
                    text    : 'Dashboard',
                    disabled: false,
                    name    : 'Dashboard'
                },
                {
                    text    : 'List of Store Status',
                    disabled: true,
                    name    : 'StoreStatus'
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
        this.$store.dispatch('clearStoreStatus')
        this.$store.dispatch('offAlert')
    },
    methods: {
        async fetch() {
            await this.$store.dispatch('fetchStoreStatus')
        }
    }
}
</script>