<template>
    <div>
        <div class="card">
            <div class="card-header text-center">Users</div>

                <ul class="list-group list-group-flush">
                    <user v-for="user in usersList" :user="user" :key="user.id"></user>
                </ul>

        </div>

        <user-modal v-for="user in usersList" :user="user" :key="user.id"></user-modal>
        <user-delete-modal v-for="user in usersList" :user="user" :key="user.id+'-delete-modal'"></user-delete-modal>

    </div>
</template>

<script>
    var User = Vue.extend(require('./User.vue'));
    var UserModal = Vue.extend(require('./UserModal.vue'));
    var UserDeleteModal = Vue.extend(require('./UserDeleteModal.vue'));

    export default {
        props: {
            users: {},
        },

        components: {
            'user': User,
            'user-modal': UserModal,
            'user-delete-modal': UserDeleteModal
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

                EventBus.$on('user-was-deleted', function(user) {
                    this.removeUserFromList(user);
                }.bind(this));
            },
            removeUserFromList: function(user) {
                window.location.href = window.location.href;
            },
            updateUsersList: function(user) {
                window.location.href = window.location.href;
            }
        }
    }
</script>
