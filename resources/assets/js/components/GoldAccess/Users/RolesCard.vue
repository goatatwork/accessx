<template>
    <div class="card">
        <div class="card-header text-center">
            Roles<br>
            <small><span class="font-italic">Click on a role to see it's permissions</span></small>
        </div>

            <ul class="list-group list-group-flush">
                <role v-for="role in roles" :role="role" :key="role.id"></role>
            </ul>

    </div>
</template>

<script>
    var Role = Vue.extend(require('./Role.vue'));

    export default {
        props: {
            roles: {},
        },

        components: {
            'role': Role,
        },
    }
</script>
