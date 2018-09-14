<template>
<v-layout row justify-center>
    <v-dialog v-model="EditShipment" persistent>
        <v-card v-if="EditShipment">
            <v-card-title>
                Add Shipment
            </v-card-title>
            <v-container grid-list-md v-show="!loader">
                <v-card-text>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent="submit">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <!-- <div v-for="client in Allcustomer" :key="client.id" v-if="client.id = selectC.id"> -->

                                    <!--  -->
                                    <v-flex sm6>
                                        <h3><b>Pickup At</b></h3>
                                        <v-divider></v-divider>
                                        <div v-if="role === 'Customer'">
                                            <v-layout wrap row>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="role" :rules="rules.name" color="blue darken-2" label="Client name" required></v-text-field>
                                                </v-flex>
                                                <!-- </div> -->
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="user.email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="user.address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="user.city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="user.phone" color="blue darken-2" label="Client Phone" required></v-text-field>
                                                </v-flex>

                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="user.country" :rules="rules.name" color="blue darken-2" label="Country" required></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                        <div v-else>
                                            <v-layout wrap row>
                                                <v-flex xs6 sm6>
                                                    <v-select :items="Allcustomer" v-model="selectCl" label="Select Client" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="selectCl.email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="selectCl.address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="selectCl.city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="selectCl.phone" color="blue darken-2" label="Client Phone" required></v-text-field>
                                                </v-flex>

                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="selectCl.country" :rules="rules.name" color="blue darken-2" label="Country" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 sm6>
                                                    <v-text-field v-model="form.from_city" :rules="rules.name" color="blue darken-2" label="From" required></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                    </v-flex>
                                    <v-flex sm6>
                                        <h3><b>Deriver To</b></h3>
                                        <v-divider></v-divider>
                                        <v-layout wrap>
                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.client_name" :rules="rules.name" color="blue darken-2" label="Client name" required></v-text-field>
                                            </v-flex>
                                            <!-- </div> -->
                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.client_email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.client_address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.client_city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.client_phone" color="blue darken-2" label="Client Phone" required></v-text-field>
                                            </v-flex>

                                            <v-flex xs6 sm6>
                                                <v-text-field v-model="form.to_city" :rules="rules.name" color="blue darken-2" label="To" required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>

                                    <!-- Details -->
                                    <v-layout wrap>
                                        <v-flex sm12>
                                            <h3><b>Parcel Details</b></h3>
                                            <v-divider></v-divider>
                                            <v-layout wrap>

                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Product Description</th>
                                                            <th scope="col"># of Pieces</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Total Weight</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="product, key in form.products">
                                                            <th scope="row">{{ key+1 }}</th>
                                                            <td><input type="text" class="form-control" placeholder="Product Description" v-model="product.product_name"></td>
                                                            <td><input type="text" class="form-control" placeholder="# of Pieces" v-model="product.product_name"></td>
                                                            <td><input type="text" class="form-control" placeholder="Price" v-model="product.price"></td>
                                                            <td><input type="text" class="form-control" placeholder="Quantity" v-model="product.quantity"></td>
                                                            <td><input type="text" class="form-control" placeholder="Total Weight" v-model="product.weight"></td>
                                                            <td>
                                                                <v-btn @click="remove(product)" icon class="mx-0">
                                                                    <v-icon color="pink darken-2" small>delete</v-icon>
                                                                </v-btn>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <v-divider></v-divider>

                                                <v-flex xs12 sm12>
                                                    <v-text-field v-model="subTotal" color="blue darken-2" label="Price" disabled></v-text-field>
                                                </v-flex>
                                                <v-btn color="primary" flat @click="add_product">Add product</v-btn>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                    <!-- Details -->

                                    <v-flex xs12 sm12>
                                        <v-layout wrap>
                                            <v-divider></v-divider>

                                            <v-flex sm4>
                                                <h3><b>Service Type</b></h3>
                                                <v-divider></v-divider>
                                                <v-layout wrap>
                                                    <select class="custom-select custom-select-md col-md-12" v-model="form.shipment_type">
                                                        <option value="yes">Type A</option>
                                                        <option value="no">Type B</option>
                                                    </select>

                                                    <div class="form-group col-md-6">
                                                        <label for="insurance" class="col-md-4 col-form-label text-md-right">Insuarance</label>
                                                        <select class="custom-select" v-model="form.insuarance_status">
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                        </select>
                                                    </div>
                                                    <!-- </div>  -->
                                                    <div class="form-group col-md-4">
                                                        <label for="payment" class="col-md-4 col-form-label text-md-right">Paid</label>
                                                        <select class="custom-select" v-model="form.payment">
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                        </select>
                                                    </div>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex sm4>
                                                <h3><b>Package Type</b></h3>
                                                <v-divider></v-divider>
                                                <v-layout wrap>
                                                    <select class="custom-select custom-select-md col-md-12" v-model="form.package_type">
                                                        <option value="yes">Type A</option>
                                                        <option value="no">Type B</option>
                                                    </select>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex sm4>
                                                <h3><b>Special Instructions</b></h3>
                                                <v-divider></v-divider>
                                                <v-layout wrap>
                                                    <v-flex xs12 sm12>

                                                        <v-textarea v-model="form.remark" color="blue">
                                                            <div slot="label">
                                                                Instructions
                                                            </div>
                                                        </v-textarea>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>

                                            <v-flex xs12 sm4>
                                                <v-select :items="Allcustomer" v-model="selectC" :hint="`${selectC.name}`" label="Select Client" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm4>
                                                <v-select :items="AllDrivers" v-model="selectD" :hint="`${select.name}`" label="Select Driver" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm4>
                                                <v-select :items="AllBranches" v-model="selectB" :hint="`${selectB.branch_name}`" label="Select Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                                            </v-flex>

                                            
                                            <v-flex xs12 sm4>
                                                <v-text-field v-model="form.booking_date" :type="'date'" color="blue darken-2" label="Booking Date" required></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm4>
                                                <v-text-field v-model="form.derivery_date" :type="'date'" color="blue darken-2" label="Deriery Date" required></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm4>
                                                <v-text-field v-model="form.derivery_time" :type="'time'" color="blue darken-2" label="Ready Time" required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <!--  -->

                                </v-layout>
                                <div class="row">

                                </div>
                                <v-flex xs6 sm6>
                                    <v-text-field v-model="form.bar_code" color="blue darken-2" label="Barcode" required></v-text-field>
                                </v-flex>
                                <barcode v-bind:value="form.bar_code"></barcode>
                            </v-container>
                        </v-form>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="save" :loading="loading" :disabled="loading">Add Shipment</v-btn>
                </v-card-actions>
            </v-container>
            <div v-show="loader" style="text-align: center">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    props: ['EditShipment', 'customers', 'form', 'role'],
    components: {
        barcode: VueBarcode
    },
    data() {
        return {
            selectCl: [],
            AllDrivers: [],
            Allcustomer: [],
            AllBranches: [],
            loading: false,
            selectD: {
                name: 'Select Driver'
            },
            selectC: {
                name: 'Select Client'
            },
            selectB: {
                branch_name: 'Select Name'
            },
            select: [],
            notifications: false,
            list: {},
            loader: false,
            dmodal1: false,
            pdialog2: false,
            dmodal2: false,
            tmodal: false,
            sound: true,
            widgets: false,
            emailRules: [
                v => {
                    return !!v || "E-mail is required";
                },
                v =>
                /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                "E-mail must be valid"
            ],
            rules: {
                name: [val => (val || "").length > 0 || "This field is required"]
            },
            items: [{
                    state: "Yes",
                    abbr: "yes"
                },
                {
                    state: "No",
                    abbr: "no"
                }
            ]
        }
    },
    methods: {
        save() {
            this.loading = true;
            axios
                .post(`/shipment/${this.form.id}`, {
                    form: this.$data.form,
                    selectD: this.$data.selectD,
                    selectC: this.$data.selectC,
                    selectB: this.$data.selectB,
                    user: this.user,
                    selectCl: this.$data.selectCl
                })
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    this.$emit('alertRequest');
                    // this.$emit('closeRequest');
                    this.$parent.AllShipments.push(response.data);
                    // this.$emit('closeRequest');
                    this.resetForm;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        },
        add_product() {
            this.form.products.push({
                product_name: '',
                weight: '',
                quantity: 1,
                price: 0,
            })
        },
        remove(product) {
            const index = this.form.products.indexOf(product)
            this.form.products.splice(index, 1)
        }
    },
    computed: {
        subTotal: function () {
            return this.form.products.reduce(function (carry, product) {
                return carry + parseFloat(product.price);
            }, 0);
        },
        vat: function() {
            return this.grandTotal * parseFloat(0.16);
            // (this.subTotal - parseFloat(this.form.discount)) * parseFloat(0.16);
        },
        grandTotal: function() {
            return this.subTotal - parseFloat(this.form.discount);
        },
    },
}
</script>
