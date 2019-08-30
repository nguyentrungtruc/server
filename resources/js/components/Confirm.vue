<template>
	<v-dialog v-model="dialog" persistent max-width="400"  @keydown.esc="cancel">
		<v-card>
			<v-toolbar color="transparent" dense flat>
				<v-toolbar-title>{{ title }}</v-toolbar-title>
				<v-spacer/>
			</v-toolbar>
			<v-divider/>
			<v-card-text v-show="!!message">Are you sure you want to {{message}} ?</v-card-text>
			<v-divider/>
			<v-card-actions>
				<v-spacer/>
				<v-btn class="ma-2" @click.native="cancel" small rounded>Cancel</v-btn>
				<v-btn class="ma-2" color="red" @click.native="agree" small rounded>Ok</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	export default {
		data() {
			return {
				dialog : false,
				title  : null,
				message: null,
				resolve: null,
				reject : null,
			}
		},
		methods: {
			open(title, message) {
				this.dialog  = true
				this.title   = title
				this.message = message
				return new Promise((resolve, reject) => {
					this.resolve = resolve
					this.reject  = reject
				})

			},
			agree() {
				this.resolve(true)
				this.dialog = false
			},
			cancel() {
				this.resolve(false)
				this.dialog = false
			}
		}		
	}
</script>