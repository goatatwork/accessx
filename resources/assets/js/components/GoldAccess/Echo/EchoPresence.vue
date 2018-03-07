<template>
    <div class="card position-absolute w-25" style="right:5px;top:60px;">
        <div class="card-body">
            <span class="h5 card-title">Who's Online?</span>
            <ul class="list-unstyled">
                <li class="list-group-item text-center" v-for="user in onlineUsers" v-text="user.name"></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        created: function() {
            this.listenToEcho();
        },

        computed: {
            channel: function() {
                return window.Echo.join('presence');
            }
        },

        data: function() {
            return {
                onlineUsers: [],
            }
        },

        methods: {
            listenToEcho: function() {
                this.channel
                    .here(users => {
                        this.onlineUsers = users;
                    })
                    .joining(user => {
                        this.onlineUsers.push(user);
                    })
                    .leaving(user => {
                        this.onlineUsers.splice(this.onlineUsers.indexOf(user), 1);
                    });
            }
        }
    }
</script>
