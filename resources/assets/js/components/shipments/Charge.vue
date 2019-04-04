<template>
<v-layout row justify-center>
    <v-dialog v-model="mySCharges" persistent width="500px">
        <v-card v-if="mySCharges">
            <v-card-title>
                Charges
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-container fill-height>
                            <v-layout align-center>
                                <v-flex sm12>
                                    <!--   <div class="form-group col-md-12">
                                        <label for="type" class="col-md-4 col-form-label text-md-right">Shipment Type</label>
                                        <select class="custom-select" v-model="form.type">
                                            <option value="OverNight">OverNight</option>
                                            <option value="Distance">Distance Based</option>
                                        </select>
                                    </div> -->
                                    <v-flex sm12>
                                        <!-- <v-checkbox label="OVS" v-model="Stype"></v-checkbox>
                                        <v-checkbox label="Distance Based" v-model="sel.dist"></v-checkbox> -->

                                        <v-radio-group v-model="Stype" :mandatory="false">
                                            <v-radio label="OVS" value="OVS"></v-radio>
                                            <v-radio label="Distance Based" value="dist"></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <div v-if="Stype === 'OVS'">
                                        <v-select :items="AllTowns" v-model="select" label="Select Town" single-line item-text="town_name" item-value="id" return-object persistent-hint></v-select>
                                        <div v-for="charge in select.charges" :key="charge.id">
                                            <v-layout wrap>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.charge" color="blue darken-2" label="Charge" disabled></v-text-field>
                                                </v-flex>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.vat" color="blue darken-2" label="VAT" disabled></v-text-field>
                                                </v-flex>
                                                <v-flex sm4>
                                                    <v-text-field v-model="charge.total" color="blue darken-2" label="Total" disabled></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                    </div>
                                    <div v-if="Stype === 'dist'">
                                        <v-flex sm12>
                                            <v-text-field v-model="form.distance" color="blue darken-2" label="Distance" type="number" required></v-text-field>
                                        </v-flex>
                                        <v-layout wrap>
                                            <v-flex sm4>
                                                <v-text-field v-model="getCharge" color="blue darken-2" label="Charge" type="number" disabled></v-text-field>
                                            </v-flex>
                                            <v-flex sm4>
                                                <v-text-field v-model="getVat" color="blue darken-2" label="VAT" disabled></v-text-field>
                                            </v-flex>
                                            <v-flex sm4>
                                                <v-text-field v-model="gettotal" color="blue darken-2" label="Total" disabled></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="update" :loading="loading" :disabled="loading" color="primary">Update</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ["updateCharges", "mySCharges"],
    data() {
        return {
            AllTowns: [],
            form: {
                type: "",
                distance: "",
                charges: ""
            },
            select: [],
            Stype: "OVS",
            loading: false
        };
    },
    methods: {
        update() {
            this.loading = true;
            axios
                .post(`/shipCharge/${this.updateCharges.id}`, {
                    form: this.form,
                    select: this.select,
                    distance: this.getCharge,
                    Stype: this.Stype
                })
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    this.$emit("alertRequest");
                    Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.$parent.shipment)
                    this.close()
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        }
    },
    computed: {
        getCharge() {
            if (this.form.distance <= 5) {
                return parseInt(200);
            } else {
                return (
                    (parseInt(this.form.distance) - parseInt(5)) * parseInt(25) +
                    parseInt(200)
                );
            }
        },
        getVat() {
            return parseInt(this.getCharge) * parseFloat(0.16);
        },
        gettotal() {
            return parseInt(this.getCharge) + parseFloat(this.getVat);
        }
    },
    mounted() {
        axios
            .get("/getTownCharge")

            .then(response => {
                this.AllTowns = response.data;

                this.loader = false;
            })

            .catch(error => {
                console.log(error);

                this.errors = error.response.data.errors;

                this.loader = false;
            });
    }
};
</script>
