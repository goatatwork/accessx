<template>
            <div class="collapse navbar-collapse justify-content-end" id="navbarPresence">
                <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarPresenceDropdownLink" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Who's Online?
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarPresenceDropdownLink">
                                <a v-for="user in onlineUsers" href="#" class="dropdown-item" v-text="user.name"></a>
                            </div>
                        </li>
                </ul>
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
