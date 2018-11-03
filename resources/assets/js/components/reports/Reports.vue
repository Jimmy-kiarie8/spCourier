<template>
<v-content>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="blue" style="margin: 1rem"></v-progress-circular>
    </div>
    <v-container fluid fill-height v-show="!loader">
        <!-- <div> -->
        <v-layout justify-center align-center>
            <!-- <v-layout row>
                <v-flex xs6 sm6>
                </v-flex>
            </v-layout> -->
            <v-layout row>
                <v-flex xs6 sm6>
                    <v-card>
                        <h1>Clients Reports</h1>
                        <hr>
                        <!-- <form action="userDateExpo" method="post"> -->
                        <label for="">Client</label>
                        <select class="custom-select custom-select-md col-md-12 col-md-12" v-model="Client.client_id" style="font-size: 13px;">
                                    <option v-for="customer in Allcustomers" :value="customer.id" :key="customer.id">{{ customer.name }}</option>
                            </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Client.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Client.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-btn flat @click="ClientReport" :loading="Cload" :disabled="Cload" success color="black">Get Data</v-btn>
                        <download-excel :data="AllClientR" :fields="json_fields" v-show="Cdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>

                            <!-- </form> -->
                    </v-card>
                </v-flex>

                <v-divider vertical></v-divider>
                <v-flex xs6 sm6>
                    <v-card>
                        <h1>Status Reports</h1>
                        <hr>
                        <form action="displayReport" method="post">
                            <!-- <select class="custom-select custom-select-md col-md-12" v-model="statusR.status">
                                            <option value="Awaiting Approval">Awaiting Approval</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Arrived">Arrived</option>
                                            <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                            <option value="Cancelled ">cancelled</option>
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
                                        </select> Between -->
                            <div>
                                <label for="">Status</label>
                                <select v-model="statusR.status" class="custom-select custom-select-md col-md-12">
                                    <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                </select>
                            </div>
                            <hr>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="statusR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="statusR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-btn flat @click="AllStatusR" :loading="Sload" :disabled="Sload" success color="black">Get Data</v-btn>
                            <download-excel :data="AllStatus" :fields="json_fields" v-show="Sdown">
                                Export
                                <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                        </form>
                    </v-card>
                </v-flex>

                <v-flex xs6 sm6>
                    <v-card>
                        <h1>Branch Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <label for="">Branch</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="branchR.branch_id">
                                    <option v-for="branch in AllBranches" :key="branch.id" :value="branch.id">{{ branch.branch_name }}</option>
                                </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="branchR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="branchR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-btn flat @click="AllbranchR" :loading="Bload" :disabled="Bload" success color="black">Get Data</v-btn>
                        <download-excel :data="AllBranchD" :fields="json_fields" v-show="Bdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                    </v-card>
                </v-flex>
                <v-divider vertical></v-divider>
                <v-flex xs6 sm6>
                    <v-card>
                        <h1>Rider Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <label for="">Rider</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="Rinder.rinder_id">
                                    <option v-for="driver in AllDrivers" :key="driver.id" :value="driver.id">{{ driver.name }}</option>
                                </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Rinder.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Rinder.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-btn flat @click="AllRinderR" :loading="Rload" :disabled="Rload" success color="black">Get Data</v-btn>
                        <download-excel :data="AllRinder" :fields="json_fields" v-show="Rdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                            <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
                                {{ message }}
                                <v-icon dark right>check_circle</v-icon>
                            </v-snackbar>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-layout>

        <!-- </div> -->
    </v-container>
</v-content>
</template>

<script>
export default {
    data() {
        return {
            Allcustomers: [],
            AllDrivers: [],
            statusR: {},
            Client: {},
            Rinder: {},
            AllBranches: [],
            AllStatus: [],
            AllClientR: [],
            AllRinder: [],
            branchR: {},
            AllBranchD: [],
            statuses: [],
            loader: false,
            Sload: false,
            Cload: false,
            Bload: false,
            Rload: false,
            Cdown: false,
            Bdown: false,
            Sdown: false,
            Rdown: false,
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            json_fields: {
                'Order Id': 'order_id',
                'Sender Name': 'sender_name',
                'Sender Email': 'sender_email',
                'Sender Phone': 'sender_phone',
                'Sender City': 'sender_city',
                'Sender Address': 'sender_address',
                'Driver': 'driver',
                'Client Name': 'client_name',
                'Client Email': 'client_email',
                'Client Phone': 'client_phone',
                'Client City': 'client_city',
                'Client Address': 'client_address',
                'Derivery Status': 'status',
                'From': 'sender_address',
                'To': 'client_address',
                'Derivery Date': 'derivery_date',
                'Derivery Time': 'derivery_time',
                'Quantity': 'amount_ordered',
                'COD Amount': 'cod_amount',
                'Booking Date': 'booking_date',
                'Special Instructions': 'speciial_instruction'
            },
        }
    },
    methods: {
        ClientReport() {
            this.Cload = true
            this.AllClientR = []
            axios.post("userDateExpo", this.$data.Client)
                .then(response => {
                    this.Cload = false
                    this.AllClientR = response.data
                    if (this.AllClientR.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Cdown = false
                    } else {
                        this.Cdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                })
                .catch(error => {
                    this.Cload = false
                    this.errors = error.response.data.errors;
                });
        },

        AllStatusR() {
            this.Sload = true
            this.AllStatus = []
            axios.post("displayReport", this.$data.statusR)
                .then(response => {
                    this.Sload = false
                    this.AllStatus = response.data
                    // console.log(response.data)
                    if (this.AllStatus.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Sdown = false
                    } else {
                        this.Sdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                })
                .catch(error => {
                    this.Sload = false
                    this.errors = error.response.data.errors;
                });
        },

        AllRinderR() {
            this.Rload = true
            this.AllRinder = []
            axios.post("DriverReport", this.$data.Rinder)
                .then(response => {
                    this.Rload = false
                    this.AllRinder = response.data
                    if (this.AllRinder.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Rdown = false
                    } else {
                        this.Rdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                })
                .catch(error => {
                    this.Rload = false
                    this.errors = error.response.data.errors;
                });
        },
        AllbranchR() {
            this.Bload = true
            this.AllBranchD = []
            axios.post("branchesExpo", this.$data.branchR)
                .then(response => {
                    this.Bload = false
                    this.AllBranchD = response.data;
                    if (this.AllBranchD.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Bdown = false
                    } else {
                        this.Bdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                        this.AllRinder = response.data
                    }
                })
                .catch(error => {
                    this.Bload = false
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loader = true;

        axios.get("getCustomer")
            .then(response => {
                this.Allcustomers = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });

        axios.get("getDrivers")
            .then(response => {
                this.AllDrivers = response.data;
                this.loader = false;
            })

            .catch(error => {
                this.loader = false;
                this.errors = error.response.data.errors;
            });

        axios.get("getBranch")
            .then(response => {
                this.AllBranches = response.data;
                this.loader = false;
            })

            .catch(error => {
                this.loader = false;
                this.errors = error.response.data.errors;
            })

        axios.get('getStatuses')
            .then((response) => {
                this.statuses = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

    }
}
</script>
