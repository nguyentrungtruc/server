<template>
    <div>
        <v-card-text>
            <d-alert :index="0" />
            
        </v-card-text>          
        <d-confirm ref="confirm"/>
    </div>
</template>

<script>
import {Alert, Confirm} from '@/components'
import index from '@/mixins'
import {mapState} from 'vuex'
export default {
    mixins: [index],
    data() {
        return {
            
        }
    },
    components: {
        'd-alert'  : Alert,
        'd-confirm': Confirm
    },
    methods: {        
    },
    computed: {
        ...mapState({
            
        }),
    },
    watch: {
       
    }
}
</script>
