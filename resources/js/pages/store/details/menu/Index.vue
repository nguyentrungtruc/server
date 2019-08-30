<template>
	<v-card>
		<v-tabs grow>
            <v-tab :to="{name: 'General'}">
                General
            </v-tab>
            <v-tab :to="{name: 'Catalogue'}">
                Catalogue
            </v-tab>
            <v-tab :to="{name: 'Topping'}">
                Topping
            </v-tab>
            <v-tab :to="{name: 'Product'}">
                Product
            </v-tab>
        </v-tabs>
        <router-view></router-view>
	</v-card>
</template>

<script>

</script>