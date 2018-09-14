<template>
<div>

    <v-content>

        <v-container fluid fill-height>

            <!-- Data table -->

            <div style="width: 100%;">

                <div v-show="loader" style="text-align: center">

                    <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>

                </div>

                <div v-show="!loader">

                    <v-card-title>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>

                    <v-data-table :headers="headers" :items="AllShipments" :search="search" counter class="elevation-1">

                        <template slot="items" slot-scope="props">
                            <td>
                                {{ props.item.bar_code }}
                            </td>
                            <td class="text-xs-right">
                                <barcode v-bind:value="props.item.bar_code" style="height: 10px;">
                                    No barcode
                                </barcode>
                            </td>
                            <td class="text-xs-right">{{ props.item.client_name }}</td>
                            <td class="text-xs-right">{{ props.item.sender_name }}</td>
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <td class="text-xs-right">{{ props.item.client_email }}</td>
                            <td class="text-xs-right">{{ props.item.client_address }}</td>

                            <td class="text-xs-right">{{ props.item.sender_city }}</td>

                            <td class="text-xs-right">{{ props.item.client_city }}</td>

                            <td class="text-xs-right">{{ props.item.booking_date }}</td>

                            <td class="text-xs-right">{{ props.item.status }}</td>

                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                        <template slot="pageText" slot-scope="{ pageStart, pageStop }">
                            From {{ pageStart }} to {{ pageStop }}
                        </template>
                    </v-data-table>

                </div>
            </div>
            <!-- Data table -->
        </v-container>
    </v-content>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";

export default {
    props: ["user", "role"],

    components: {
        barcode: VueBarcode,
    },

    data() {
        return {
            headers: [{
                    text: "Airwaybill",

                    value: "airway_bill_no"
                },

                {
                    text: "Barcode",

                    value: "bar_code"
                },

                {
                    text: "Client",

                    value: "client_name"
                },

                {
                    text: "From",

                    value: "sender_name"
                },

                {
                    text: "Client Phone",

                    value: "client_phone"
                },

                {
                    text: "Client Email",

                    value: "client_email"
                },

                {
                    text: "Client Address",

                    value: "client_address"
                },

                {
                    text: "Sender City",

                    value: "sender_city"
                },

                {
                    text: "Client City",

                    value: "client_city"
                },

                {
                    text: "Booked on",

                    value: "booking_date"
                },

                {
                    text: "Status",

                    value: "status"
                },
            ],

            AllShipments: [],
            loader: false,
            search: '',
        };
    },

    methods: {
    },

    mounted() {

        this.loader = true;

        axios
            .get("scheduled")

            .then(response => {
                this.AllShipments = response.data;

                this.loader = false;
            })

            .catch(error => {
                this.errors = error.response.data.errors;

                this.loader = false;
            });


    },

    computed: {

    },

    created() {
        this.initialize();
    },

    }
</script>

<style>
/* This is for documentation purposes and will not be needed in your application */
