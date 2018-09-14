<template>
<v-layout row justify-center>
    <v-dialog v-model="UpdateShipmentStatus" persistent width="500px">
        <v-card v-if="UpdateShipmentStatus">
            <v-card-title>
                Update Shipment Status
            </v-card-title>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <select class="custom-select custom-select-md col-md-12" v-model="form.status">
                                <option value="Awaiting Approval">Awaiting Approval</option>
                                <option value="Approved">Approved</option>
                                <option value="Arrived">Arrived</option>
                                <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                <option value="Call Back ">Call Back</option>
                                <option value="Cancelled ">Cancelled</option>
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
                            <div v-if="form.status === 'Scheduled'">
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.scheduled_date" color="blue darken-2" label="Schedule Date" type="date"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.derivery_time" color="blue darken-2" label="Schedule Time" type="time"></v-text-field>
                                </v-flex>
                                <v-flex xs4 sm12>
                                    <v-text-field v-model="form.location" color="blue darken-2" label="Location" required></v-text-field>
                                </v-flex>
                            </div>
                            <v-flex xs12 sm12>
                                <v-textarea v-model="form.remark" color="blue">
                                    <div slot="label">
                                        Remark <small>(optional)</small>
                                    </div>
                                </v-textarea>
                            </v-flex>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="UpdateShipment" :loading="loading" :disabled="loading">Update Status</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['UpdateShipmentStatus', 'updateitedItem', 'selectedItems'],
    data() {
        return {
            loading: false,
            snackbar: false,
            timeout: 5000,
            message: "",
            color: "",
            form: {
                scheduled_date: '',
                derivery_time: '',
                location: '',
                remark: '',
            },
        }
    },
    methods: {
        UpdateShipment() {
            // alert(this.updateitedItem.id);
            this.loading = true
            axios
                .patch(`/UpdateShipment`, {
                    selected: this.selectedItems,
                    form: this.form
                })
                .then(response => {
                    this.loading = false
                    this.$emit("alertRequest");
                      this.close();
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        },
    },
    mounted() {}
}
</script>
