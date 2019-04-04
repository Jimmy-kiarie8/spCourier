<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1200px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Emails</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <div class="card">
                        <div class="card-header">
                            <ul class="list-group text-center">
                                <li class="list-group-item active">{{ email.title }}</li>
                                <li class="list-group-item">{{ email.content }}</li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <ul class="list-group text-center">
                                <li class="list-group-item active">Sent to:</li>
                            </ul>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Ceated on</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(subscriber, index) in subscribers" :key="index">
                                        <td>{{ subscriber.name }}</td>
                                        <td>{{ subscriber.email }}</td>
                                        <td>{{ subscriber.created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn flat @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    data() {
        return {
            subscribers: [],
            email: [],
            dialog: false,
        }
    },
    methods: {
        getSubscribers(id) {
            axios.get(`/mails/${id}`).
            then((response) => {
                    this.loading = false
                    this.subscribers = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        close() {
            this.dialog = false
        }
    },

    created() {
        eventBus.$on('emailShowEvent', data => {
            this.dialog = true
            this.email = data
            this.getSubscribers(data.id)
        })
    },
}
</script>
