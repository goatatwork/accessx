<template>
    <a href="#" class="list-group-item list-group-item-action" :class="additionalClasses">
        <span  :class="iconStyle"></span>{{ permission.name }}
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
                iconStyle: 'far fa-bookmark mr-2 text-dark',
            }
        },

        methods: {
            highlightPermissionsForRole: function(role) {
                this.additionalClasses = (_.find(role.permissions, { 'name': this.permission.name})) ? 'bg-light font-weight-bold' : '';
                this.iconStyle = (_.find(role.permissions, { 'name': this.permission.name})) ? 'fas fa-bookmark mr-2 text-success' : 'far fa-bookmark mr-2 text-dark';
            },
            initializeEventBus: function() {
                EventBus.$on('role-was-selected', function(role) {
                    this.highlightPermissionsForRole(role);
                }.bind(this));
            },
        }
    }
</script>
