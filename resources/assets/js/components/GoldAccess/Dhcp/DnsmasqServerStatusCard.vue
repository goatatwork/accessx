<template>
    <div class="card">
        <a href="/activity_logs" class="text-dark">
            <div class="card-header text-center">
                <span class="fas fa-4x fa-leaf mb-3"></span><br>
                <span class="h5">DNSMASQ</span>
            </div>
        </a>
        <div class="card-body text-center" :class="cardBodyClasses">
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
            cardBodyClasses: function() {
                return (this.status.isUp) ? 'border-success text-success' : 'border-danger text-danger';
            },
            cardHeaderClasses: function() {
                return (this.status.isUp) ? 'bg-success border-success text-dark' : 'bg-danger border-danger text-dark';
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
