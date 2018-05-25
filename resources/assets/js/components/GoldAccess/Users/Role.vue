<template>
    <a href="#" class="list-group-item list-group-item-action" @click="selectRole">
        <span :class="icon"></span>{{ role.name }}
    </a>
</template>

<script>
    export default {
        props: {
            role: {},
        },

        computed: {
            icon: function() {
                return (this.role.name == 'admin') ? 'fas fa-user-shield text-primary mr-2' : ( (this.role.name == 'technician') ? 'fas fa-user-ninja text-success mr-2' : 'fas fa-user-astronaut text-warning mr-2');
            }
        },

        methods: {
            selectRole: function() {
                EventBus.$emit('role-was-selected', this.role);
            }
        }
    }
</script>
