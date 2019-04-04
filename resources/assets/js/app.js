
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import Print from 'vue-print-nb'
// import VueChartkick from 'vue-chartkick'
import Chart from 'chart.js'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@fortawesome/fontawesome-free/css/all.css' // Ensure you are using css-loader
import 'vuetify/dist/vuetify.min.css'
// import VueCharts from 'vue-chartjs'
// import { Bar, Line } from 'vue-chartjs'
import JsonExcel from 'vue-json-excel'
// import VueResource from "vue-resource"
// window.Echo = new Echo({

//     broadcaster: 'pusher',

//     key: 'c0a8d7b1122459cd74c9' //Add your pusher key here

// });

import VueCharts from 'vue-chartjs'
import { Bar, Line } from 'vue-chartjs'
window.eventBus = new Vue()

import jsPDF from 'jsPDF'

Vue.use(Print);
Vue.use(Vuetify)
Vue.use(VueRouter)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// axios.defaults.baseURL = 'http://courier.dev/api/getData';
Vue.component('downloadExcel', JsonExcel)

let myHeader = require('./components/include/Header.vue');
let myUser = require('./components/users/User.vue');
let myProfile = require('./components/users/Profile.vue');
let myCompany = require('./components/company/Company.vue');
let mysubsicriber = require('./components/emails/Subscribe.vue');
let myReports = require('./components/reports/Reports.vue');
let myRoles = require('./components/users/roles/Roles.vue');
let myunauth = require('./components/Unauthorized.vue');
let myMails = require('./components/emails/Emails.vue');

let myDash = require('./components/App.vue');
let myLogs = require('./components/splogs/Log.vue');


const routes = [
    // {path: '/', component: dashboard },
    { path: '/', component: myDash },
    { path: '/users', component: myUser },
    { path: '/profile', component: myProfile },
    { path: '/companies', component: myCompany },
    { path: '/subscribers', component: mysubsicriber },
    { path: '/roles', component: myRoles },
    { path: '/unauthorized', component: myunauth },
    { path: '/logs', component: myLogs },
    { path: '/mails', component: myMails },

]
const router = new VueRouter({
    // mode: 'history',
    routes // short for `routes: routes`
})

const app = new Vue({
    el: '#app',
    router,
    components: {
        myHeader, myUser,
        myProfile, myCompany, mysubsicriber, myMails,
        myReports, myRoles,
        myunauth, myDash, myLogs
        // myContainer
    },
});
