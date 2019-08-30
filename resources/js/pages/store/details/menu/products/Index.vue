<template>
    <div>
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
import {Content, Dialog} from './components'

export default {
    data() {
        return {
            title: 'List of Product',
        }
    },
    components: {
        'd-content': Content,
        'd-dialog' : Dialog
    },
    mounted() {
        this.fetch(this.$route.params.storeId)
    },
    beforeDestroy() {
        this.$store.dispatch('clearProductStatus')
        this.$store.dispatch('clearCatalogue')        
        this.$store.dispatch('clearSize')
        this.$store.dispatch('clearProduct')
    },
    methods: {
        async fetch(id) {
            this.$store.dispatch('fetchProductStatus')
            this.$store.dispatch('fetchCatalogue', id)
            this.$store.dispatch('fetchSize')
            this.$store.dispatch('fetchProduct', id)
        }
    }
}
</script>