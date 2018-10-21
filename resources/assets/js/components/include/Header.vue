<template>
<div>
    <v-app id="inspire">
        <!-- temporary -->
        <v-navigation-drawer right temporary v-model="right" fixed>
        </v-navigation-drawer>
        <!-- temporary -->
        <v-navigation-drawer fixed :color="color" :clipped="$vuetify.breakpoint.lgAndUp" app v-model="drawer">
            <v-list dense id="navigation">
                <!-- <v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                    <v-layout pa-2 column fill-height class="lightbox white--text">
                        <v-spacer></v-spacer>
                        <v-flex shrink>
                            <div class="subheading">{{ user.name }}</div>
                            <div class="body-1">{{ user.email }}</div>
                        </v-flex>
                    </v-layout>
                </v-img> -->
                <template>
                    <v-card>
                    <!-- <v-card style="background: url('storage/ps/landS.jpg')"> -->
                        <router-link to="/" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">dashboard</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Dashboard
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/Shipments" class="v-list__tile v-list__tile--link" v-if="user.can['view shipments']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">local_shipping</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Shipments
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/charges" class="v-list__tile v-list__tile--link" v-if="user.can['view charges']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">attach_money</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Charges
                                </div>
                            </div>
                        </router-link>
                        <!-- <router-link to="/roles" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">attach_money</i></div>
                            <div class="v-list__tile__content">
                                    <div class="v-list__tile__title">
                                        Manage Roles
                                    </div>
                            </div>
                        </router-link> -->
                        <!-- <v-expansion-panel v-model="panel" expand>
                            <v-expansion-panel-content>
                                <template slot="header">Regular</template>
                                <v-card>
                                    <v-card-text>Some content</v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel> -->

                        <router-link to="/subscribers" class="v-list__tile v-list__tile--link" v-if="user.can['view subscribers']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">email</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Subscribers
                                </div>
                            </div>
                        </router-link>

                        <router-link to="/scanner" class="v-list__tile v-list__tile--link" v-if="user.can['outscan', 'inscan']">
                            <div class="v-list__tile__action"><i class="fa fa-barcode nav_icon"></i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Scan Shipments
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/profile" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">account_circle</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Your Profile
                                </div>
                            </div>
                        </router-link>

                        <!-- <router-link to="/companies" class="v-list__tile v-list__tile--link" v-if="user.can['create users', 'delete users', 'view users']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">home</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Company Profile
                                </div>
                            </div>
                        </router-link> -->
                        <router-link to="/print" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">print</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    print
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/reports" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">book</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Reports
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/customers" class="v-list__tile v-list__tile--link" v-for="role in user.roles" :key="role.id" v-if="role.name === 'Client'">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">print</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    My Shipments
                                </div>
                            </div>
                        </router-link>
                        <v-list-group prepend-icon="account_circle" v-if="user.can['view users']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>User&Roles</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/users" class="v-list__tile theme--light" style="text-decoration: none" id="link-router">
                                    <v-list-tile-action>
                                        <v-icon>people_outline</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-title>Manage Users</v-list-tile-title>
                            </router-link>
                            <router-link to="/roles" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>gavel</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title> Manage Roles</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="insert_drive_file" v-if="user.can['view branches']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Manage Branches</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/branches" class="v-list__tile theme--light" style="text-decoration: none">
                                    <v-list-tile-action>
                                        <v-icon>book</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-title>Branches</v-list-tile-title>
                            </router-link>
                            <router-link to="/status" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>question_answer</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title> Shipment status</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                    </v-card>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar dark app :color="color" :clipped-left="$vuetify.breakpoint.lgAndUp" fixed>
            <v-toolbar-title style="width: 600px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                SpeedBall Courier
                <img src="storage/logo1.jpg" alt="" style="width: 60px; height: 60px; border-radius: 25%;">
            </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-icon @click.stop="right = !right" style="cursor: pointer">apps</v-icon>
                <form action="/logout" method="post">
                    <v-btn flat color="white" type="submit">Logout</v-btn>
                </form>
                <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
        </v-toolbar>
    </v-app>
</div>
</template>

<script>
export default {
    props: ['user', 'role', 'logo'],
    data() {
        return {
            sheet: false,
            color: '#132f51',
            panel: false,
            dialog: false,
            changeColor: 'item.color',
            drawer: true,
            drawerRight: false,
            right: null,
            menu: false,
            mode: '',
            company: {},
            cruds: [
                ['Create', 'add'],
                ['Read', 'insert_drive_file'],
                ['Update', 'update'],
                ['Delete', 'delete']
            ]
        }
    },
    mounted() {
        axios.post('getLogo')
            .then((response) => {
                this.company = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
}
</script>

<style scoped>
.v-expansion-panel__container:hover {
    border-radius: 10px !important;
    width: 90% !important;
    margin-left: 15px !important;
    background: #e3edfe !important;
    color: #1a73e8 !important;
}
.theme--light{
    background-color: #212120 !important;
    /* background: url('storage/logo1.jpg') !important; */
    color: #fff !important;
}

</style>
