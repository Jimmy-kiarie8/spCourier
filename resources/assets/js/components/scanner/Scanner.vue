<template>
<v-content>
    <v-container fluid fill-height v-show="!loader">
        <v-layout justify-center align-center>
            <v-snackbar v-model="snackbar" absolute bottom left dark :color="color">
                <span>{{message}}</span>
                <v-icon dark>{{icon}}</v-icon>
            </v-snackbar>
            <v-card>
                <v-layout row wrap>
                    <v-flex sm5>
                        <v-form ref="form" @submit.prevent style="width: 100%;">
                            <v-container grid-list-md text-xs-center>
                                <h2>In Scan</h2>
                                <v-layout row wrap>
                                    <v-flex xs6 sm4>
                                        <v-text-field v-model="form.scan_date" :type="'date'" color="blue darken-2" label="Date" required></v-text-field>
                                    </v-flex>
                                    <div class="form-group col-md-4">
                                        <label for="inputState">Status</label>
                                        <select class="custom-select" v-model="form.status_in">
                                            <option value="Awaiting Approval" selected>Awaiting Approval</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Arrived">Arrived</option>
                                            <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Cleared">Cleared</option>
                                            <option value="Delivered">Delivered</option>
                                            <option value="Dispatched">Dispatched</option>
                                            <option value="Hold">Hold</option>
                                            <option value="Not Picking">Not Picking</option>
                                            <option value="Out For Destination">Out For Destination</option>
                                            <option value="Out For Delivery">Out For Delivery</option>
                                            <option value="Returned">Returned</option>
                                            <option value="Ready For Depart">Ready For Depart</option>
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="Shipment Collected">Shipment Collected</option>
                                            <option value="Transit">Transit</option>
                                            <option value="Waiting for Scan">Waiting for scan</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputAddress2">Location</label>
                                        <input type="text" class="form-control" id="inputAddress2" placeholder="Location" v-model="form.location">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="inputCity">Remarks</label>
                                        <textarea class="form-control" v-model="form.remarks_in" placeholder="Remarks" rows="3"></textarea>
                                    </div>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12>
                                        <!-- <v-flex xs12 sm12>
                                                <v-text-field v-model="form.bar_code_in" color="blue darken-2" label="Inscan" required style="margin-top: 40px;"></v-text-field>
                                            </v-flex> -->
                                        <div class="form-group col-md-12">
                                            <label for="inputAddress1">Inscan</label>
                                            <input type="text" class="form-control" id="inputAddress1" placeholder="Barcode" v-model="form.bar_code_in" @change="Inscan" ref="scanIn">
                                        </div><br>
                                        <v-flex xs12 sm12>
                                            <barcode :value="form.bar_code_in" style="height: 30px;"></barcode>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-btn color="primary" flat @click="Inscansub" :disabled="loading_in" :loading="loading_in">Inscan
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-flex>
                    <v-flex sm5 offset-sm2>
                        <v-form ref="form" @submit.prevent style="width: 100%;">
                            <v-container grid-list-md text-xs-center>
                                <h2>Out Scan</h2>
                                <v-layout row wrap>
                                    <v-flex xs6 sm5>
                                        <v-text-field v-model="form.scan_date_out" :type="'date'" color="blue darken-2" label="Date" required></v-text-field>
                                    </v-flex>
                                    <div class="form-group col-md-3">
                                        <label for="inputState">Status</label>
                                        <select class="custom-select" v-model="form.status_out">
                                            <option value="Awaiting Approval" selected>Awaiting Approval</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Arrived">Arrived</option>
                                            <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Cleared">Cleared</option>
                                            <option value="Delivered">Delivered</option>
                                            <option value="Dispatched">Dispatched</option>
                                            <option value="Hold">Hold</option>
                                            <option value="Not Picking">Not Picking</option>
                                            <option value="Out For Destination">Out For Destination</option>
                                            <option value="Out For Delivery">Out For Delivery</option>
                                            <option value="Returned">Returned</option>
                                            <option value="Ready For Depart">Ready For Depart</option>
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="Shipment Collected">Shipment Collected</option>
                                            <option value="Transit">Transit</option>
                                            <option value="Waiting for Scan">Waiting for scan</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputAddress2">Location</label>
                                        <input type="text" class="form-control" id="inputAddress2" placeholder="Location" v-model="form.location">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="inputCity">Remarks</label>
                                        <textarea class="form-control" v-model="form.remarks_out" placeholder="Remarks" rows="3"></textarea>
                                    </div>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12>
                                        <div class="form-group">
                                            <label for="inputAddress2">Outscan</label>
                                            <input type="text" class="form-control" id="inputAddress2" placeholder="Barcode" v-model="form.bar_code" @change="Outscan">
                                        </div><br>
                                        <!-- <v-flex xs12 12>
                                            <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor" v-model="form.bar_code" @change="Outscan">
                                            <v-text-field v-model="form.bar_code" color="blue darken-2" label="Outscan" required style="margin-top: 40px;" @change="Outscan"></v-text-field>
                                            
                                        </v-flex> -->
                                        <v-flex xs12 sm12>
                                            <barcode :value="form.bar_code" style="height: 30px;"></barcode>
                                        </v-flex>
                                        <v-divider></v-divider>
                                        <v-btn color="primary" flat @click="OutscanSub" :disabled="loading" :loading="loading">Outscan
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-flex>
                </v-layout>
                <table class="table table-hover" v-if="AllScanned.length > 0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Waybill Number</th>
                            <th scope="col">Cod Amount</th>
                            <th scope="col">Client Name</th>
                            <th scope="col">Client Address</th>
                            <th scope="col">Client Phone</th>
                            <th scope="col">Sender Name</th>
                            <th scope="col">Sender Address</th>
                            <th scope="col">Sender City</th>
                            <th scope="col">Sender Phone</th>
                            <th scope="col">Special Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="scan, key in AllScanned" :key="scan.id">
                            <th scope="row">{{ key+1 }}</th>
                            <td>{{ scan.airway_bill_no }}</td>
                            <td>{{ scan.cod_amount }}</td>
                            <td>{{ scan.client_name }}</td>
                            <td>{{ scan.client_address }}</td>
                            <td>{{ scan.client_phone }}</td>
                            <td>{{ scan.sender_name }}</td>
                            <td>{{ scan.sender_address }}</td>
                            <td>{{ scan.sender_city }}</td>
                            <td>{{ scan.sender_phone }}</td>
                            <td>{{ scan.speciial_instruction }}</td>
                        </tr>
                    </tbody>
                </table>
            </v-card>
        </v-layout>
    </v-container>

    <div v-show="loader" style="text-align: center; width: 100%;">
        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
    </div>
</v-content>
</template>

<script>
import VueBarcode from 'vue-barcode';
export default {
    props: ['user', 'role'],
    components: {
        'barcode': VueBarcode
        // , UpdateShipment
    },
    data() {
        const defaultForm = Object.freeze({
            bar_code: ''
        })
        return {
            errors: [],
            message: 'test',
            AllShipments: {},
            form: Object.assign({}, defaultForm),
            snackbar: false,
            errors: {},
            icon: 'check_circle',
            color: 'black',
            loader: false,
            loading_in: false,
            loading: false,
            AllScanned: [],
        }
    },
    methods: {
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        Outscan() {
            // var length = this.AllShipments.length;
            // alert('submit')
            // for (var i = 0; i < this.AllShipments.length; i++) {
            // if (this.AllShipments[i].bar_code == this.form) {
            // alert('success')
            this.loading = true
            axios.post(`/barcodeUpdate/${this.form.bar_code}`, this.$data.form)
                .then((response) => {
                    this.loading = false
                    // console.log(response.data.errors);
                    if (response.error) {
                        this.errors = response.data.errors
                        this.snackbar = true
                        this.message = 'shipment Not found'
                        this.icon = 'block'
                        this.color = 'red'
                    } else {
                        this.AllScanned.push(response.data);
                        this.form.bar_code = ''
                        // this.AllScanned = response.data
                        // console.log(response);
                        // this.snackbar = true
                        // this.message = 'successifully scanned'
                        // this.icon = 'check_circle'
                        // this.color = 'indigo'
                        // this.resetForm()
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading = false
                })
            // return true;
            // die();
            // } else {
            //     this.snackbar = true
            //     this.message = "barcode doesn'/t exist... Please try again";
            //     this.icon = 'block'
            //     this.color = 'red'
            // }
        },
        // },
        Inscan() {
            // var length = this.AllShipments.length;
            // for (var i = 0; i < length; i++) {
            //     if (this.AllShipments[i].bar_code == this.form) {
            // alert('success')
            this.loading_in = true
            axios.post(`/barcodeIn/${this.form.bar_code_in}`, this.$data.form)
                .then((response) => {
                    this.loading_in = false
                    if (response.error) {
                        this.errors = response.errors;
                        this.snackbar = true
                        this.message = 'shipment Not found'
                        this.icon = 'block'
                        this.color = 'red'
                    } else {
                        this.AllScanned.push(response.data);
                        this.form.bar_code_in = ''
                    }

                    // this.AllScanned = response.data
                    // console.log(response);
                    // this.snackbar = true
                    // this.message = 'successifully scanned'
                    // this.icon = 'check_circle'
                    // this.color = 'indigo'
                    // this.resetForm()
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading_in = false
                })
            // return true;
            // die();
            // return;
            // } else {
            //     this.snackbar = true
            //     this.message = "barcode doesn'/t exist... Please try again";
            //     this.icon = 'block'
            //     this.color = 'red'
            // }
            // }
        },

        OutscanSub() {
            this.loading = true
            axios.post('/statusUpdate', {
                    scan: this.$data.AllScanned,
                    form: this.$data.form
                })
                .then((response) => {
                    this.loading = false
                    // this.AllScanned.push(response.data);
                    // this.AllScanned = response.data
                    console.log(response);
                    this.snackbar = true
                    this.message = 'successifully scanned'
                    this.icon = 'check_circle'
                    this.color = 'indigo'
                    // this.resetForm()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        Inscansub() {
            this.loading = true
            axios.post('/statusUpdateIn', {
                    scan: this.$data.AllScanned,
                    form: this.$data.form
                })
                .then((response) => {
                    this.loading = false
                    // this.AllScanned.push(response.data);
                    // this.AllScanned = response.data
                    console.log(response);
                    this.snackbar = true
                    this.message = 'successifully scanned'
                    this.icon = 'check_circle'
                    this.color = 'indigo'
                    // this.resetForm()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        }
    },
    mounted() {
        this.loader = true
        axios.get('/getShipments')
            .then((response) => {
                this.AllShipments = response.data
                this.loader = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
            })
    },
    computed: {
        formIsValid() {
            return (
                this.form.bar_code
            )
        },
        submit() {
            if (this.form.bar_code) {
                return this.Outscan
            }
        }
    },
}
</script>

  <style scoped>

</style>

// 0000002
