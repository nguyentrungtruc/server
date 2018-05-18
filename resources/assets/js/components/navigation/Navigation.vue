<template>
	<v-navigation-drawer
	clipped
	fixed
	v-model="drawer"
	app
	>
	<v-list dense v-if="currentUser != null">
		<template v-for="item in items" v-if="item.isEmployee == currentUser.isEmployee || item.isAdmin == currentUser.isAdmin">
			<v-layout row v-if="item.heading" align-center :key="item.heading" >
				<v-flex xs6>
					<v-subheader v-if="item.heading">
						{{ item.heading }}
					</v-subheader>
				</v-flex>
			</v-layout>
			<v-list-group v-else-if="item.children" v-model="item.model" :key="item.text" :prepend-icon="item.icon">
				<v-list-tile slot="activator">
					<v-list-tile-content>
						<v-list-tile-title>
							{{ item.title }}
						</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile
				v-for="(child, i) in item.children"
				:key="i"
				:to="{name: child.action}"
				avatar 
				v-if="child.isEmployee == currentUser.isEmployee || item.isAdmin == currentUser.isAdmin">
				<v-list-tile-avatar>
					<v-icon v-if="child.icon">{{child.icon}}</v-icon>
				</v-list-tile-avatar>
				<v-list-tile-content>
					<v-list-tile-title>
						{{ child.title }}
					</v-list-tile-title>
				</v-list-tile-content>
			</v-list-tile>
		</v-list-group>
		<v-list-tile v-else :to="{name:item.action}" :key="item.text">
			<v-list-tile-action>
				<v-icon>{{ item.icon }}</v-icon>
			</v-list-tile-action>
			<v-list-tile-content>
				<v-list-tile-title>
					{{ item.title }}
				</v-list-tile-title>
			</v-list-tile-content>
		</v-list-tile>
	</template>
</v-list>
</v-navigation-drawer>
</template>

<script>
import {mapState} from 'vuex'
export default {
	props: ['drawer', 'items'],
	data: function() {
		return {

		}
	},
	computed: {
		...mapState({
			currentUser: state => state.authStore.authUser
		})
	},
}
</script>

<style>

</style>