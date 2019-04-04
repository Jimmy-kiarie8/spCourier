<template>
<div>
    <v-dialog v-model="dialog" persistent max-width="1200px" v-if="dialog">
        <v-card>
            <v-card-title>
                <span class="headline">Send mail to Subscribers</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="form.title" color="blue darken-2" label="Title" required></v-text-field>
                            <small class="has-text-danger" v-if="errors.title">{{ errors.title[0] }}</small>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea v-model="form.content" color="blue">
                                <div slot="label">
                                    Content
                                </div>
                            </v-textarea>
                            <small class="has-text-danger" v-if="errors.content">{{ errors.content[0] }}</small>
                        </v-flex>
                        <v-flex xs12 sm4>
                            <!-- <v-card>
                                <v-btn color="red" darken-1 raised @click="onPickFile" style="color: #fff;">Upload</v-btn>
                                <input type="file" @change="getFile" style="display: none" ref="fileInput">
                            </v-card> -->
                            <div class="container">
                                <div class="large-12 medium-12 small-12 filezone">
                                    <input type="file" id="files" ref="files" multiple v-on:change="handleFiles()"/>
                                    <p>
                                        Drop your files here <br>or click to search
                                    </p>
                                </div>

                                <div v-for="(file, key) in files" class="file-listing">
                                    <img class="preview" v-bind:ref="'preview'+parseInt(key)"/>
                                    {{ file.name }}
                                    <div class="success-container" v-if="file.id > 0">
                                        Success
                                    </div>
                                    <div class="remove-container" v-else>
                                        <a class="remove" v-on:click="removeFile(key)">Remove</a>
                                    </div>
                                </div>

                                <a class="submit-button" v-on:click="submitFiles()" v-show="files.length > 0">Submit</a>
                            </div>

                        </v-flex>

                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn flat @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn :disabled="loading" :loading="loading" flat color="primary" @click="upload">Send Mail</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
export default {
  data() {
    const defaultForm = Object.freeze({
      title: "",
      content: ""
    });
    return {
      files: [],
      upload_files: [],
      dialog: false,
      loading: false,
      defaultForm,
      errors: {},
      form: Object.assign({}, defaultForm)
    };
  },
  methods: {
    handleFiles() {
      let uploadedFiles = this.$refs.files.files;

      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
      this.getImagePreviews();
    },
    getImagePreviews() {
      for (let i = 0; i < this.files.length; i++) {
        if (/\.(jpe?g|png|gif)$/i.test(this.files[i].name)) {
          let reader = new FileReader();
          reader.addEventListener(
            "load",
            function() {
              this.$refs["preview" + parseInt(i)][0].src = reader.result;
            }.bind(this),
            false
          );
          reader.readAsDataURL(this.files[i]);
        } else if (/\.(csv|xls)$/i.test(this.files[i].name)) {
          this.$nextTick(function() {
            this.$refs["preview" + parseInt(i)][0].src = "/storage/img/csv.png";
          });
        } else if (/\.(pdf)$/i.test(this.files[i].name)) {
          this.$nextTick(function() {
            this.$refs["preview" + parseInt(i)][0].src = "/storage/img/pdf.png";
          });
        } else {
          this.$nextTick(function() {
            this.$refs["preview" + parseInt(i)][0].src =
              "/storage/img/file.png";
          });
        }
      }
    },
    removeFile(key) {
      this.files.splice(key, 1);
      this.getImagePreviews();
    },
    submitFiles() {
      for (let i = 0; i < this.files.length; i++) {
        if (this.files[i].id) {
          continue;
        }
        let formData = new FormData();
        formData.append("file", this.files[i]);

        axios
          .post("/fileUpload", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(
            function(data) {
              console.log(this.files.length, i);
              console.log(data);
              this.files[i].id = data["data"]["id"];
              this.files.splice(i, 1, this.files[i]);
              this.upload_files.push(data.data);
              if (this.files.length === i + 1) {
                // alert('finish')

                this.sendmail();
              }

              // console.log('success');
            }.bind(this)
          )
          .catch(function(data) {
            console.log("error");
          })
          .then(response => {
            // alert('daata')
            // this.sendmail()
            // console.log(response);
          });
      }
    },

    sendmail() {
      this.form.file_up = this.upload_files;
      this.loading = true;
      axios
        .post("/mails", this.form)
        .then(response => {
          this.loading = false;
          this.color = "black";
          this.message = "Sent";
          this.snackbar = true;
          this.files = [];
          // this.close();
        })
        .catch(error => {
          this.loading = false;
          this.color = "red";
          this.message = "Something went wrong";
          this.snackbar = true;
          // this.errors = error.response.data.errors;
        });
    },

    upload() {
      this.loading = true;
      axios
        .post("/fileUpload", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          this.loading = false;
          console.log(response.data);
          // this.sendmail(response.data)
        })
        .catch(error => {
          this.loading = false;
          // this.errors = error.response.data.errors;
          console.log(error);
        });
    },
    close() {
      this.dialog = false;
    }
  },
  created() {
    eventBus.$on("sendEmailEvent", data => {
      this.dialog = true;
    });
  }
};
</script>

<style scoped>
input[type="file"] {
  opacity: 0;
  width: 100%;
  height: 200px;
  position: absolute;
  cursor: pointer;
}

.filezone {
  outline: 2px dashed grey;
  outline-offset: -10px;
  background: #ccc;
  color: dimgray;
  padding: 10px 10px;
  min-height: 200px;
  position: relative;
  cursor: pointer;
}

.filezone:hover {
  background: #c0c0c0;
}

.filezone p {
  font-size: 1.2em;
  text-align: center;
  padding: 50px 50px 50px 50px;
}

div.file-listing img {
  max-width: 90%;
}

div.file-listing {
  margin: auto;
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

div.file-listing img {
  height: 100px;
}

div.success-container {
  text-align: center;
  color: green;
}

div.remove-container {
  text-align: center;
}

div.remove-container a {
  color: red;
  cursor: pointer;
}

a.submit-button {
  display: block;
  margin: auto;
  text-align: center;
  width: 200px;
  padding: 10px;
  text-transform: uppercase;
  background-color: #ccc;
  color: white;
  font-weight: bold;
  margin-top: 20px;
}
</style>
