<template>
    <div>
        <v-app-bar app>
            <v-btn color="red darken-1" icon @click.stop="changeDrawer()">
				<v-icon>menu</v-icon>
			</v-btn>
            <d-logo/>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" icon @click.stop="logout()">
				<v-icon>
					exit_to_app
				</v-icon>
			</v-btn>
        </v-app-bar>
    </div>
</template>

<script>
import Logo from '../Logo'
export default {
    mixins    : [],
    components: {
        'd-logo': Logo
    },
    data() {
        return {
            drawer: true
        }
    },
    methods: {
        changeDrawer() {
            this.$store.dispatch('changeDrawerState')
        },
        logout() {
            const data = []
            const url  = `/Credentials/Logout`
            this.axios.post(url, data, {withCredentials:true}).then(response => {
                this.$store.dispatch('logout')
                this.$auth.destroyToken()
                this.$router.push({name: 'Home'})
            })
        }
    }
}
</script>