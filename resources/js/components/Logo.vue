<template>
	<v-card :ripple="true" color="transparent" flat to="/"  max-height="48">
		<v-card-actions class="text-xs-center pa-0">
			<v-avatar size="36" color="white">
				<v-img src="/img/logo48x48.png" alt="dofuu-logo" style="border-radius: 8px" />
			</v-avatar>
			<div class="pl-1 headline font-weight-bold" v-if="$vuetify.breakpoint.smAndUp">Dofuu.com</div>
		</v-card-actions>
	</v-card>
</template>