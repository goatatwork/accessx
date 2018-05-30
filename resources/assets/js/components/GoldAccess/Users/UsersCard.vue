<template>
    <div>
        <div class="card">
            <div class="card-header text-center">Users</div>

                <ul class="list-group list-group-flush">
                    <user v-for="user in usersList" :user="user" :key="user.id"></user>
                </ul>

        </div>

        <user-modal v-for="user in usersList" :user="user" :key="user.id"></user-modal>

    </div>
</template>

<script>
    var User = Vue.extend(require('./User.vue'));
    var UserModal = Vue.extend(require('./UserModal.vue'));

    export default {
        props: {
            users: {},
        },

        components: {
            'user': User,
            'user-modal': UserModal
        },

        data: function() {
            return {
                usersList: Object.assign({}, this.users),
            }
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            initializeEventBus: function() {
                EventBus.$on('user-was-updated', function(user) {
                    this.updateUsersList(user);
                }.bind(this));
            },
            updateUsersList: function(user) {
                window.location.href = window.location.href;
            }
        }
    }
</script>
