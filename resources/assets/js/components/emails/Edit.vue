sc<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add User</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap v-show="!loader">
                        <v-form ref="form" @submit.prevent="submit">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="subscribers.name" color="purple darken-2" label="Full name" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
                                    </v-flex>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="subscribers.email" color="blue darken-2" label="Email" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small> -->
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="loading" flat color="primary" @click="update" :loading="loading">Submit</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-layout>
                    <div v-show="loader" style="text-align: center">
                        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                    </div>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
  data() {
    return {
      e1: true,
      loader: false,
      loading: false,
      subscribers: [],
      dialog: false,
    };
  },
  methods: {
    update() {
      this.loading = true;
      axios
        .patch(`/updateSubscribers/${this.subscribers.id}`, this.subscribers)
        .then(response => {
          // console.log(response);
          this.loading = false;
          eventBus.$emit("alertRequestEvent");
          this.close();
          Object.assign(
            this.$parent.AllSubscribers[this.$parent.editedIndex],
            this.$parent.editedItem
          );
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          this.loading = false;
        });
    },
    resetForm() {
      this.form = Object.assign({}, this.defaultForm);
      this.$refs.form.reset();
    },
    close() {
        this.dialog = false
    }
  },
  created() {
    eventBus.$on("editSubscribersEvent", data => {
      this.subscribers = data;
      this.dialog = true
    });
  }
};
</script>
