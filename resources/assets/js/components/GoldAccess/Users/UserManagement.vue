<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <users-card :users="users"></users-card>
                </div>
                <div class="col">
                    <roles-card :roles="roles"></roles-card>
                </div>
                <div class="col">
                    <permissions-card :permissions="permissions"></permissions-card>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var UsersCard = Vue.extend(require('./UsersCard.vue'));
    var RolesCard = Vue.extend(require('./RolesCard.vue'));
    var PermissionsCard = Vue.extend(require('./PermissionsCard.vue'));

    export default {
        props: {
            users: {},
            roles: {},
            permissions: {},
        },

        components: {
            'users-card': UsersCard,
            'roles-card': RolesCard,
            'permissions-card': PermissionsCard,
        }
    }
</script>
