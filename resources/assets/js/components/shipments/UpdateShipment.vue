<template>
<v-layout row justify-center>

    <v-dialog v-model="UpdateShipment" persistent width="1300px">
        <v-card v-if="UpdateShipment">
            <v-card-title>
                Update Shipment
            </v-card-title>
            <v-container grid-list-md>
                <v-card style="width: 100%;">
                    <v-layout row wrap>
                        <v-flex sm8>
                            <v-layout wrap>
                                <v-flex sm6>
                                    <GmapAutocomplete @place_changed="setPlace" class="form-control" v-show="showMap">
                                    </GmapAutocomplete>
                                </v-flex>
                                <v-flex sm6>
                                    <v-card-actions>
                                        <v-btn flat color="primary" @click="usePlace" v-show="showMap" class="col-md-6">Add</v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="mapUpsd" v-show="!showMap" class="col-md-6">Update Location</v-btn>
                                        <v-btn flat color="primary" @click="locationUpdate" :loading="loading_loc" :disabled="loading_loc" class="col-md-6" v-show="showMap">Update Location</v-btn>
                                    </v-card-actions>
                                </v-flex>
                            </v-layout>

                            <GmapMap style="width: 700px; height: 450px;" :zoom="4" :center="{lat: -1.3072985, lng: 36.908417299999996}">
                                <GmapMarker v-for="(marker, index) in markers" :key="index" :position="marker.position" />
                                <GmapMarker v-if="this.place" label="★" :position="{
                                lat: this.place.geometry.location.lat(),
                                lng: this.place.geometry.location.lng(),
                                }" />
                            </GmapMap>
                            <v-divider></v-divider>
                        </v-flex>
                        <v-flex sm4 style="border-left: 1px solid #c1c1c1;">
                            <div class="form-group col-md-6">
                                <label for="insurance" class="col-md-4 col-form-label text-md-right">Status</label>
                                <select class="custom-select custom-select-md col-md-12" v-model="updateitedItem.status">
                                <option value="Awaiting Approval">Awaiting Approval</option>
                                <option value="Approved">Approved</option>
                                <option value="Arrived">Arrived</option>
                                <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                <option value="Cancelled ">Call Back</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Cleared">Cleared</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Dispatched">Dispatched</option>
                                <!-- <option value="Cancled">Cancled</option> -->
                                <option value="Hold">Hold</option>
                                <option value="Not Available">Not Available</option>
                                <option value="Not Picking">Not Picking</option>
                                <option value="Out For Destination">Out For Destination</option>
                                <option value="Offline">Offline</option>
                                <option value="Out For Delivery">Out For Delivery</option>
                                <option value="Returned">Returned</option>
                                <option value="Ready For Depart">Ready For Depart</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Shipment Collected">Shipment Collected</option>
                                <option value="Transit">Transit</option>
                                <option value="Waiting for Scan">Waiting for scan</option>
                                <option value="Wrong Number">Wrong Number</option>
                            </select>
                            </div>
                            <div v-if="updateitedItem.status === 'Scheduled'">
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="updateitedItem.derivery_date" color="blue darken-2" label="Schedule Date" type="date"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="updateitedItem.derivery_time" color="blue darken-2" label="Schedule Time" type="time"></v-text-field>
                                </v-flex>
                                <v-flex xs4 sm12>
                                    <v-text-field v-model="updateitedItem.location" color="blue darken-2" label="Location" required></v-text-field>
                                </v-flex>
                            </div>
                            <div v-if="updateitedItem.status === 'Not Picking'">
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="updateitedItem.derivery_date" color="blue darken-2" label="Date" type="date"></v-text-field>
                                </v-flex>
                                <v-flex xs4 sm12>
                                    <v-text-field v-model="updateitedItem.location" color="blue darken-2" label="Location" required></v-text-field>
                                </v-flex>
                            </div>
                            <v-flex xs12 sm12>
                                <v-textarea v-model="updateitedItem.remark" color="blue">
                                    <div slot="label">
                                        Remark
                                    </div>
                                </v-textarea>
                            </v-flex>

                            <v-flex xs12 sm12>
                                <v-btn flat color="primary" @click="paid" v-if="updateitedItem.paid === 0">Mark as paid</v-btn>
                                <v-btn flat color="primary" @click="paid" v-if="updateitedItem.paid === 1">Mark as unpaid</v-btn>
                                <v-btn color="primary" raised disabled style="color: rgb(76, 175, 80) !important;" v-if="updateitedItem.paid === 0">Not Paid</v-btn>
                                <v-btn color="primary" raised disabled style="color: rgb(76, 175, 80) !important;" v-if="updateitedItem.paid === 1">Paid</v-btn>
                                <!-- <v-btn flat color="success" disabled>{{ updateitedItem.paid }}</v-btn> -->
                            </v-flex>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>

                    </v-layout>
                </v-card>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-flex xs4 sm3>
                        <v-text-field v-model="dist" color="blue darken-2" label="Distance" ref="distanceGet" required></v-text-field>
                    </v-flex>
                    <v-btn flat @click="getDistance">Dist</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="UpdateStatus" :loading="loading" :disabled="loading">Update Status</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
// import VueGoogleAutocomplete from "vue-google-autocomplete";
export default {
    props: ['UpdateShipment', 'AllProducts', 'updateitedItem', 'markers'],
    components: {
        // VueGoogleAutocomplete,
    },
    data() {
        return {
            loading: false,
            loading_loc: false,
            // markers: [],
            showMap: false,
            place: null,
            dist: ''
        }
    },
    description: 'Maps',

    methods: {
        locationUpdate() {
            this.loading_loc = true
            this.updateitedItem.location = this.updateitedItem.location
            axios
                .post(`/locationUpdate/${this.updateitedItem.id}`, this.markers)
                .then(response => {
                    this.loading_loc = false
                    this.alert()
                    // Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.updateitedItem)
                })
                .catch(error => {
                    this.loading_loc = false;
                    this.errors = error.response.data.errors;
                });
        },
        UpdateStatus() {
            // alert(this.updateitedItem.id);
            this.updateitedItem.derivery_date = ''
            this.loading = true
            axios
                .post(`/updateStatus/${this.updateitedItem.id}`, {
                    formobg: this.updateitedItem,
                    address: this.address
                })
                .then(response => {
                    this.loading = false
                    this.alert()
                    this.close()
                    // Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.updateitedItem)
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        paid() {
            if (this.updateitedItem.paid == 0) {
                this.updateitedItem.paid = 1
            } else {
                this.updateitedItem.paid = 0
            }
            axios
                .post(`/paid/${this.updateitedItem.id}`, this.updateitedItem)
                .then(response => {
                    this.loading = false
                    this.alert()
                    // Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.updateitedItem)
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },

        // getAddressData: function(addressData, placeResultData, id) {
        //   this.address = addressData;
        // },

        close() {
            this.$emit("closeRequest");
            this.showMap = false
        },
        alert() {
            this.$emit("alertRequest");
        },
        setDescription(description) {
            this.description = description;
        },
        setPlace(place) {
            this.place = place
        },
        usePlace(place) {
            if (this.place) {
                this.markers.push({
                    position: {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                    }
                })
                this.place = null;
            }
        },
        mapUpsd() {
            this.markers = []
            this.showMap = true
        },
        getDistance() {
            var directionsService = new google.maps.DirectionsService();

            var request = {
                origin: 'Mombasa, Kenya', // a city, full address, landmark etc
                destination: 'Nairobi, Kenya',
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            // var dista = this.dist

            directionsService.route(request, function (response, status, dista) {
                // if (status == google.maps.DirectionsStatus.OK) {
                    this.$refs["distanceGet"]
                    console.log(response.routes[0].legs[0].distance.value)
                    alert(response.routes[0].legs[0].distance.value);
                    dista = response.routes[0].legs[0].distance.value // the distance in metres
                    this.dist = dista
                    alert(dista)
                    alert(this.dist)
                // } else {
                //     alert('wrong');
                    // oops, there's no route between these two locations
                    // every time this happens, a kitten dies
                    // so please, ensure your address is formatted properly
                // }
            });
            // alert(this.dista)
            // this.dist = dista
            // return dist
        }

    },
    computed: {
        // getDistance() {
        //     var directionsService = new google.maps.DirectionsService();

        //     var request = {
        //         origin: 'Mombasa, Kenya', // a city, full address, landmark etc
        //         destination: 'Nairobi, Kenya',
        //         travelMode: google.maps.DirectionsTravelMode.DRIVING
        //     };

        //     directionsService.route(request, function (response, status) {
        //         // if (status == google.maps.DirectionsStatus.OK) {
        //             // alert(response.routes[0].legs[0].distance.value);
        //             this.dist = response.routes[0].legs[0].distance.value // the distance in metres
        //         // } else {
        //         //     alert('wrong');
        //             // oops, there's no route between these two locations
        //             // every time this happens, a kitten dies
        //             // so please, ensure your address is formatted properly
        //         // }
        //     });
        //     // return dist
        // }
        // getMarkers() {
        //     if (this.UpdateShipment) {
        //         this.markers.push({
        //             position: {
        //                 lat: this.updateitedItem.lat,
        //                 lng: this.updateitedItem.lng,
        //             }
        //         })
        //     }
        // }
    },

    mounted() {

    }
}
</script>
