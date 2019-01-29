<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <v-tooltip right>
                        <v-btn icon slot="activator" class="mx-0" @click="schedulepct">
                            <v-icon color="blue darken-2" small>refresh</v-icon>
                        </v-btn>
                        <span>Refresh</span>
                    </v-tooltip>
                    <div class="card card-chart">
                        <div class="card-body">
                            <div class="chart-area">
                                <my-branch :height="255"></my-branch>
                            </div>
                            <div class="progress-Ship">
                                <div v-for="user in AllSc" :key="user.id">
                                    {{ user.name }}: {{ user.count }} %
                                    <v-progress-linear color="indigo" height="2" :value="user.count"></v-progress-linear>
                                </div>
                            </div>
                        </div>
                    </div>
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
            AllSc: [],
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
        schedulepct() {
            this.loader = true
            axios.get('/schedulepct')
                .then((response) => {
                    this.AllSc = response.data
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
        this.schedulepct()
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
