<template>
    <div v-show="showAlert" class="alert alert-info alert-dismissible fade show position-absolute w-50" role="alert" style="right:10px;bottom:20px;">
        <h4 class="alert-heading">This happened...</h4>
        <ul class="list-unstyled">
            <li v-for="message in messages">{{ message.message }}</li>
        </ul>
        <button type="button" class="close" data-hide="alert" aria-label="Close" @click="clearMessages()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        created: function() {
            this.listenToEcho();
        },

        data: function() {
            return {
                showAlert: false,
                messages: [],
            }
        },

        methods: {
            clearMessages: function() {
                this.showAlert = false;
                this.messages = [];
            },
            listenToEcho: function() {
                window.Echo.channel('echo_messages')
                    .listen('SubnetWasCreated', (e) => {
                        this.renderMessage(e);
                    });
            },
            renderMessage: function(message) {
                this.showAlert = true;
                this.messages.push(message);
            },
        }
    }
</script>
