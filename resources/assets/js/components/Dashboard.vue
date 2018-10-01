<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <div v-show="loader" style="text-align: center; width: 100%;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-flex xs12 sm12 v-show="!loader">
                <Notification :user="user"></Notification>
                <v-layout row wrap>
                    <div class="col-md-3">
                        <v-card color="white">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <v-flex sm4>
                                        <i class="pull-left fa fa-car" style="font-size: 70px; color: #9358ac"></i>
                                    </v-flex>
                                    <v-flex sm7 offset-sm1>
                                        <strong>{{ Allshipments }}</strong>
                                        <v-divider></v-divider>
                                        <h6 style="color: #000;">Total Shipments</h6>
                                    </v-flex>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <div class="col-md-3">
                        <v-card color="white">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <v-flex sm4>
                                        <i class="pull-left fa fa-calendar" style="font-size: 70px; color: #3f51b5"></i>
                                    </v-flex>
                                    <v-flex sm7 offset-sm1>
                                        <strong>{{ AllScheduled }}</strong>
                                        <v-divider></v-divider>
                                        <h6 style="color: '#000';">Total Scheduled</h6>
                                    </v-flex>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <div class="col-md-3">
                        <v-card color="white darken-1">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <v-flex sm4>
                                        <i class="pull-left fa fa-check-circle" style="font-size: 70px; color: #ef553a"></i>
                                    </v-flex>
                                    <v-flex sm7 offset-sm1>
                                        <strong>{{ AllderiveredShipment }}</strong>
                                        <v-divider></v-divider>
                                        <h6 style="color: #3b5998;">Total Derivered</h6>
                                    </v-flex>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <div class="col-md-3">
                        <v-card color="white lighten-1">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <v-flex sm4>
                                        <i class="pull-left fa fa-plane" style="font-size: 70px; color: #99a2b3"></i>
                                    </v-flex>
                                    <v-flex sm7 offset-sm1>
                                        <strong>{{ AllCanceled }}</strong>
                                        <v-divider></v-divider>
                                        <h6 style="color: white;">Total Canceled</h6>
                                    </v-flex>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <v-divider></v-divider>
                    <v-divider></v-divider>
                    <v-flex xs8>
                        <line-chart :messages="{empty: 'No data'}" :refresh="10" :download="true" :data="[['2018-01-18 08:31:24', 11], ['2018-2-02 08:31:24', 4], ['2018-03-03 08:31:24', 19], ['2018-04-04 08:31:24', 12]]"></line-chart>
                    </v-flex>
                    <v-flex xs4>
                        <v-card>
                            <v-toolbar color="#fff">
                                <v-toolbar-title>Business Stats</v-toolbar-title>
                            </v-toolbar>
                            <v-list two-line>
                                <template>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Waiting Approval</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: green"> {{ AllCanceled }}</v-list>
                                    </v-list-tile>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Derivery Orders</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: green"> {{ AllderiveredShipment }}</v-list>
                                    </v-list-tile>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Total Packages</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: green"> {{ Allshipments }}</v-list>
                                    </v-list-tile>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Delayed Orders</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: red"> {{ AlldelayedShipment }}</v-list>
                                    </v-list-tile>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Orders Not Completed</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: red">10</v-list>
                                    </v-list-tile>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-sub-title> Total Derivery</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list style="color: orange"> {{ AllderiveredShipment }}</v-list>
                                    </v-list-tile>
                                </template>
                            </v-list>
                        </v-card>
                    </v-flex>
                    <v-divider></v-divider>
                    <v-divider></v-divider>
                    <v-divider></v-divider>

                    <v-flex xs12>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-card>
                                    <v-toolbar color="#fff">
                                        <v-toolbar-title>vehicles</v-toolbar-title>
                                    </v-toolbar>
                                    <v-list two-line>
                                        <template>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-sub-title> Active</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list style="color: green"> 4</v-list>
                                            </v-list-tile>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-sub-title> At Rest</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list style="color: red"> 2</v-list>
                                            </v-list-tile>
                                        </template>
                                    </v-list>
                                </v-card>
                            </v-flex>
                            <v-flex xs4>
                                <v-card>
                                    <v-toolbar color="#fff">
                                        <v-toolbar-title>Derivery Boys</v-toolbar-title>
                                    </v-toolbar>
                                    <v-list two-line>
                                        <template>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-sub-title> Active</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list style="color: green"> 4</v-list>
                                            </v-list-tile>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-sub-title> At Rest</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list style="color: red"> 2</v-list>
                                            </v-list-tile>
                                        </template>
                                    </v-list>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-divider></v-divider>
                    <v-divider></v-divider>
                </v-layout>

                <v-layout wrap>
                    <v-flex xs3 sm2>
                        <v-card color="indigo">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <router-link to="/shipments" class="v-list__tile v-list__tile--link">
                                        <v-flex sm4>
                                            <v-icon color="white">local_shipping</v-icon>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex sm7 offset-sm1>
                                            <h6 style="color: white;">Add Shipment</h6>
                                        </v-flex>
                                    </router-link>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs3 sm2>
                        <v-card color="blue-grey darken-1">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <router-link to="/users" class="v-list__tile v-list__tile--link">
                                        <v-flex sm4>
                                            <v-icon color="white">account_circle</v-icon>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex sm7 offset-sm1>
                                            <h6 style="color: white;">Users</h6>
                                        </v-flex>
                                    </router-link>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs3 sm2>
                        <v-card color="indigo darken-1">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <router-link to="/branches" class="v-list__tile v-list__tile--link">
                                        <v-flex sm4>
                                            <v-icon color="white">widgets</v-icon>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex sm7 offset-sm1>
                                            <h6 style="color: white;">Branches</h6>
                                        </v-flex>
                                    </router-link>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs3 sm2>
                        <v-card color="deep-purple darken-4">
                            <v-card-actions>
                                <v-layout row wrap>
                                    <router-link to="/reports" class="v-list__tile v-list__tile--link">
                                        <v-flex sm4>
                                            <v-icon color="white">book</v-icon>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-flex sm7 offset-sm1>
                                            <h6 style="color: white;">Add Reports</h6>
                                        </v-flex>
                                    </router-link>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
let Notification = require('./notification/Notification.vue');
export default {
    props: ['user'],
    components: {
        Notification
    },
    data() {
        return {
            Allusers: {},
            loader: false,
            Allshipments: {},
            AllScheduled: {},
            AlldelayedShipment: {},
            notCompleted: {},
            AllCanceled: {},
            AllderiveredShipment: {},
            AllapprovedShipment: {},
            chart: [],
            lables: [],
            notifications: [],
            rows: {},
        }
    },
    mounted() {
        this.loader = true
        axios.get('getUsersCount')
            .then((response) => {
                this.Allusers = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('getShipmentsCount')
            .then((response) => {
                this.Allshipments = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('scheduledShipmentCount')
            .then((response) => {
                this.AllScheduled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        // Dashboard
        axios.get('delayedShipmentCount')
            .then((response) => {
                this.AlldelayedShipment = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('getCanceledCount')
            .then((response) => {
                this.AllCanceled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('deriveredShipmentCount')
            .then((response) => {
                this.AllderiveredShipment = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('deriveredShipmentCount')
            .then((response) => {
                this.loader = false
                this.AllderiveredShipment = response.data
            })
            .catch((error) => {
                this.loader = false
                this.errors = error.response.data.errors
            })

        axios.get('getChartData')
            .then((response) => {
                console.log(response);
                this.chart = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

    }
}
</script>

<style scoped>
#cols {
    background: #f0f0f0;
}

.v-content__wrap {
    margin-top: -60px;
}
</style>
