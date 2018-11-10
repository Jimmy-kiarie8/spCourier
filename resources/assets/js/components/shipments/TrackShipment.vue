<template>
<div class="text-xs-center">
    <v-dialog v-model="OpenTrackBranch" hide-overlay persistent width="1500">
        <v-card v-if="OpenTrackBranch">
            <v-toolbar card blue-grey darken-1>
                <v-toolbar-title class="body-2">
                    Shipments Details
                    <v-spacer></v-spacer>
                    <v-btn icon dark @click="close">
                        <v-icon color="black">close</v-icon>
                    </v-btn>
                </v-toolbar-title>
            </v-toolbar>
            <v-card-text id="printMe">
                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">Waybill Number: </label>{{ shipments.airway_bill_no }}</li>
                            <li class="list-group-item"><label for="">From: </label>{{ shipments.sender_city }}</li>
                            <li class="list-group-item"><label for="">Parcels Sent: </label>{{ shipments.products.length }}</li>
                            <li class="list-group-item"><label for="">Status: </label>{{ shipments.status }}</li>
                        </ul>
                    </v-flex>

                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">Derivery Date: </label> {{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for="">Derivery Time: </label> {{ shipments.derivery_time }}</li>
                            <li class="list-group-item"><label for="">To: </label> {{ shipments.client_city }}</li>
                            <li class="list-group-item"><label for="">Assigned Staff: </label> {{ shipments.assign_staff }}</li>
                        </ul>
                    </v-flex>
                </v-layout>

                <v-toolbar card blue-grey darken-1>
                    <v-toolbar-title class="body-2">Proof of Delivery (POD) for this parcel</v-toolbar-title>
                </v-toolbar>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">Waybill Status: </label>{{ shipments.status }}</li>
                            <li class="list-group-item"><label for="">Receiver Name: </label>{{ shipments.client_name }}</li>
                            <li class="list-group-item"><label for="">Delivery Date: </label>{{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for="">Parcels PODed: </label>{{ shipments.products.length }}</li>
                        </ul>
                    </v-flex>

                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">ID Number: </label> {{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for="">Delivery Time: </label> {{ shipments.derivery_time }}</li>
                            <form :action="getUrl" method="post" target="_blank">
                                <li class="list-group-item"><label for="">POD: </label>
                                    <v-btn color="primary" raised type="submit">Download POD</v-btn>
                                </li>
                            </form>
                        </ul>
                    </v-flex>
                </v-layout>

                <v-toolbar card blue-grey darken-1>
                    <v-toolbar-title class="body-2">
                        <v-layout wrap>
                            <v-flex sm6>Sender Details</v-flex>
                            <v-flex sm6 style="margin-left: 750px;margin-top: -20px;">Recipient Details</v-flex>
                        </v-layout>
                    </v-toolbar-title>
                </v-toolbar>
                <v-layout wrap>
                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">Receiver Name: </label>{{ shipments.client_name }}</li>
                            <li class="list-group-item"><label for="">Delivery Date: </label>{{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for="">Parcels PODed: </label>{{ shipments.products.length }}</li>
                        </ul>
                    </v-flex>

                    <v-flex xs12 sm6>
                        <ul class="list-group">
                            <li class="list-group-item"><label for="">ID Number: </label> {{ shipments.derivery_date }}</li>
                            <li class="list-group-item"><label for="">Delivery Time: </label> {{ shipments.derivery_time }}</li>
                        </ul>
                    </v-flex>
                </v-layout>

                <v-toolbar card blue-grey darken-1>
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
        </v-card>
    </v-dialog>
</div>
</template>

<script>
export default {
    props: ['shipments', 'OpenTrackBranch'],
    data() {
        return {
            dialog: false
        }
    },
    methods: {
        refresh() {
            this.$emit('refreshRequest')
        },
        close() {
            this.$emit('closeRequest')
        }
    },
    computed: {
        getUrl() {
            return 'pod/' + this.shipments.id
        }
    }
}
</script>
