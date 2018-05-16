<template>
	<v-app id="inspire">
		<v-content>
			<v-container fluid fill-height color="grey">
				<v-layout justify-center >
					<v-flex xs12 sm8 md4>
						<v-card class="elevation-12">
							<v-progress-linear :indeterminate="true" v-if="loading"></v-progress-linear>
							<v-toolbar class="elevation-1" color="white">
								<v-toolbar-title>Đăng nhập</v-toolbar-title>
								<v-spacer></v-spacer>
							</v-toolbar>
							<v-card-text>
								<v-form autocomplete="off">
									<v-text-field prepend-icon="email" 
									v-model.trim="email"
									name="email" 
									label="Email" 
									type="text" 
									:error-messages="errors.collect('email')" 
									v-validate="'required|email'"
									></v-text-field>

									<v-text-field prepend-icon="lock" 
									v-model.trim="password"
									name="password" 
									label="Mật khẩu" 
									id="password" 
									type="password"
									:error-messages="errors.collect('password')"
									v-validate="'required'"
									></v-text-field>
								</v-form>
							</v-card-text>
							<v-card-actions>
								<v-btn block color="primary" :loading="loading" :disabled="loading" v-on:click.prevent="login()">Đăng nhập</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
import {Validator} from 'vee-validate'
import Vietnamese from 'vee-validate/dist/locale/vi'
import {loginUrl} from '@/config/config'
import {clientId, clientSecret} from '@/env.js'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			locale: 'vi',
			drawer: null,
			email: null,
			password:null,
			loading: false
		}
	},
	props: {
		source: String
	},
	methods: {
		login: async function() {
			var vm = this
			vm.loading = await !vm.loading
			await vm.$validator.validateAll().then(async (result) => {
				if(result) {
					let data = {email: vm.email, password: vm.password}
					var authUser = {}
					vm.axios.post('/api/DOFUU-AUTH/8420be8df65a43e246a0a6215c5ed977bb099cb8/login', data).then(response => {
						console.log(response)
						if(response.status == 200) {
							console.log(response.data)
							authUser.access_token = response.data.access_token
							authUser.expires_in = response.data.expires_in + Date.now()
							window.localStorage.setItem('authUser', JSON.stringify(authUser))
							vm.$store.dispatch('getAuth').then(response => {
								if(response.status == 200) {
									this.$router.push({name: 'Dashboard'})
								}
							})
						}						
					})
				}
			})
			vm.loading = !vm.loading
		}
	},
	computed: {

	},
	mounted: function() {
		this.$validator.localize(this.locale, {
			messages: Vietnamese.messages
		})
	}
}
</script>
