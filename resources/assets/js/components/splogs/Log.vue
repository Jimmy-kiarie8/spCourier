<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div v-show="!loader">
                    <v-card-title>
                        Logs
                        <v-tooltip right>
                            <v-btn icon slot="activator" class="mx-0" @click="getCalls">
                                <v-icon color="blue darken-2" small>refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <!-- <v-btn color="primary" raised @click="getCalls">Calls</v-btn> -->
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllCalls" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.user_id }}</td>
                            <td class="text-xs-right">{{ props.item.user_name }}</td>
                            <td class="text-xs-right">{{ props.item.event }}</td>
                            <td class="text-xs-right">{{ props.item.shipment.airway_bill_no }}</td>
                            <td class="text-xs-right">{{ props.item.shipment.updated_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="details(props.item)" slot="activator">
                                        <v-icon color="info darken-2" small>visibility</v-icon>
                                    </v-btn>
                                    <span>Details</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </div>
            </v-layout>
        </v-container>
    </v-content>
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
    <show></show>
</div>
</template>

<script>
import show from './Show'
export default {
    props: ['user'],
    components: {
        show
    },
    data() {
        return {
            search: '',
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            y: 'bottom',
            x: 'left',
            AllCalls: [],
            loader: false,
            editedItem: {},
            loading: false,
            headers: [{
                    text: 'User Id',
                    align: 'left',
                    value: 'user_id'
                },
                {
                    text: 'User Name',
                    align: 'left',
                    value: 'user_name'
                },
                {
                    text: 'Event',
                    value: 'event'
                },
                {
                    text: 'Waybill Number',
                    value: 'bar_code'
                },
                {
                    text: 'Updated at',
                    value: 'updated_at'
                },
                {
                    text: 'Action',
                    sortable: false
                },
            ],
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        }
    },
    methods: {
        details(item) {
            eventBus.$emit('ShowShipEvent', item);
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        openModalAdd() {
            this.OpenAdd = true
        },
        openModalMail() {
            this.maildialog = true
        },
        openModalUns() {
            this.getunsubscribed()
            this.OpenUns = true
        },
        alert() {
            this.message = 'Thanks for subscribing'
            this.color = 'black'
            this.snackbar = true
        },
        close() {
            this.OpenAdd = this.OpenUns = this.maildialog = false
        },
        deleteItem(item) {
            this.message = 'Loading...'
            this.color = 'black'
            this.snackbar = true
            const index = this.AllCalls.indexOf(item)
            if (confirm('Are you sure you want to delete this item?')) {
                axios.delete(`/email/${item.id}`)
                    .then((response) => {
                        this.snackbar = false
                        this.message = 'Success'
                        this.color = 'black'
                        this.snackbar = true
                        this.AllCalls.splice(index, 1)
                        // this.calls()
                        // console.log(response);

                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                    })
            }
            // confirm('Are you sure you want to delete this item?') && this.AllCalls.splice(index, 1)
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        getCalls() {
            axios.get('/calls')
                .then((response) => {
                    this.AllCalls = response.data
                    this.loader = false
                })
                .catch((error) => {
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.loader = true
        this.getCalls()
    },
    computed: {
        formIsValid() {
            return (
                this.form.title &&
                this.form.content
            )
        },
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view logs']) {
                next();
            } else {
                next('/unauthorized');
            }
        })
    }
}
</script>
