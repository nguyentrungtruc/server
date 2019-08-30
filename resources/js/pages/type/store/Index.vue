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
            title: 'List of Types',
            items: [
                {
                    text    : 'Dashboard',
                    disabled: false,
                    name    : 'Dashboard'
                },
                {
                    text    : 'List of Types',
                    disabled: true,
                    name    : 'Type'
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
        this.$store.dispatch('clearType')
        this.$store.dispatch('offAlert')
    },
    methods: {
        async fetch() {
            await this.$store.dispatch('fetchType')
        }
    }
}
</script>