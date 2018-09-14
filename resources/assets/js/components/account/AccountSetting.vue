<template>
<div>
    <div>
        <v-toolbar color="indigo" dark tabs>
            <v-tabs v-model="tab" color="indigo" align-with-title>
                <v-tabs-slider color="yellow"></v-tabs-slider>

                <v-tab v-for="item in items" :key="item">
                    {{ item }}
                </v-tab>
            </v-tabs>
        </v-toolbar>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <v-btn color="primary" flat @click="openUser">Add Loan</v-btn>
                <ShowLoan :openRequest="dispClient" :client="editedItem"></ShowLoan>
            </v-tab-item>

            <v-tab-item>
                <v-card-title>
                    <!-- <v-btn color="primary" flat @click="openUser">Add Client</v-btn> -->
                    <v-spacer></v-spacer>
                    <v-text-field v-model="searchArchived" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="Refheaders" :items="AllReferres" :search="search" counter class="elevation-1">
                    <template slot="items" slot-scope="props">
                        <td>
                            {{ props.item.client_id }}
                        </td>
                        <td class="text-xs-right">{{ props.item.amount }}</td>
                        <td class="text-xs-right">{{ props.item.id_no }}</td>
                        <td class="text-xs-right">{{ props.item.amount }}</td>
                        <td class="text-xs-right">{{ props.item.loan_id }}</td>
                        <td class="justify-center layout px-0">
                            <v-btn icon class="mx-0" @click="refresh(props.item)">
                                <v-icon color="blue darken-2">refresh</v-icon>
                            </v-btn>

                        </td>
                    </template>
                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                    <template slot="pageText" slot-scope="{ pageStart, pageStop }">
                        From {{ pageStart }} to {{ pageStop }}
                    </template>
                </v-data-table>
            </v-tab-item>
        </v-tabs-items>

    </div>
    <div class="text-xs-center mt-3">
        <v-btn @click="next">next tab</v-btn>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            items: [
                'Details', 'Referees'
            ],
            active: null,
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
        }
    },
    methods: {
        next() {
            const active = parseInt(this.active)
            this.active = (active < 2 ? active + 1 : 0)
        }
    }
}
</script>
