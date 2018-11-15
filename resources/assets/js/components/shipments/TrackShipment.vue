<template>
<div class="text-xs-center">
    <v-dialog v-model="OpenTrackBranch" hide-overlay persistent width="1500">
        <v-card v-if="OpenTrackBranch" id="printMe">
            <!-- <v-card> -->
            <v-card-text>
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        <h2>Shipment {{ shipments.airway_bill_no }}</h2>
                    </li>
                </ul>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>Waybill Number: </b></label>{{ shipments.airway_bill_no }}</li>
                            <li class="list-group-item"><label for=""><b>From: </b></label>{{ shipments.sender_city }}</li>
                            <li class="list-group-item"><label for=""><b>Status: </b></label>{{ shipments.status }}</li>
                        </ul>
                    </v-flex>

                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>Derivery Date: </b></label> {{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for=""><b>To: </b></label> {{ shipments.client_city }}</li>
                        </ul>
                    </v-flex>
                    <!-- </div> -->
                </v-layout>

                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Product Details</v-toolbar-title>
                </v-toolbar>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>Waybill Status:</b></label>{{ shipments.status }}</li>
                            <li class="list-group-item" v-if="shipments.status === 'Delivered'"><label for="">Receiver Name: </label>{{ shipments.receiver_name }}</li>
                            <li class="list-group-item"><label for=""><b>Delivery Date: </b></label>{{ shipments.derivery_date }}</li>
                        </ul>
                    </v-flex>

                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>COD amount: </b></label> {{ shipments.cod_amount }}</li>
                            <li class="list-group-item"><label for=""><b>product Description: </b></label> {{ shipments.bar_code }}</li>
                            <li class="list-group-item"><label for=""><b>Quantity: </b></label> {{ shipments.amount_ordered }}</li>
                        </ul>
                    </v-flex>
                </v-layout>

                <v-toolbar card style="background: #122e4f; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">
                        <v-layout wrap>
                            <v-flex sm6>Client Details</v-flex>
                            <v-flex sm6 style="margin-left: 750px;margin-top: -20px;"><b>Sender Details</b></v-flex>
                        </v-layout>
                    </v-toolbar-title>
                </v-toolbar>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>Client Name:</b> </label>{{ shipments.client_name }}</li>
                            <li class="list-group-item"><label for=""><b>Client Email:</b> </label>{{ shipments.client_email }}</li>
                            <li class="list-group-item"><label for=""><b>Client Phone:</b> </label>{{ shipments.client_phone }}</li>
                        </ul>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for=""><b>Sender Name:</b> </label>SpeedBall Courier Services</li>
                            <li class="list-group-item"><label for=""><b>Sender Email:</b> </label>info@speedballcourier.com</li>
                            <li class="list-group-item"><label for=""><b>Sender Phone:</b> </label>+254728492446</li>
                        </ul>
                    </v-flex>

                </v-layout>

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
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-print="'#printMe'" flat color="primary">Print</v-btn>
            </v-card-actions>
            <!-- <div v-show="nextPage" style="text-align: center; width: 100%;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div> -->
            <!-- <div class="text-xs-center">
                <v-pagination v-model="AllShip.current_page" :length="AllShip.last_page" total-visible="7" @input="next" circle></v-pagination>
            </div> -->
            <v-divider></v-divider>
            <v-divider></v-divider>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
export default {
  props: ["shipments", "OpenTrackBranch"],
  data() {
    return {
      dialog: false
    };
  },
  methods: {
    refresh() {
      this.$emit("refreshRequest");
    },
    close() {
      this.$emit("closeRequest");
    }
  },
  computed: {
    getUrl() {
      return "pod/" + this.shipments.id;
    }
  }
};
</script>
