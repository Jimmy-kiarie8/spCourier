<template>
<v-layout row justify-center>
    <v-dialog v-model="openShowRequest" persistent max-width="500px">
        <v-card v-if="openShowRequest">
            <v-card-title>
                <span class="headline">{{ user.name }} Profile</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <div class="hide-overflow" style="position: relative;">
                        <!-- <v-toolbar absolute color="teal lighten-3" dark scroll-off-screen scroll-target="#scrolling-techniques"> -->
                        <!-- </v-toolbar> -->
                        <img :src="user.profile" :alt="user.name">
                        <div id="scrolling-techniques" class="scroll-y" style="max-height: 600px;">
                            <ul class="list-group">
                                <li class="list-group-item active"><b>Client Details</b></li>
                                <li class="list-group-item"><label for="">Client Name:</label><span style="margin-right: 100px; float: right;">{{ user.name }}</span></li>
                                <li class="list-group-item"><label for="">Client Phone:</label><span style="margin-right: 100px; float: right;">{{ user.phone }}</span></li>
                                <li class="list-group-item"><label for="">Client Email:</label><span style="margin-right: 100px; float: right;">{{ user.email }}</span></li>
                                <li class="list-group-item"><label for="">Client City:</label><span style="margin-right: 100px; float: right;">{{ user.city }}</span></li>
                                <li class="list-group-item"><label for="">Client Address:</label><span style="margin-right: 100px; float: right;">{{ user.address }}</span></li>
                            </ul>

                            <v-layout wrap>
                                <v-flex sm6 v-for="ships in AllShips" :key="ships.id">
                                    <ul class="list-group">
                                        <li class="list-group-item active"><b>Shipment Details {{ ships.bar_code }}</b></li>
                                        <li class="list-group-item"><label for="">WayBill Number:</label><span style="float: right;">{{ ships.bar_code }}</span></li>
                                        <li class="list-group-item"><label for="">Booking Date:</label><span style="float: right;">{{ ships.booking_date }}</span></li>
                                        <li class="list-group-item"><label for="">From:</label><span style="float: right;">{{ ships.from_city }}</span></li>
                                        <li class="list-group-item"><label for="">To:</label><span style="float: right;">{{ ships.to_city }}</span></li>
                                        <li class="list-group-item"><label for="">Status:</label><span style="float: right;">{{ ships.status }}</span></li>
                                    </ul>
                                </v-flex>
                            </v-layout>
                        </div>
                    </div>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" flat @click.native="close">Close</v-btn>
                <v-spacer></v-spacer>
                <!-- <v-btn color="blue darken-1" flat @click.native="dialog = fzalse">Save</v-btn> -->
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['user', 'openShowRequest', 'AllShips'],
    data() {
        return {
            // AllShips: [],
        }
    },
    methods: {
        close() {
            this.$emit('closeRequest')
        }
    },
    mounted() {

    }
}
</script>
