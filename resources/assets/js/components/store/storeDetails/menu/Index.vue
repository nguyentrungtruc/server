<template>
	<div>
		<v-layout row>
			<v-flex xs12 sm4>
				<vue-catalogue></vue-catalogue>
			</v-flex>
			<v-flex xs12 sm8>
				<vue-product></vue-product>
			</v-flex>
		</v-layout>
	</div>
</template>

<script>
import Catalogue from './catalogue/Index'
import Product from './product/Index'
export default {
	components: {
		'vue-catalogue' : Catalogue,
		'vue-product': Product
	},
	data: function() {
		return {

		}
	},
	methods: {

	}
}
</script>

<style>

</style>