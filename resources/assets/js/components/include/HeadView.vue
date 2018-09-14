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

                        <a href="http://courier.dev/courier" class="v-list__tile v-list__tile--link">

                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">home</i></div>

                            <div class="v-list__tile__content">

                                <div class="v-list__tile__title">

                                    Dashboard

                                </div>

                            </div>

                        </a>
                        
                        <a href="http://courier.dev/csv" class="v-list__tile v-list__tile--link">

                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">info</i></div>

                            <div class="v-list__tile__content">

                                <div class="v-list__tile__title">

                                    Upload Excel

                                </div>

                            </div>

                        </a>

                        <a href="http://courier.dev/shipreports" class="v-list__tile v-list__tile--link">

                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">info</i></div>

                            <div class="v-list__tile__content">

                                <div class="v-list__tile__title">

                                    Reports

                                </div>

                            </div>

                        </a>

                    </v-card>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar dark app :color="color" :clipped-left="$vuetify.breakpoint.lgAndUp" fixed>
            <v-toolbar-title style="width: 600px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                {{ user.name }}
                <img :src="user.profile" alt="" style="width: 60px; height: 60px; border-radius: 50%;">
            </v-toolbar-title>
            <v-spacer></v-spacer>

            <v-icon @click.stop="right = !right" style="cursor: pointer">apps</v-icon>
            <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
        </v-toolbar>
    </v-app>
</div>
</template>

<script>
export default {

    props: ['user'],

    data() {

        return {

            items: [{

                    color: 'red',

                    text: 'Red'

                },

                {

                    color: 'green',

                    text: 'Green'

                },

                {

                    color: 'grey',

                    text: 'grey'

                },

                {

                    color: 'orange',

                    text: 'Orange'

                },

                {

                    color: 'purple',

                    text: 'purple'

                },

                {

                    color: 'blue',

                    text: 'blue'

                },

                {

                    color: 'indigo',

                    text: 'indigo'

                },

                {

                    color: 'pink',

                    text: 'pink'

                },

                {

                    color: 'cyan',

                    text: 'cyan'

                },

            ],

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
