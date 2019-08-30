<template>
	<v-dialog :value="dialog" persistent max-width="600">
		<v-card>
			<v-card-title class="headline">{{title}}</v-card-title>
			<v-card-text>
				<v-row align="center" v-for="(item, index) in activities" :key="item.id" >
					<v-checkbox hide-details :label="item.daysOfWeek" v-model="items[index].id" :value="item.id" class="shrink"></v-checkbox>
					<v-row align="center" v-for="(time, i) in items[index].times" :key="i">
						<v-dialog  ref="from" persistent v-model="time.fromModal" lazy full-width width="290px" :return-value.sync="time.from">
							<template v-slot:activator="{ on }">
								<v-text-field
									hide-details
									slot         = "activator"
									label        = "Bắt đầu"
									v-model      = "time.from"
									prepend-icon = "access_time"
									readonly
									v-on  = "on"
									class = "shrink mx-2"
								/>
							</template>
							
							<v-time-picker v-model="time.from" actions format="24hr">
								<v-spacer></v-spacer>
								<v-btn small rounded text color="primary" @click="time.fromModal = false">Cancel</v-btn>
								<v-btn small rounded text color="primary" @click="saveTime('from', time.from, index)">OK</v-btn>
							</v-time-picker>
						</v-dialog>

						<v-dialog ref="to" persistent v-model="time.toModal" lazy full-width width="290px" :return-value.sync="time.to">
							<template v-slot:activator="{ on }">
								<v-text-field
									hide-details
									slot         = "activator"
									label        = "Kết thúc"
									v-model      = "time.to"
									prepend-icon = "access_time"
									readonly 
									v-on  = "on"
									class = "shrink mx-2"
								/>
							</template>
							
							<v-time-picker v-model="time.to" actions format="24hr">
								<v-spacer></v-spacer>
								<v-btn small rounded text color="primary" @click="time.toModal = false">Cancel</v-btn>
								<v-btn small rounded text color="primary" @click="saveTime('to', time.to, index)">OK</v-btn>
							</v-time-picker>
						</v-dialog>
					</v-row>
				</v-row>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn small rounded text color="red accent-3" @click.native="close">Cancel</v-btn>
				<v-btn small rounded text color="primary" @click.native="save">Update</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	import {mapState} from 'vuex'
	import axios from 'axios'
	export default {
		data : function() {
			return {
				title: 'Update activity times',
				items: [],
			}
		},
		methods: {
			close() {
				this.$store.commit('CLOSE_ACTIVITY_DIALOG')
			},
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
					const url     = `Store/${this.store.id}/Activity/Update`
					var   payload = {data: data}
					axios.post(url, payload).then(response => {
						if(response.status === 200) {
							this.$store.commit('SHOW_STORE', response.data)
							this.close()
						}
					}).catch(error => {

					})
				}
			},
			//ACTION ALERT WHEN SUCCESS
			success(message) {
				this.$store.dispatch('onAlert', {close: true, index: 0, message: message, routeName: this.$route.name, show: true, type: 'success'})
			},
			//ACTION ALERT WHEN FAIL
			fail(message) {
				this.$store.dispatch('onAlert', {close: false, index: 1, message: message, routeName: this.$route.name, show: true, type: 'error'})
			}
		},
		computed: {
		//Store Activity
		...mapState({
			dialog    : state    => state.activity.dialog,
			activities: state => state.activity.activities,
			store     : state => state.store.store
		}),
	},
	watch: {
		'activities': function(val) {
			var   array      = new Array()
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
	}
}
</script>
