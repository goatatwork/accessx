<template>
    <div class="list-group-item list-group-item-action" :class="additionalClasses">
        <span  v-show="userIsAdmin" class="fas fa-fw fa-user-shield mr-2 text-primary"></span>
        <span v-show="userIsTechnician" class="fas fa-fw fa-user-ninja mr-2 text-success"></span>
        <span v-show="userIsGuest" class="fas fa-fw fa-user-astronaut mr-2 text-warning"></span>
        {{ user.name }}
        <ul class="list-inline pull-right" style="display:inline">
            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" :data-target="modalHref" @click="selectUser"><small>EDIT</small></button>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            user: {},
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        computed: {
            modalHref: function() {
                return '#user-modal-'+this.user.id;
            },
            userIsAdmin: function() {
                return (_.find(this.user.roles, { 'name': 'admin' })) ? true : false;
            },
            userIsGuest: function() {
                return (_.find(this.user.roles, { 'name': 'guest' })) ? true : false;
            },
            userIsTechnician: function() {
                return (_.find(this.user.roles, { 'name': 'technician' })) ? true : false;
            }
        },

        data: function() {
            return {
                additionalClasses: '',
            }
        },

        methods: {
            initializeEventBus: function() {
                EventBus.$on('user-was-selected', function(user) {
                    // this.additionalClasses = (user.id == this.user.id) ? 'bg-light font-weight-bold' : '';
                }.bind(this));
            },
            selectUser: function() {
                EventBus.$emit('user-was-selected', this.user);
            }
        }
    }
</script>
