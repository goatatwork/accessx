<template>
    <a href="#" class="list-group-item list-group-item-action" :class="additionalClasses" @click.prevent="selectRole">
        <span :class="icon"></span>{{ role.name }}
    </a>
</template>

<script>
    export default {
        props: {
            role: {},
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        computed: {
            icon: function() {
                return (this.role.name == 'admin') ? 'fas fa-user-shield text-primary mr-2' : ( (this.role.name == 'technician') ? 'fas fa-user-ninja text-success mr-2' : 'fas fa-user-astronaut text-warning mr-2');
            }
        },

        data: function() {
            return {
                additionalClasses: '',
            }
        },

        methods: {
            initializeEventBus: function() {
                EventBus.$on('role-was-selected', function(role) {
                    this.additionalClasses = (role.id == this.role.id) ? 'bg-light font-weight-bold' : '';
                }.bind(this));
            },
            selectRole: function() {
                EventBus.$emit('role-was-selected', this.role);
            }
        }
    }
</script>
