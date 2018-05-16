<template>
	<v-dialog :value="dialog" persistent max-width="600">
		<v-card>
			<v-card-title class="headline">{{title}}</v-card-title>
			<v-card-text>
				<v-layout row wrap v-for="(item, index) in activities" :key="item.id"> 
					<v-flex xs12 sm4 md4>
						<v-checkbox hide-details :label="item.daysOfWeek" v-model="items[index].id" :value="item.id" class="pt-3"></v-checkbox>
					</v-flex>
					<v-flex xs8 v-for="(time, i) in items[index].times" :key="i">
						<v-layout row wrap>
							<v-flex xs6>
								<v-dialog  ref="from" persistent v-model="time.fromModal" lazy full-width width="290px" :return-value.sync="time.from">
									<v-text-field
									slot="activator"
									hide-details
									label="Start time"
									single-line
									v-model="time.from"
									prepend-icon="access_time"
									readonly
									></v-text-field>
									<v-time-picker v-model="time.from" actions format="24hr">
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="time.fromModal = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="saveTime('from', time.from, index)">OK</v-btn>
									</v-time-picker>
								</v-dialog>
							</v-flex>
							<v-flex xs6>
								<v-dialog ref="to" persistent v-model="time.toModal" lazy full-width width="290px" :return-value.sync="time.to">
									<v-text-field
									hide-details
									slot="activator"
									label="End time"
									v-model="time.to"
									prepend-icon="access_time"
									readonly></v-text-field>
									<v-time-picker v-model="time.to" actions format="24hr">
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="time.toModal = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="saveTime('to', time.to, index)">OK</v-btn>
									</v-time-picker>
								</v-dialog>
							</v-flex>
						</v-layout>
					</v-flex>
				</v-layout>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="green darken-1" flat @click.native="$store.commit('DIALOG_CLOSE_ACTIVITY')">Cancel</v-btn>
				<v-btn color="green darken-1" flat @click.native="save">Save</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import {getHeader} from '@/config/config'
import {mapState} from 'vuex'
export default {
	props:['store'],
	data: function() {
		return {
			title: 'Update active times',
			items: [],
		}
	},
	methods: {
		saveTime(ref, time, index) {
			this.$refs[ref][index].save(time)
		},
		save: async function() {
			var data = []
			this.items.forEach(item => {
				if(item.id != null) {
					var timeTemp = []
					item.times.forEach(time => {
						if(time.from != null && time.to != null) {						
							timeTemp.push({from: time.from, to: time.to})
						}
					})
					data.push({activity_id: item.id, times: timeTemp})
				}
			})
			if(data.length>0) {
				var payload = {data: data}
				axios.post('/api/DofuuActivityTime/'+this.store.id, payload, {headers: getHeader()}).then(response => {
					console.log(response.data.message)
					if(response.status === 200) {
						this.$store.commit('SHOW_STORE', response.data)
						this.$store.commit('ALERT_ACTIVITY', {show: true, type: 'success', message: response.data.message})
						this.$store.commit('DIALOG_CLOSE_ACTIVITY')
					}
				}).catch(error => {

				})
			}
		}
	},
	computed: {
		//Store District
		...mapState({
			dialog: state => state.activityStore.dialog,
			activities: state => state.activityStore.activities
		}),
	},
	watch: {
		'activities': function(val) {
			var array = new Array()
			const activities = this.store.activities
			if(activities.length > 0) {
				val.forEach(item => {
					array.push({id: item.id, times:[{ from: null, to: null, fromModal: false, toModal: false}]})
				})
				array.map(item => {
					activities.find(item1 => {
						if(item.id == item1.id) {
							item.times = JSON.parse(JSON.stringify(item1.times))
							item.times.map(time => {
								Object.assign(time, {fromModal: false, toModal: false})
							})
						}
					})
				})
				array.map(item => {
					item.times.map(time => {
						if(time.from == null || time.to == null) {
							item = Object.assign(item, {id: null, times:[{ from: "8:00", to: "22:00", fromModal: false, toModal: false}]})
						}
					})
				})
				this.items = JSON.parse(JSON.stringify(array))
			} else {
				val.forEach(item => {
					array.push({id: null, times:[{ from: "8:00", to: "22:00", fromModal: false, toModal: false}]})
				})
				this.items = JSON.parse(JSON.stringify(array))
			}
		},
		'dialog': function(val) {
			if(val) {
				this.$store.dispatch('fetchActivity')
			}
		}
	}
}
</script>

<style scoped>
.google-map {
	width: 800px;
	height: 600px;
	margin: 0 auto;
	background: gray;
}
</style>