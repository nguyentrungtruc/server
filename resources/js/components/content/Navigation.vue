<template>
    <v-navigation-drawer disable-route-watcher app :clipped="$vuetify.breakpoint.mdAndUp" :value="drawer" expand-on-hover>
        <v-list dense shaped>
            <template v-for="(item, index) in items">
                <v-subheader v-if="item.header" :key="index">
                    {{ item.header }}
                </v-subheader>

                <v-list-item v-else-if="!item.sub" :key="index" active-class="red--text text--darken-4" :to="{name:item.action}" exact>
                    <v-list-item-action>
                    <v-icon>{{item.icon}}</v-icon>
                    </v-list-item-action>
                    <v-list-item-title>{{item.title}}</v-list-item-title>
                </v-list-item>
                
                <v-list-group v-else :key="index" :prepend-icon="item.icon" active-class="red--text text--darken-4">
                    <template v-slot:activator>
                        <v-list-item-title>{{item.title}}</v-list-item-title>
                    </template>

                    <v-list-item v-for="(child, i) in item.children" :key="i"  :to="{name: child.action}" exact active-class="red--text text--darken-4" >
                        <v-list-item-action>
                            <v-icon>keyboard_arrow_right</v-icon>
                        </v-list-item-action>
                        <v-list-item-title>{{child.title}}</v-list-item-title>                        
                    </v-list-item>                    
                </v-list-group>
            </template>
        </v-list>      
        <template v-slot:append>
            <div class="pa-2">
                <v-btn block @click.stop="logout()">Logout</v-btn>
            </div>
        </template>  
    </v-navigation-drawer>
</template>

<script>
import {mapState} from 'vuex'
export default {
    data() {
        return { 
            items : [		
                {icon: 'dashboard', title: 'Dashboard', action: 'Dashboard', sub:false},
                { icon: 'receipt', title: 'Manage Orders', action: 'Order', sub: false},
                { icon: 'store', title: 'Manage Stores', action: 'Store', sub: false},
                {
                    icon    : 'group',
                    title   : 'Manage Users',
                    model   : false,
                    children: [
                    { title: 'Users', action:'Customer' },
                    { title: 'Roles' , action:'Role'},
                    { title: 'Passports', action:'Passport'},
                    ],
                    sub: true
                },
                { icon: 'redeem', title: 'Manage Coupons', action: 'Coupon', sub: false},
                {
                    icon    : 'settings',
                    title   : 'Manage Services',
                    model   : false,
                    children: [
                    { title: 'Services', action: 'Service'},
                    { title: 'Delivery Locations', action: 'Delivery'},
                    { title: 'Range For Delivery', action: 'Range'},
                    ],
                    sub: true
                },
                {header: 'Store'},
                {
                    icon    : 'format_list_numbered',
                    title   : 'Manage Types',
                    model   : false,
                    children: [
                    { title: 'Types Of Stores', action: 'Type'},
                    { title: 'Rating Types', action: 'RatingType'}
                    ],
                    sub: true
                },
                { icon: 'access_time', title: 'Manage Activity', action: 'Activity', sub:false},
                { icon: 'format_size', title: 'Manage Size', action: 'Size', sub:false},
                {header: 'System'},
                {
                    icon    : 'place',
                    title   : 'Manage Locations',
                    active  : true,
                    model   : false,
                    children: [
                    { title: 'Districts', action: 'District'},
                    { title: 'Cities', action: 'City'},
                    { title: 'Countries', action: 'Country'},
                    ],
                    sub: true
                },		
                {
                    icon    : 'lens',
                    title   : 'Manage Status',
                    model   : false,
                    children: [
                    { title: 'Order Status', action: 'OrderStatus'},
                    { title: 'Coupon Status', action: 'CouponStatus'},
                    { title: 'Product Status', action: 'ProductStatus'},
                    { title: 'Store Status', action: 'StoreStatus'},
                    ],
                    sub: true
                },
                { icon: 'payment', title: 'Payment Methods', action: 'PaymentMethod', sub:false},
            ]
        }
    },
    methods: {
        goTo(action) {
            this.$router.push({name: action})
        },
        logout() {
            const data = []
            const url  = `/Credentials/Logout`
            this.axios.post(url, data, {withCredentials:true}).then(response => {
                this.$store.dispatch('logout')
                this.$auth.destroyToken()
                this.$router.push({name: 'Home'})
            })
        }
    },
    computed : {
        ...mapState({
            drawer: state => state.drawer
        })
    },
    created() {

    }
}
</script>