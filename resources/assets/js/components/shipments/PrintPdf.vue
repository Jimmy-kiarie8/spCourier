<template>
<div id="home" class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-body">
                <div class="form-group">
                    <v-layout row>
                        <v-flex xs12 sm5 offset-sm1>
                            <v-text-field v-model="form.start_date" :type="'date'" color="blue darken-2" label="Start Date" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm5 offset-sm1>
                            <v-text-field v-model="form.end_date" :type="'date'" color="blue darken-2" label="End Date" required></v-text-field>
                        </v-flex>
                    </v-layout>
                </div>
                <v-btn raised color="primary" @click="getScheduled" :disabled="loading" :loading="loading">Download PDF</v-btn>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import jsPDF from 'jsPDF'
import VueBarcode from "vue-barcode";

export default {
    name: 'home',
    components: {
        barcode: VueBarcode,
    },
    data() {
        return {
            name: '',
            Scheduled: [],
            form: {},
            errors: [],
            loading: false,
        }
    },
    methods: {
        getScheduled() {
            this.loading = true
            axios.post('getScheduled', this.$data.form)
                .then((response) => {
                    this.loading = false
                    this.Scheduled = response.data
                    this.download()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        download() {
            let pdfName = 'SpeedBall';
            var doc = new jsPDF();
            // doc.text(this.name, 10, 10);
            var doc = new jsPDF();
            var imgData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHkAAABaCAMAAACrHAnjAAAACXBIWXMAAAsSAAALEgHS3X78AAADAFBMVEVHcEz5+vz6+/35+vz5shb///8aR40ZRoz///////83X59FbKoZRozR3/D7+/36+/35+vz5+vwlU5X6+/36+/35+vz7/P36+/36+/35+vxRd7MYRYzv8/n5+vz7/P35+vz6+/1bg71vm9EZRowVQ4r5+vz5+vz5+vz5+vz5+vz6+/1vm9ETOXb7/P35+vz5+vz5+vz///9tmdAZRoxwndL6+/35+vz5+vz5+vz6+/35+vxwndJii8QZRowZRowZRowZRoxwndJumtBwndJwndKqwuJwndIZRoz7/P35+vz5+vz5+vxwndJwndIZRoxhisMSPoZwndISMmQZRowZRowZRoz8/P75+vz///////9wndJumtBwndL4pgcZRowZRowqT5JwndJwndJwndL5+vz5+vz///9wndKqsc1wndJum9Fqlcxpksprl81wndL7zWnw8vdMeLQCNIH5+vxwndJwndL5+vz5+vz5+vwZRoz5shf///9wndJokslnkMhum9FgicJrls1ljsb5rw0MPIZii8Rsl84IOYRtmM/6vDT///5NbKRvnNIUQ4pkjcUWRItjjMR1i7g0XJ0hTpJfh8Behr8XRYtPdbJagbwcSY5UerZKcK1XfrkeTJAsV5n5sRFbg706YqE9ZKMGN4IRP4hqlcwkUJRDaqlpk8owWpshUJNNc683X58oVJcaSI2ptdL5sRRdhb76tSH5rAOsudX/+Onj6PNVfbf5rQglUpVBaKc+Z6ZHbqtSebRZgLqxvtlFbKtpk8tRd7P5shg5YqT//fpahMD8/P51otafs9WHnMT+7sxVgL5YgLlJcrH4+fwvV5dSfbvh5fDM1ejDz+Xa4O59krz80HL+896Xq85yj8Hc4u/p7fSRpcr7w0z//PX6uSxlg7VCY567x97T2ulScqhzlcf09vpOd7b7yV795rX814n6vjtHaaN7mcmmvNz+6sKfr875tB394KT+9eP93Jf803q0xuKpudj83Zv+8deGo85hfK/T2ej6xVJpib6sijL/AAAAeXRSTlMAuMgBPJJJ5PMYSUlMBygUrHlJDItzIqUROUkbAzEbhEVJN+q+weBuaJOfEQt9LeVO+EFdZVQ/stse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'

            for (let i = 0; i < this.Scheduled.length; i++) {
                doc.addImage(imgData, 0, 0, 20, 20);
                doc.setFontSize(12)
                const element = this.Scheduled[i];
                doc.setFont("times");
                doc.setFontType("normal");
                // doc.text(20, 120, 'Back to left');
                doc.text(0, 30, 'SpeedBall Courier Services.');
                doc.text(0, 40, 'Location: ' + element['sender_city']);
                doc.text(0, 50, 'Phone: ' + element['sender_phone']);
                doc.text(0, 60, 'Email: ' + element['sender_email']);

                // doc.text(105, 80, 'This is centred text.', null, null, 'center');
                doc.text(200, 30, 'Booking Date: ' + element['booking_date'], null, null, 'right');
                doc.text(200, 40, 'From: ' + element['sender_address'], null, null, 'right');
                doc.text(200, 50, 'To: ' + element['client_address'], null, null, 'right');
                // doc.text(200, 110, 'And some more', null, null, 'right');

                doc.text(0, 80, 'Sender Details :');
                doc.text(0, 90, 'Name: ' + element['sender_name']);
                doc.text(0, 100, 'Phone: ' + element['sender_phone']);
                doc.text(0, 110, 'Email: ' + element['sender_email']);

                doc.text(105, 80, 'Client Details :', null, null, 'center');
                doc.text(105, 90, 'Name: ' + element['client_name'], null, null, 'center');
                doc.text(105, 100, 'Phone: ' + element['client_phone'], null, null, 'center');
                doc.text(105, 110, 'Email: ' + element['client_email'], null, null, 'center');

                doc.text(200, 80, 'Shipment Details :', null, null, 'right');
                doc.text(200, 90, 'WayBill No: ' + element['bar_code'], null, null, 'right');
                doc.text(200, 100, 'Derivery Date:: ' + element['derivery_date'], null, null, 'right');
                // doc.text(105, 80, 'Client Details :', null, null, 'center');

                doc.text(0, 130, 'Product Description');
                // doc.text(85, 130, 'Weight', null, null, 'center');
                doc.text(120, 130, 'Price', null, null, 'right');
                doc.text(180, 130, 'Quantity', null, null, 'right');
                // doc.text(180, 130, 'Total', null, null, 'right');
                
                // doc.text(200, 80, 'Shipment Details :', null, null, 'right');
                // doc.text(140, 140, element['cod_amount'], null, null, 'right');
                // doc.text(180, 140, element['amount_ordered'], null, null, 'right');
                
                doc.text(0, 140, '' + element['bar_code']);
                // doc.text(85, 140, element['weight'], null, null, 'center');
                doc.text(120, 140,'' +  element['cod_amount'], null, null, 'right');
                doc.text(180, 140, '' + element['amount_ordered'], null, null, 'right');

                // for (let j = 0; j < element.products.length; j++) {
                //     const products = element.products[j];
                //     doc.text(0, 140, products['product_name']);
                //     doc.text(85, 140, products['weight'], null, null, 'center');
                //     doc.text(140, 140, products['price'], null, null, 'right');
                //     doc.text(180, 140, products['quantity'], null, null, 'right');
                //     // doc.text(180, 140, products['quantity'], null, null, 'right');
                // }
                doc.text(0, 150, 'Special Instructions: ' + element['speciial_instruction']);


                doc.text(0, 160, 'NOTE: ');
                doc.text(0, 170, 'Clients are requested to pay through M-PESA TILL NUMBER - 877838 ');
                doc.text(0, 180, '(If asked to pay cash please cal 0728 492 446 or 0799 869 844) ');

                doc.text(0, 200, 'Authorizer Signature____________________________');
                doc.text(200, 200, 'Customer Signature____________________________', null, null, 'right');
                doc.addPage()
            }
            doc.save(pdfName + '.pdf');
        }
    },
    mounted() {},
}
</script>
