<template>
    <div class="card">
        <a href="/activity_logs" class="text-dark">
            <div class="card-header text-center">
                <span class="fas fa-4x fa-folder mb-3"></span><br>
                <span class="h5">ACTIVITY LOGS</span>
            </div>
        </a>
        <div class="card-body text-center">
            <span class="h2">{{ recordCount }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            count: {
                type: [String, Number],
            }
        },

        data: function() {
            return {
                recordCount: this.count,
            }
        },

        created: function() {
            this.listenForEcho();
        },

        methods: {
            listenForEcho: function() {
                this.listening = true;
                window.Echo.private('activity_logs')
                    .listen('ActivityWasLogged', ({activity_log}) => {
                        this.recordCount++;
                    });
            },
        }
    }
</script>
