<template>
    <v-data-table
        :loading = "loading"
        :headers = "headers"
        :items   = "data"
          class  = "elevation-1"
        hide-actions                
        :disable-initial-sort = "true"
    >
        <template v-slot:items="props">
            <slot  v-bind:props="props"></slot>
        </template>        
    </v-data-table>
</template>

<script>
export default {
    props: ['loading', 'headers', 'data']
}
</script>