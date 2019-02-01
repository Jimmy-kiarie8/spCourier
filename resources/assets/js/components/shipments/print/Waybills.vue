<template>
    <div>
        {{ user.country_name }}
        <Tzprint v-if="user.country_name === 'Tanzania'"></Tzprint>
        <myPrintPdf v-if="user.country_name === 'Kenya'"></myPrintPdf>
    </div>
</template>

<script>
import myPrintPdf from './PrintPdf.vue';
import Tzprint from './Tzprint';
export default {
    components: {
        Tzprint, myPrintPdf 
    },
    props: ['user'],
data() {
    return {
        AllCountries: [],
    }
},
mounted() {
    axios.get('/getCountry')
            .then((response) => {
                this.AllCountries = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
}
</script>

<style>

</style>
