<template>
    <div class="card">
        <div class="card-header">Roles</div>

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
        }
    }
</script>
