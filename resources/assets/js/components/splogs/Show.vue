<template>
<div class="text-xs-center">
    <v-dialog v-model="OpenTrackBranch" hide-overlay persistent width="1500">
        <v-card v-if="OpenTrackBranch">
            <!-- <v-card> -->
            <v-card-text id="printMe">
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        <barcode v-bind:value="shipments.bar_code" style="margin-top: 5px !important;"></barcode>
                    </li>
                    <span>
              <v-btn icon dark @click="close">
                <v-icon color="black">close</v-icon>
              </v-btn>
            </span>
                </ul>
                <table class="table table-hover">
                    <thead>
                        <th>Waybill</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Delivery Date</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.bar_code }}</td>
                            <td>{{ shipments.sender_city }}</td>
                            <td>{{ shipments.client_city }}</td>
                            <td>{{ shipments.status }}</td>
                            <td>{{ shipments.derivery_date }}</td>
                        </tr>
                    </tbody>
                </table>

                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Product Details</v-toolbar-title>
                </v-toolbar>
                <table class="table table-hover">
                    <thead>
                        <th>Waybill Status</th>
                        <th>Receiver Name</th>
                        <th>Delivery Date</th>
                        <th>COD Amount</th>
                        <th>Quantity</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.status }}</td>
                            <td>{{ shipments.receiver_name }}</td>
                            <td>{{ shipments.derivery_date }}</td>
                            <td>{{ shipments.cod_amount }}</td>
                            <td>{{ shipments.amount_ordered }}</td>
                        </tr>
                    </tbody>
                </table>
                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Client Details</v-toolbar-title>
                </v-toolbar>
                <table class="table table-hover">
                    <thead>
                        <th>Client Name</th>
                        <th>Client Email</th>
                        <th>Client Phone</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.client_name }}</td>
                            <td>{{ shipments.client_email }}</td>
                            <td>{{ shipments.client_phone }}</td>
                        </tr>
                    </tbody>
                </table>

                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Branch&Rider</v-toolbar-title>
                </v-toolbar>
                <table class="table table-hover">
                    <thead>
                        <th>Rider Name</th>
                        <th>Branch Name</th>
                    </thead>
                    <tbody>
                        <tr v-for="shipment in shipD" :key="shipment.id">
                            <td>{{ shipment.driver }}</td>
                            <td>{{ shipment.branch_id }}</td>
                        </tr>
                    </tbody>
                </table>
                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Sender Details</v-toolbar-title>
                </v-toolbar>
                <table class="table table-hover">
                    <thead>
                        <th>Sender Name</th>
                        <th>Sender Email</th>
                        <th>Sender Phone</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.sender_name }}</td>
                            <td>{{ shipments.sender_email }}</td>
                            <td>{{ shipments.sender_phone }}</td>
                        </tr>
                    </tbody>
                </table>
<!-- 
                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Waybill Event Tracking</v-toolbar-title>
                </v-toolbar>
                <v-layout wrap>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Events</th>
                                <th scope="col">Event date and time</th>
                                <th scope="col">Location</th>
                                <th scope="col">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="statuses in shipments.statuses" :key="statuses.id">
                                <th scope="row">1</th>
                                <td>{{ statuses.status }}</td>
                                <td>{{ statuses.created_at }}</td>
                                <td>{{ statuses.location }}</td>
                                <td>{{ statuses.remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </v-layout> -->
            </v-card-text>

            <v-card-actions>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-print="'#printMe'" flat color="primary">Print</v-btn>
            </v-card-actions>
            <v-divider></v-divider>
            <v-divider></v-divider>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    components: {
        barcode: VueBarcode
    },
    data() {
        return {
            shipD: [],
            shipments: [],
            OpenTrackBranch: false
        };
    },
    methods: {
        refresh() {
            this.$emit("refreshRequest");
        },
        TrackEvent() {
            eventBus.$emit("TrackEvent", this.shipments);
        },
        close() {
            // this.$emit("closeRequest");
            this.OpenTrackBranch = false
        },
        showShip(data) {
            axios
                .post(`/getshipD/${data.id}`)
                .then(response => {
                    this.shipD = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
        shipmentGet(data) {
            axios
                .post(`/getShipSingle/${data.id}`)
                .then(response => {
                    this.shipments = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        }
    },

    created() {
        eventBus.$on("ShowShipEvent", data => {
            this.shipmentGet(data);
            this.showShip(data);
            this.OpenTrackBranch = true;
        });
    },
    computed: {
        getUrl() {
            return "pod/" + this.shipments.id;
        }
    }
};
</script>

<style scoped>
.vue-barcode-element {
    margin-top: 5px !important;
}
</style>
