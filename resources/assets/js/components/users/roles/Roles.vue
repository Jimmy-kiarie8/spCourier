<template>
<div>
    <v-content>
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card-title>
                        Users
                        <v-btn slot="activator" color="primary" dark @click="openAdd">Add Role</v-btn>
                        <v-tooltip right>
                            <v-btn icon slot="activator" class="mx-0" @click="getRoles">
                                <v-icon color="blue darken-2" small>refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line></v-text-field>
                    </v-card-title>
                </div>
            </v-layout>
        </v-container>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>
        <div class="container col-md-8">
            <table class="table table-hover table-striped" style="margin-top: -70px !important;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role, key in AllRoles" :key="role.id">
                        <th scope="row">{{ key+1 }}</th>
                        <td>{{ role.name }}</td>
                        <td>{{ role.description }}</td>
                        <td>
                            <v-btn icon class="mx-0" @click="openEdit(role)">
                                <v-icon small color="blue darken-2">edit</v-icon>
                            </v-btn>
                            <v-btn icon class="mx-0" @click="deleteItem(role.id)">
                                <v-icon small color="pink darken-2">delete</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left='left' v-model="snackbar">
            {{ message }}
            <v-icon dark right>check_circle</v-icon>
        </v-snackbar>
    </v-content>
    <AddRole @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></AddRole>
    <!--  <ShowRole @closeRequest="close" :openShowRequest="dispShow"></ShowRole> -->
    <EditRole @closeRequest="close" :openEditRequest="dispEdit" @alertRequest="showAlert" :form="editedItem" :userPerm="userPerm"></EditRole>
</div>
</template>

<script>
let AddRole = require('./AddRole.vue')
// // let ShowRole = require('./ShowRole.vue')
let EditRole = require('./EditRole.vue')
export default {
    props: ['user', 'role'],
    components: {
        AddRole,
        // ShowRole,
        EditRole
    },
    data() {
        return {
            headers: [{
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Phone Number",
                    value: "phone"
                },
                {
                    text: "City",
                    value: "city"
                },
                {
                    text: "Branch",
                    value: "branch"
                },
                {
                    text: "Status",
                    value: "status"
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            search: '',
            loader: false,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            snackbar: false,
            loading: false,
            timeout: 5000,
            color: 'black',
            message: 'Success',
            AllRoles: [],
            userPerm: [],
            temp: '',
            editedItem: {},
        }
    },
    methods: {
        openShow(key) {
            // this.$children[4].list = this.company[key]
            this.$children[2].list = this.AllRoles[key]
            this.dispShow = true
        },
        openAdd() {
            this.dispAdd = true
        },
        openEdit(item) {
            console.log(item)
            this.editedIndex = this.AllRoles.indexOf(item)
            this.editedItem = Object.assign({}, item)
            // axios.post('getPerms')
            // .then((response) => {
            //     this.userPerm = response.data
            // })
            // .catch((error) => {
            //     this.errors = error.response.data.errors
            // })
            axios.post(`getRolesPerm/${item.id}`, item)
            .then((response) => {
                eventBus.$emit('RolepermEvent', response.data);
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
            this.dispEdit = true
        },
        showAlert() {
            this.message = 'Successifully Added';
            this.snackbar = true;
            this.color = 'black';
        },
        sort() {
            this.loading = true
            axios.post('getSorted', this.select)
                .then((response) => {
                    this.loading = false
                    this.AllRoles = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },

        deleteItem(item) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.message = 'Deleting...'
                this.color = 'indigo'
                this.snackbar = true
                // this.timeout = 20000
                this.color = 'indigo'
                axios.delete(`/roles/${item}`)
                    .then((response) => {
                        this.snackbar = false
                        this.AllRoles.splice(index, 1)
                        this.message = 'deleted successifully'
                        this.color = 'red'
                        this.snackbar = true
                    })
                    .catch((error) => {
                        this.snackbar = false
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                        this.errors = error.response.data.errors
                    })
            }
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = false
        },
        getRoles() {
            axios.get('getRoles')
                .then((response) => {
                    this.loader = false
                    this.AllRoles = response.data
                })
                .catch((error) => {
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        }
    },
    mounted() {
        this.loader = true
        this.getRoles()
    },
    // beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //         if (vm.role === 'Admin' || vm.role === 'companyAdmin') {
    //             next();
    //         } else {
    //             next('/unauthorized');
    //         }
    //     })
    // }
}
</script>

<style>
.container {
    margin-top: -30px;
}
</style>
