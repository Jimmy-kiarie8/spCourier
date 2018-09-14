<template>
<div>
    <v-app id="inspire">
        <!-- temporary -->
        <v-navigation-drawer right temporary v-model="right" fixed>
        </v-navigation-drawer>
        <!-- temporary -->
        <v-navigation-drawer fixed :color="color" :clipped="$vuetify.breakpoint.lgAndUp" app v-model="drawer">
            <v-list dense>
                <template>
                    <v-list-group append-icon="" style="background:#f0f0f0">
                        <v-list-tile slot="activator" style="background:#f5f5f5; padding: 0 0 40px 0; margin-top: 10px; padding: 30px 0 0 0;">
                        </v-list-tile>
                    </v-list-group>
                    <v-divider></v-divider>
                    <v-card>
                        <router-link to="/" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">home</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Dashboard
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/Shipments" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">local_shipping</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Shipments
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/charges" class="v-list__tile v-list__tile--link">
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
                        <router-link to="/users" class="v-list__tile v-list__tile--link" v-if="role === 'Admin'">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">account_circle</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Users
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/subscribers" class="v-list__tile v-list__tile--link" v-if="role === 'Admin'">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">email</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Subscribers
                                </div>
                            </div>
                        </router-link>
                        <!-- <router-link to="/customers" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">account_circle</i></div>
                            <div class="v-list__tile__content">
                                    <div class="v-list__tile__title">
                                        Manage Customers
                                    </div>
                            </div>
                        </router-link> -->
                        <router-link to="/scanner" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i class="fa fa-barcode nav_icon"></i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Scan Shipments
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/branches" class="v-list__tile v-list__tile--link" v-if="role === 'Admin'">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">home</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Manage Branches
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
                        <v-expansion-panel popout>
                            <v-expansion-panel-content>
                                <div slot="header">Account Setting</div>
                                <v-card>
                                    <router-link to="/invoices" class="v-list__tile v-list__tile--link">
                                        <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">book</i></div>
                                        <div class="v-list__tile__content">
                                            <div class="v-list__tile__title">
                                                Invoices
                                            </div>
                                        </div>
                                    </router-link>
                                    <router-link to="/receipts" class="v-list__tile v-list__tile--link">
                                        <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">book</i></div>
                                        <div class="v-list__tile__content">
                                            <div class="v-list__tile__title">
                                                Receipt
                                            </div>
                                        </div>
                                    </router-link>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>

                        <router-link to="/profile" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">account_circle</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Your Profile
                                </div>
                            </div>
                        </router-link>

                        <!-- <router-link to="/companies" class="v-list__tile v-list__tile--link" v-if="role === 'Admin'">
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
            color: 'indigo',
            dialog: false,
            changeColor: 'item.color',
            drawer: true,
            drawerRight: false,
            right: null,
            menu: false,
            mode: '',
            company: {},
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
