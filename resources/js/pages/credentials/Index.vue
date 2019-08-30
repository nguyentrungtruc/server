<template>
	<v-content>
		<v-container fluid fill-height color="grey">
			<v-layout justify-center >
				<v-flex xs12 sm8 md4>
					<v-card class="elevation-12">
						<v-progress-linear :indeterminate="true" v-if="loading" color="red darken-4" />
						<v-toolbar class="elevation-1" color="red darken-4" dense>
							<v-toolbar-title>Login</v-toolbar-title>
						</v-toolbar>
						<v-card-text>
                            <d-alert :index="0"/>
							<v-form>
								<v-text-field 
								        prepend-icon  = "email"
								        v-model.trim  = "email"
								        name          = "email"
								        label         = "Email"
								        type          = "text"
								      :error-messages = "errors.collect('email')"
								        v-validate    = "'required|email'"
								        color         = "red darken-4"
								></v-text-field>

								<v-text-field prepend-icon="lock" 
								        v-model.trim               = "password"
								        name                       = "password"
								        label                      = "Mật khẩu"
								        id                         = "password"
								        type                       = "password"
								      :error-messages              = "errors.collect('password')"
								        v-validate                 = "'required'"
								        color                      = "red darken-4"
								        @keyup.enter.exact.prevent = "login"
								></v-text-field>
							</v-form>
						</v-card-text>
						<v-card-actions>
							<v-btn block color="red darken-4" :loading="loading" :disabled="loading" v-on:click.prevent="login()">Submit</v-btn>
						</v-card-actions>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</v-content>
</template>

<script>
import Alert from '@/components/Alert'
import Vietnamese from 'vee-validate/dist/locale/vi'
import {Validator} from 'vee-validate'
export default {
    data() {
        return {
            locale  : 'vi',
            email   : null,
            password: null,
            loading : false
        }
    },
    components: {
        'd-alert': Alert
    },
	methods: {
		login: async function() {
			var vm = this
			if(!vm.loading) {
				vm.loading = true
				await vm.$validator.validateAll().then(async (result) => {
					if(result) {
						let data = {email: vm.email, password: vm.password}
					
						const url = `/Credentials/Login`
						vm.axios.post(url, data, {withCredentials: true}).then(response => {
							if(response.status === 200) {
								vm.loginSuccess(response)
							}					
						}).catch((error) => {
							if(error.response.status === 403) {
								vm.loginFail(error)
							}
						}).finally(() => {
							vm.loading = false
						})
					}
				})
			}
			
		},
		loginSuccess(response) {
			var authUser              = {}
			    authUser.access_token = response.data.access_token
			    authUser.expires_in   = response.data.expires_in + Date.now()
                localStorage.setItem('jwt_admin', JSON.stringify(authUser))
                this.$store.dispatch('getAuth').then(response => {
                    if(response.status === 200) {
                        this.$router.push({name: 'Dashboard'})
                    }
                })
        },
        loginFail(error) {
            this.$store.dispatch('onAlert', {close: false, index: 0, message: 'Email or password is wrong!!!', routeName: this.$route.name, show: true, type: 'error'})
        }
	},
	mounted: function() {
		this.$validator.localize(this.locale, {
			messages: Vietnamese.messages
		})
	}
}
</script>