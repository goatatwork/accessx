<template>
    <a href="#" class="list-group-item list-group-item-action">
        <span class="fas fa-user mr-2 text-dark"></span>{{ user.name }}
        <ul class="list-inline pull-right" style="display:inline">
            <button v-show="userIsAdmin" class="btn btn-sm btn-outline-primary"><span class="fas fa-fw fa-user-shield"></span></button>
            <button v-show="userIsTechnician" class="btn btn-sm btn-outline-success"><span class="fas fa-fw fa-user-ninja"></span></button>
            <button v-show="userIsGuest" class="btn btn-sm btn-outline-secondary"><span class="fas fa-fw fa-user-astronaut"></span></button>
        </ul>
    </a>
</template>

<script>
    export default {
        props: {
            user: {},
        },

        computed: {
            userIsAdmin: function() {
                return _.find(this.user.roles, { 'name': 'admin' });
            },
            userIsGuest: function() {
                return _.find(this.user.roles, { 'name': 'guest' });
            },
            userIsTechnician: function() {
                return _.find(this.user.roles, { 'name': 'technician' });
            }
        }
    }
</script>
