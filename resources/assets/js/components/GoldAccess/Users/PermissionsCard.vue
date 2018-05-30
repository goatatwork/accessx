<template>
    <div v-show="showThisCard" class="card animated fadeIn">
        <div class="card-header text-center">Permissions<span class="font-weight-bold">{{ additionalText }}</span></div>

            <ul class="list-group list-group-flush">
                <permission v-for="permission in permissions" :permission="permission" :key="permission.id"></permission>
            </ul>

    </div>
</template>

<script>
    var Permission = Vue.extend(require('./Permission.vue'));

    export default {
        props: {
            permissions: {},
        },

        components: {
            'permission': Permission,
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        data: function() {
            return {
                additionalText: '',
                showThisCard: false,
            }
        },

        methods: {
            setAdditionalText: function(role) {
                this.additionalText = ' for '+role.name;
            },
            initializeEventBus: function() {
                EventBus.$on('role-was-selected', function(role) {
                    this.setAdditionalText(role);
                    this.showThisCard = true;
                }.bind(this));
            },
        }
    }
</script>
