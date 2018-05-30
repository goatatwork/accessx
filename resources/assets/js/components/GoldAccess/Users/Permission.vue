<template>
    <a href="#" class="list-group-item list-group-item-action" :class="additionalClasses">
        <span  :class="iconStyle" @click.prevent="togglePermission($event.target.attributes.permissionname.value)" :permissionname="permission.name"></span>{{ permission.name }}
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
                currentlySelectedRole: {},
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
                    this.currentlySelectedRole = role;
                }.bind(this));
            },
            togglePermission: function(x) {
                this.iconStyle = (this.iconStyle == 'far fa-bookmark mr-2 text-dark') ? 'fas fa-bookmark mr-2 text-success' : 'far fa-bookmark mr-2 text-dark';

                axios.patch('/api/authorization/roles/'+this.currentlySelectedRole.id+'/permissions/'+x+'/toggle').then(response => {
                    console.log(response.data);
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
