<template>
<v-layout row justify-center>
    <v-dialog v-model="openEditRequest" persistent max-width="500px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Role</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent>
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="form.name" :rules="rules.name" color="purple darken-2" label="Full name" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
                                    </v-flex>
                                    <v-flex xs12 sm12>
                                        <v-textarea v-model="form.description" color="blue">
                                            <div slot="label">
                                                Description
                                            </div>
                                        </v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="loading" flat color="primary" @click="save" :loading="loading">Submit</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openEditRequest', 'form'],
    data() {
        return {
            loading: false,
            loader: false,
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.patch(`/roles/${this.form.id}`, this.form).
            then((response) => {
                    this.loading = false
                    console.log(response);
                    this.$emit('alertRequest');
                    Object.assign(this.$parent.AllRoles[this.$parent.editedIndex], this.$parent.editedItem)
                    this.$emit('closeRequest');

                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        close() {
            this.$emit('closeRequest')
        },
    },
}
</script>
