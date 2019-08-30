<template>
    <div>
        <v-card>
            <v-card-title primary-title>
                <div class="headline">{{title}}</div>
            </v-card-title>
            <d-breadcrumbs class="body-2" :items="items" />
            <d-content/>
        </v-card>
    </div>
</template>

<script>
import {Breadcrumbs} from '@/components'
import {Content} from './components'
export default {
    data(){
        return {
            title: 'List of Customers',
            items: [
                {
                    text    : 'Dashboard',
                    disabled: false,
                    name    : 'Dashboard'
                },
                {
                    text    : 'List of Customers',
                    disabled: true,
                    name    : 'Customers'
                }
            ]    
        }
    },
    components: {
        'd-breadcrumbs': Breadcrumbs,
        'd-content'    : Content
    },
    mounted() {
        this.fetch()
    },
    beforeDestroy() {
        this.$store.dispatch('clearUser')
        this.$store.dispatch('offAlert')
    },
    methods: {
        fetch() {
            const query = {...this.search, page: 1, key: this.$route.name}
            this.$store.dispatch('fetchUser', query)
        }
    },
}
</script>