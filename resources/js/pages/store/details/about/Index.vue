<template>
    <v-layout row wrap>
        <v-flex d-flex shrink xs3>
            <v-card>
                
                <v-card  class = "card-radius">
                    <v-img
                    :src           = "image(store.avatar)"
                      aspect-ratio = "2.75"
                      height       = "200px"
                      width        = "100%"
                    />
                </v-card>
                
                <v-list three-line dense>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon color="indigo">phone</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title><h4>Mobile</h4></v-list-item-title>
                            <v-list-item-subtitle><h4>{{store.phone | formatPhone}}</h4></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item >
                        <v-list-item-action>
                            <v-icon color="indigo">place</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title><h4>Address</h4></v-list-item-title>
                            <v-list-item-subtitle><h4>{{store.address}}</h4></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-flex>

        <v-flex d-flex xs12 sm6 md6>
            <GmapMap 
            :center       = "{lat:store.lat, lng:store.lng}"
            :zoom         = "17"
              map-type-id = "terrain"
              style       = "width: 100%; height:100%"
            :options      = "mapOptions"
            >
                <GmapMarker	:position="{lat:store.lat, lng:store.lng}"
                :clickable="true" :icon="typeIcon(store.typeName)">
                    <gmap-info-window :position="{lat:store.lat, lng:store.lng}" :opened="true">
                        <v-card>
                            <v-card-title primary-title>
                                <div>
                                    <a class="headline font-weight-bold" :href="`https://www.dofuu.com/${store.citySlug}/${store.slug}`" target="_blank">{{store.name}}</a>
                                    <div class="grey--text">{{store.typeName}}</div>
                                </div>
                            </v-card-title>
                            <v-list dense>
                                <v-list-item>
                                    <v-list-item-action>
                                        <v-icon color="indigo">phone</v-icon>
                                    </v-list-item-action>
                                    <v-list-item-content>
                                        <v-list-item-title><h4>{{store.phone | formatPhone}}</h4></v-list-item-title>
                                        <v-list-item-subtitle><h4>Mobile</h4></v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-divider inset></v-divider>

                                <v-list-item>
                                    <v-list-item-action>
                                        <v-icon color="indigo">place</v-icon>
                                    </v-list-item-action>
                                    <v-list-item-content>
                                        <v-list-item-title><h4>{{store.address}}</h4></v-list-item-title>
                                        <v-list-item-subtitle><h4>Address</h4></v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-card>	
                    </gmap-info-window>
                </GmapMarker>
            </GmapMap>
        </v-flex>

        <v-flex shrink>

            <v-subheader>
                Activity Times
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" slot="activator" small icon color="green" @click.stop.prevent="$store.commit('OPEN_ACTIVITY_DIALOG')">
                            <v-icon>edit</v-icon>
                        </v-btn>
                    </template>
                    <span>Update activity times</span>
                </v-tooltip>				
            </v-subheader>
            <v-layout column wrap>
                <v-flex  v-for="(item, index) in store.activities" :key="index">
                    <v-card>
                        <div class="text-xs-center">									
                            {{item.daysofweek}}
                        </div>
                        <v-divider></v-divider>
                        <v-flex v-for="(time, i) in item.times" :key="i" class="text-xs-center">
                            <div><strong>{{time.from}} - {{time.to}}</strong></div>
                        </v-flex>				
                    </v-card>							
                </v-flex>							
            </v-layout>		
        </v-flex>
        <d-dialog/>
    </v-layout>
</template>

<script>
import index from '@/mixins'
import {mapState} from 'vuex'
import {Dialog} from './components'
export default {
    mixins    : [index],
    components: {
        'd-dialog': Dialog
    },
    data() {
        return {
            mapOptions: {
                zoomControl      : true,
                mapTypeControl   : false,
                scaleControl     : false,
                streetViewControl: false,
                rotateControl    : false,
                fullscreenControl: true,
                disableDefaultUi : false
            }
        }
    },
    created() {
        this.fetch()
    },
    beforeDestroy() {
        this.$store.dispatch('clearActivity')
    },
    methods: {
        async fetch() {
            await this.$store.dispatch('fetchActivity')
        }
    },
    computed: {
        ...mapState({
            store  : state => state.store.store,
            loading: state => state.store.loading
        })
    }
}
</script>