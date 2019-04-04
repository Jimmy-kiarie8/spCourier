<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <!-- Send Email -->
        
        <!-- Send Email -->
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div v-show="!loader">
                    <v-card-title>
                        Subscribers
                        <v-tooltip bottom v-if="user.can['view users']">
                            <v-btn icon class="mx-0" @click="getsubscribers" slot="activator">
                                <v-icon small color="blue darken-2">refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-btn color="orange" @click="openModalAdd" flat>Add Subscribers</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="warning" flat @click="openModalUns">UnSubscribed</v-btn>
                        <v-btn color="success" flat @click="openModalMail">Send Email</v-btn>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllSubscribers" class="elevation-1" :loading="loading" :search="search">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="deleteItem(props.item)" slot="activator">
                                        <v-icon color="pink darken-2" small>delete</v-icon>
                                    </v-btn>
                                    <span>Unsubscribe</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="editSub(props.item)" slot="activator">
                                        <v-icon color="primary darken-2" small>edit</v-icon>
                                    </v-btn>
                                    <span>Unsubscribe</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <download-excel :data="AllSubscribers" :fields="json_fields">
                        Export
                        <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                </div>
            </v-layout>
        </v-container>
    </v-content>
    <AddSubscribers :openAddRequest="OpenAdd" @closeRequest="close" @alertRequest="alert" :Subscribers='AllSubscribers'>
    </AddSubscribers>
    <Unsubscribed :openUnRequest="OpenUns" @closeRequest="close" @alertRequest="alert" :Unsubscribed='AllUnSubscribed'>
    </Unsubscribed>
    <SendEmails></SendEmails>
    <Edit></Edit>
</div>
</template>

<script>
let AddSubscribers = require("./AddSubscribers");
let Unsubscribed = require("./Unsubscribed");
let SendEmails = require("./SendEmails");
import Edit from "./Edit";
export default {
    props: ["user", "role"],
    components: {
        AddSubscribers,
        Unsubscribed,
        Edit, SendEmails
    },

    data() {
        return {
            OpenAdd: false,
            OpenUns: false,
            maildialog: false,
            Mailloader: false,
            search: "",
            snackbar: false,
            timeout: 5000,
            message: "Success",
            color: "black",
            headers: [{
                    text: "User Name",
                    align: "left",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Subscribed on",
                    value: "created_at"
                },
                {
                    text: "Actions",
                    value: "name",
                    sortable: false
                }
            ],
            json_fields: {
                Name: "name",
                Email: "email"
            },
            AllSubscribers: [],
            AllUnSubscribed: [],
            editedIndex: -1,
            loader: false,
            editedItem: {},
            loading: false,
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
            }
        };
    },
    watch: {
        dialog(val) {
            val || this.close();
        }
    },
    methods: {
        resetForm() {
            this.form = Object.assign({}, this.defaultForm);
            this.$refs.form.reset();
        },
        openModalAdd() {
            this.OpenAdd = true;
        },
        openModalMail() {
            eventBus.$emit("sendEmailEvent");
        },
        openModalUns() {
            this.getunsubscribed();
            this.OpenUns = true;
        },
        editSub(data) {
            eventBus.$emit("editSubscribersEvent", data);
        },
        alert() {
            this.message = "Thanks for subscribing";
            this.color = "black";
            this.snackbar = true;
        },
        close() {
            this.OpenAdd = this.OpenUns = false;
        },
        deleteItem(item) {
            this.loading = true;
            const index = this.AllSubscribers.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/email/${item.id}`)
                    .then(response => {
                        this.loading = false;
                        this.snackbar = false;
                        this.message = "Success";
                        this.color = "black";
                        this.snackbar = true;
                        this.AllSubscribers.splice(index, 1);
                        // this.getsubscribers()
                        // console.log(response);
                    })
                    .catch(error => {
                        this.loading = false;
                        this.errors = error.response.data.errors;
                        this.message = "something went wrong";
                        this.color = "red";
                        this.snackbar = true;
                    });
            }
            // confirm('Are you sure you want to delete this item?') && this.AllSubscribers.splice(index, 1)
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm);
            this.$refs.form.reset();
        },
        getunsubscribed() {
            axios
                .get("/getunsubscribed")
                .then(response => {
                    this.AllUnSubscribed = response.data;
                    this.loader = false;
                })
                .catch(error => {
                    this.loader = false;
                    // this.errors = error.response.data.errors;
                    this.getunsubscribed();
                });
        },
        // refresh() {
        //     this.getunsubscribed()
        //     this.getsubscribers()
        // },
        getsubscribers() {
            this.loading = true;
            axios
                .get("/getsubscribers")
                .then(response => {
                    this.AllSubscribers = response.data;
                    this.loader = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.loader = false;
                    this.getsubscribers();
                    // this.errors = error.response.data.errors;
                });
        },

    },
    mounted() {
        // this.loader = true;
        this.getsubscribers();
        this.getunsubscribed();
    },
    computed: {
        formIsValid() {
            return this.form.title && this.form.content;
        }
    },
    created() {
        eventBus.$on("unsubEvent", data => {
            this.getunsubscribed();
            this.getsubscribers();
        });
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
