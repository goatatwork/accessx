<template>
    <div v-show="messages.network_address" class="alert alert-info alert-dismissible fade show position-absolute w-25" role="alert" style="right:10px;bottom:20px;">
        New Subnet {{ messages.network_address }} created.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
                messages: {},
            }
        },

        methods: {
            doIt: function(subnet) {
                this.messages = subnet;
                // setTimeout(function() {
                //     $('.alert').alert('close');
                // }, 5000);
            },
            listenToEcho: function() {
                window.Echo.private('subnets')
                    // .listen('SubnetWasCreated', (e) => {
                    //     this.messages = e.subnet;
                    // });
                    .listen('SubnetWasCreated', ({subnet}) => {
                        this.doIt(subnet);
                    });
            }
        }
    }
</script>
