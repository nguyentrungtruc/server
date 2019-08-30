<template>
    <v-dialog
    v-model = "loading"
    hide-overlay
    persistent
    width = "300"
    >
        <v-card
            dark
        >
            <v-card-text>
            Please stand by
            <v-progress-linear
                indeterminate
                color = "red darken-4"
                class = "mb-0"
            ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ['loading']
}
</script>