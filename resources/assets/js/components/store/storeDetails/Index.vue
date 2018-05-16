<template>	
	<v-card>
		<v-toolbar color="yellow accent-2 red--text text--accent-3" class="elevation-0" dense>
			<v-tooltip top>
				<v-icon slot="activator" :color="store.verified ? 'green' : 'grey'">verified_user</v-icon>
				<span v-if="store.verified">Verfied Merchant</span>
				<span v-else>Not Verfied Merchant</span>
			</v-tooltip>
			<v-toolbar-title>{{store.name}}</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-toolbar-title>{{store.type_name}}</v-toolbar-title>
		</v-toolbar>
		<v-container grid-list-md text-xs-center>
			<v-layout row wrap>
				<v-flex xs3>
					<v-card flat>
						<v-card-media
						class="white--text"
						height="200px"
						:src="image(store.avatar)"
						></v-card-media>
					</v-card>
				</v-flex>
				<v-flex xs9>
					<v-card flat>
						<v-layout row wrap>
							<v-flex xs3 class="text-lg-right">
								Type:
							</v-flex>
							<v-flex xs9>
								{{store.type_name}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								Store name:
							</v-flex>
							<v-flex xs9>
								{{store.name}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								City:
							</v-flex>
							<v-flex xs9>
								{{store.city_name}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								District:
							</v-flex>
							<v-flex xs9>
								{{store.district_name}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								Address:
							</v-flex>
							<v-flex xs9>
								{{store.address}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								Phone:
							</v-flex>
							<v-flex xs9>
								{{store.phone}}
							</v-flex>
							<v-flex xs3  class="text-lg-right">
								Prepare time:
							</v-flex>
							<v-flex xs9>
								{{store.preparetime}}
							</v-flex>
						</v-layout>
					</v-card>					
				</v-flex>
				<v-divider></v-divider>
			</v-flex>

			<v-flex>
				<v-card flat>
					<v-subheader>
						Active times
						<v-tooltip top>
							<v-btn slot="activator" flat icon color="green" @click.stop.prevent="$store.commit('DIALOG_ACTIVITY')">
								<v-icon>edit</v-icon>
							</v-btn>
							<span>Edit Activities</span>
						</v-tooltip>						
					</v-subheader>
					<v-layout row wrap>
						<v-flex  v-for="(item, index) in store.activities" :key="index">
							<v-card>
								<div>									
									{{item.daysofweek}}
								</div>
								<v-flex v-for="(time, i) in item.times" :key="i">
									<div>{{time.from}} - {{time.to}}</div>
								</v-flex>				
							</v-card>							
						</v-flex>							
					</v-layout>					
				</v-card>
			</v-flex>
		</v-layout>
	</v-container>
	<v-container grid-list-md >
		<v-tabs icons-and-text centered fixed-tabs color="grey lighten-2">
			<v-tabs-slider color="red accent-3"></v-tabs-slider>
			<v-tab :to="{name: 'Menu'}">
				Menu
				<v-icon>menu</v-icon>
			</v-tab>
		</v-tab>
	</v-tabs>
	<router-view></router-view>
</v-container>
<vue-activity-dialog :store.sync="store"></vue-activity-dialog>
<v-snackbar :timeout="3000" :color="alert.type" bottom multi-line :value="alert.show">
	{{alert.message}}
	<v-btn flat @click.native="snackbar = false">Close</v-btn>
</v-snackbar>
</v-card>
</template>

<script>
import ActivityDialog from './Activity/activityDialog'
import axios from 'axios'
import {getHeader} from '@/config/config'
import image from '@/mixins/imageUrl'
import {mapState} from 'vuex'
export default {
	mixins: [image],
	components: {
		'vue-activity-dialog': ActivityDialog
	},
	data: function() {
		return {
			title: 'Store Details',
			snackbar: false
		}
	},
	computed: {
		...mapState({
			store: state =>  state.storeStore.store,
			alert: state => state.activityStore.alert
		})
	},
	created() {
		this.$store.dispatch('showStore', this.$route.params.sid)
	}
}

</script>

<style scoped>

</style>