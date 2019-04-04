<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="indigo" style="margin: 1rem"></v-progress-circular>
        </div>

        <!-- Send Email -->
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div v-show="!loader">
                    <v-card-title>
                        Emails
                        <v-tooltip bottom v-if="user.can['view users']">
                            <v-btn icon class="mx-0" @click="getEmails" slot="activator">
                                <v-icon small color="blue darken-2">refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllMails" class="elevation-1" :loading="loading" :search="search">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.title }}</td>
                            <td class="text-xs-right">{{ props.item.content }}</td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="showMail(props.item)" slot="activator">
                                        <v-icon color="primary darken-2" small>visibility</v-icon>
                                    </v-btn>
                                    <span>View</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <download-excel :data="AllMails" :fields="json_fields">
                        Export
                        <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                </div>
            </v-layout>
        </v-container>
    </v-content>
    <Show></Show>
</div>
</template>

<script>
import Show from './Show'
export default {
    props: ["user"],
    components: {Show},

    data() {
        return {
            errors: {},
            search: "",
            color: "black",
            headers: [{
                    text: "Title",
                    align: "left",
                    value: "title"
                },
                {
                    text: "Content",
                    value: "content"
                },
                {
                    text: "Created on",
                    value: "created_at"
                },
                {
                    text: "Actions",
                    value: "name",
                    sortable: false
                }
            ],
            json_fields: {
                'Subject': "title",
                'Email': "content",
                'Sent On': 'created_at'
            },
            AllMails: [],
            loader: false,
            loading: false,
        };
    },
    watch: {
        dialog(val) {
            val || this.close();
        }
    },
    methods: {
        getEmails() {
            this.loading = true
            axios
                .get("/mails")
                .then(response => {
                    this.AllMails = response.data;
                    this.loader = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.loader = false;
                    this.errors = error.response.data.errors;
                });
        },
        showMail(data) {
            eventBus.$emit('emailShowEvent', data)
        }
    },
    mounted() {
        this.loader = true;
        this.getEmails();
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["view subscribers"]) {
                next();
            } else {
                next("/unauthorized");
            }
        });
    }
};
</script>
