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
            title: 'List of Toppings',
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
        this.$store.dispatch('clearTopping')
    },
    methods: {
        async fetch(id) {
            await this.$store.dispatch('fetchTopping', id)
        }
    }
}
</script>