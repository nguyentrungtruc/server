<template>
    <v-app id="inspire" dark>
        <d-credential/> 
    </v-app>
</template>

<script>
import Main from '@/components/content/Main'
import Credential from '@/pages/credentials/Index'
import {mapState} from 'vuex'
export default {
    components: {
        'd-credential': Credential
    },
    created() {

    },
    computed: {
        
    }
}
</script>