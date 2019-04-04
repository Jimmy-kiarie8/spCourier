<template>
<div>
    <div v-for="role in user.roles" :key="role.id">
        <div>
            <Dashboard :user="user"></Dashboard>
        </div>
    </div>
</div>
</template>

<script>
import Dashboard from "./Dashboard.vue";
export default {
    props: ["user"],
    components: {
        Dashboard,
    }
};
</script>

<style>
</style>
