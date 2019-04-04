<template>
<div>
    <v-app id="inspire">
        <!-- temporary -->
        <vue-topprogress ref="topProgress"></vue-topprogress>
        <v-navigation-drawer right temporary v-model="right" fixed></v-navigation-drawer>
        <!-- temporary -->
        <v-navigation-drawer fixed :color="color" :clipped="$vuetify.breakpoint.lgAndUp" app v-model="drawer">
            <v-list dense id="navigation">
                <v-img :aspect-ratio="16/9" src="/storage/ps/landS.jpg">
                    <v-layout pa-2 column fill-height class="lightbox white--text">
                        <v-spacer></v-spacer>
                        <v-flex shrink>
                            <div class="subheading">{{ user.name }}</div>
                            <div class="body-1">{{ user.email }}</div>
                        </v-flex>
                    </v-layout>
                </v-img>
                <template>
                    <v-card>
                        <!-- <v-card style="background: url('storage/ps/landS.jpg')"> -->
                        <router-link to="/" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">dashboard</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Dashboard</div>
                            </div>
                        </router-link>

                        <router-link to="/rinders" class="v-list__tile v-list__tile--link" v-for="roleR in user.roles" :key="roleR.id" v-if="roleR.name === 'Rider'">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">local_shipping</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">My Shipments</div>
                            </div>
                        </router-link>
                        <router-link to="/Shipments" class="v-list__tile v-list__tile--link" v-if="user.can['view shipments']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">local_shipping</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Manage Shipments</div>
                            </div>
                        </router-link>
                        <router-link to="/charges" class="v-list__tile v-list__tile--link" v-if="user.can['view charges']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">attach_money</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Manage Charges</div>
                            </div>
                        </router-link>

                        <router-link to="/subscribers" class="v-list__tile v-list__tile--link" v-if="user.can['view subscribers']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">email</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Subscribers</div>
                            </div>
                        </router-link>
                        <router-link to="/mails" class="v-list__tile v-list__tile--link" v-if="user.can['view subscribers']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">email</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Emails</div>
                            </div>
                        </router-link>

                        <router-link to="/profile" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">account_circle</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">My Profile</div>
                            </div>
                        </router-link>
                        <!--  -->
                        <!--  -->
                        <!--  -->
                        <router-link to="/reports" class="v-list__tile v-list__tile--link" v-if="user.can['view reports']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">book</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Reports</div>
                            </div>
                        </router-link>

                        <!--  -->

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
                                <v-list-tile-title>Manage Roles</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="book" v-if="user.can['view logs']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Logs</v-list-tile-title>
                            </v-list-tile>
                            <router-link to="/logs" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>business</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Logs</v-list-tile-title>
                            </router-link>
                        </v-list-group>
                    </v-card>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar dark app :color="color" :clipped-left="$vuetify.breakpoint.lgAndUp" fixed>
            <v-toolbar-title style="width: 600px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>Mail App
                <img
            src="/storage/logo.png"
            alt
            style="margin-left: 10;"
          >
        </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-divider vertical></v-divider>
                <Notifications :user="user"></Notifications>
                <v-divider vertical></v-divider>
            <Logout :user="user"></Logout>
        </v-toolbar>
    </v-app>

    <v-snackbar :timeout="timeout" top="top" :color="snackcolor" right="right" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import Notifications from "../notification/Notification";
import { vueTopprogress } from "vue-top-progress";
import Logout from "./Logout";
// import chattyNoty from '../notification/chattyNoty'
export default {
  components: {
    Notifications,
    vueTopprogress,
    Logout 
    //  chattyNoty
  },
  props: ["user"],
  data() {
    return {
      role: "",
      color: "#132f51",
      dialog: false,
      snackcolor: '',
      drawer: true,
      drawerRight: false,
      right: null,
      mode: "",
      notifications: [],
      company: {},
      snackbar: false,
      timeout: 5000,
      message: "Success"
    };
  },
  methods: {
    close() {
      this.dialog = false;
    },

    showalert() {
      this.message = "success";
      this.snackcolor = 'black'
      this.snackbar = true;
    }
  },
  created() {
    eventBus.$on("progressEvent", data => {
      this.$refs.topProgress.start();
    });
    eventBus.$on("StoprogEvent", data => {
      this.$refs.topProgress.done();
    });
    eventBus.$on("alertRequestEvent", data => {
      this.showalert()
    });
  },
  mounted() {
  }
};
</script>

<style scoped>
.v-expansion-panel__container:hover {
  border-radius: 10px !important;
  width: 90% !important;
  margin-left: 15px !important;
  background: #e3edfe !important;
  color: #1a73e8 !important;
}

.theme--light {
  background-color: #212120 !important;
  /* background: url('storage/logo1.jpg') !important; */
  color: #fff !important;
}
</style>
