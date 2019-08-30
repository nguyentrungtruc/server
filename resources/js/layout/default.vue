<template>
    <v-app id="inspire">
        <d-toolbar />
        <d-navigation />
        <d-main/> 
    </v-app>
</template>

<script>
import Toolbar from '@/components/header/Toolbar'
import Navigation from '@/components/content/Navigation'
import Main from '@/components/content/Main'
import Credential from '@/pages/credentials/Index'
import {mapState} from 'vuex'
export default {
    components: {
        'd-toolbar'   : Toolbar,
        'd-navigation': Navigation,
        'd-main'      : Main,
        'd-credential': Credential
    },
    created() {

    },
    computed: {
        ...mapState({
            isAuth: state => state.auth.isAuth
        })
    }
}
</script>

<style>
    .card-radius {
	    border-radius: 8px;
    }
</style>