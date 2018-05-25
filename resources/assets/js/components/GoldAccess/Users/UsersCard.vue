<template>
    <div class="card">
        <div class="card-header">Users</div>

            <ul class="list-group list-group-flush">
                <user v-for="user in users" :user="user" :key="user.id"></user>
            </ul>

    </div>
</template>

<script>
    var User = Vue.extend(require('./User.vue'));

    export default {
        props: {
            users: {},
        },

        components: {
            'user': User,
        }
    }
</script>
