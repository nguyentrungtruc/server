<template>
    <div>
        <v-breadcrumbs :items="items">
            <template v-slot:item="props">
                <router-link :to="{name: props.item.name}" :class="[props.item.disabled && 'disabled']">{{ props.item.text | upperCase }}</router-link>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    props: {items: Array},
}
</script>

<style scoped>
    .disabled {
        color         : grey;
        pointer-events: none;
    }
</style>