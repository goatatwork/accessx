<template>
    <a href="#" class="list-group-item list-group-item-action" :class="additionalClasses">
        <span class="far fa-bookmark mr-2 text-dark"></span>{{ permission.name }}
    </a>
</template>

<script>
    export default {
        props: {
            permission: {},
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        data: function() {
            return {
                additionalClasses: '',
            }
        },

        methods: {
            highlightPermissionsForRole: function(role) {
                this.additionalClasses = (_.find(role.permissions, { 'name': this.permission.name})) ? 'active' : '';
            },
            initializeEventBus: function() {
                EventBus.$on('role-was-selected', function(role) {
                    this.highlightPermissionsForRole(role);
                }.bind(this));
            },
        }
    }
</script>
