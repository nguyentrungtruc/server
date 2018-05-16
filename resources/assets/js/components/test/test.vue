<template>
	<div>
		
	</div>
</template>

<script>
	import {apiDomain, getHeader} from '@/config/config.js'
	import axios from 'axios'
	export default {
		data(){
			return {

			}
		},
		created() {
			axios.get(apiDomain+'/api/session', {headers: getHeader(), withCredentials:true}).then(response => {
				console.log(response)
			})
		}
	}
</script>

<style>
	
</style>