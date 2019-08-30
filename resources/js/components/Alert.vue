<template>
    <v-alert
        :color = "alert.type"
        outlined
        :value       = "true"
        :type        = "alert.type"
          transition = "scale-transition"
          v-show     = "alert.index === index && $route.name === alert.routeName"
    >
      {{alert.message}}
    </v-alert>
</template>

<script>
import {mapState} from 'vuex'
export default {
    props   : {index: Number},
    computed: {
        ...mapState({
            alert: state => state.alert
        })
    }
}
</script>