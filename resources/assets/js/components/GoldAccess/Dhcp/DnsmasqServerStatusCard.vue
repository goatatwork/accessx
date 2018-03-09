<template>
    <div class="card" :class="cardClasses">
        <a href="/activity_logs" class="text-dark">
            <div class="card-header text-center">
                <span class="fas fa-4x fa-folder mb-3"></span><br>
                <span class="h5">DNSMASQ</span>
            </div>
        </a>
        <div class="card-body text-center">
            <span class="h2">{{ statusText }}</span><br>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                status: {
                    isUp: true,
                },
            }
        },

        computed: {
            cardClasses: function() {
                return (this.status.isUp) ? 'border-success' : 'border-danger';
            },
            statusText: function() {
                return (this.status.isUp) ? 'UP' : 'DOWN';
            }
        },

        created: function() {
            this.getStatus();
        },

        methods: {
            getStatus: function() {
                axios.get('/api/docker/services/dhcp/statuscard').then(response => {
                    this.status = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
