<template>
    <v-sheet>
        <v-container fluid >
            <v-content >
                <v-layout row wrap>
                    <v-flex xs12>
                        <router-view></router-view>
                    </v-flex>
                </v-layout>
            </v-content>
        </v-container>        
    </v-sheet>
</template>

<script>
export default {
    
}
</script>