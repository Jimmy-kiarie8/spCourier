<template>
<v-layout row justify-center>
    <v-dialog v-model="openRequest" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">User Profile</span>
                <v-spacer></v-spacer>
                <v-btn flat color="info">Go To Shipments</v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                        <ul class="list-group" v-for="ship in shipment" :key="ship.id">
                            <li class="list-group-item active"><b>Client Details</b></li>
                            <li class="list-group-item"><label for="">Client Name:</label><span style="float: right;">{{ ship.client_name }}</span></li>
                            <li class="list-group-item"><label for="">Client Phone:</label><span style="float: right;">{{ ship.client_phone }}</span></li>
                            <li class="list-group-item"><label for="">Client Email:</label><span style="float: right;">{{ ship.client_email }}</span></li>
                            <li class="list-group-item"><label for="">Client City:</label><span style="float: right;">{{ ship.client_city }}</span></li>
                            <li class="list-group-item active"><b>Sender Details</b></li>
                            <li class="list-group-item"><label for="">Sender Name:</label><span style="float: right;">{{ ship.sender_name }}</span></li>
                            <li class="list-group-item"><label for="">Sender Phone:</label><span style="float: right;">{{ ship.sender_phone }}</span></li>
                            <li class="list-group-item"><label for="">Sender Email:</label><span style="float: right;">{{ ship.sender_email }}</span></li>
                            <li class="list-group-item active"><b>Shipment Details</b></li>
                            <li class="list-group-item"><label for="">WayBill Number:</label><span style="float: right;">{{ ship.bar_code }}</span></li>
                            <li class="list-group-item"><label for="">Booking Date:</label><span style="float: right;">{{ ship.booking_date }}</span></li>
                            <li class="list-group-item"><label for="">From:</label><span style="float: right;">{{ ship.from_city }}</span></li>
                            <li class="list-group-item"><label for="">To:</label><span style="float: right;">{{ ship.to_city }}</span></li>
                            <li class="list-group-item"><label for="">Status:</label><span style="float: right;">{{ ship.status }}</span></li>
                        </ul>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openRequest', 'shipment'],
    data() {
        return {

        }
    },
    methods: {
        close() {
            this.$emit('closeRequest')
        }
    }
}
</script>
