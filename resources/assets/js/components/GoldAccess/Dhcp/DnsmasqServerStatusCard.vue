<template>
    <div class="card">
        <a href="/activity_logs" class="text-dark">
            <div class="card-header text-center">
                <span class="fas fa-4x fa-leaf mb-3"></span><br>
                <span class="h5">DNSMASQ</span>
            </div>
        </a>
        <div class="card-body text-center" :class="cardBodyClasses">
            <div class="row">
                <div class="col">
                    <span class="h2">{{ statusText }}</span><br>
                    <ul class="list-unstyled">
                        <li>
                            <small v-show="status.uptime">{{ uptimeText }}</small>
                            <small v-show="!status.uptime">&nbsp;</small>
                        </li>
                        <li>
                            <small>
                                <span class="text-muted" :class="fetchingUptimeClass">Fetching uptime</span>
                            </small>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    <small v-show="!restarting">
                        <button @click="restartContainer" class="btn btn-sm float-right" :class="restartButtonClasses">
                            {{ restartButtonText }}
                        </button>
                    </small>
                    <small v-show="restarting">
                        <button class="btn btn-sm float-right" :class="restartButtonClasses">
                            <span class="fas fa-spinner fa-pulse"></span> {{ restartButtonText }}
                        </button>
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                status: {
                    isUp: true,
                    uptime: '',
                },
                fetchingUptime: false,
                restarting: false,
                restartButtonText: 'Restart Server',
                restartButtonClasses: 'btn-dark text-light'
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
            },
            uptimeText: function() {
                return (this.status.uptime) ? this.status.uptime : 'DOWN';
            },
            fetchingUptimeClass() {
                return (this.fetchingUptime) ? 'show' : 'fade';
            }
        },

        created: function() {
            this.getStatus();
        },

        methods: {
            getStatus: function() {
                this.fetchingUptime = true;
                axios.get('/api/docker/services/dhcp/statuscard').then(response => {
                    this.status = response.data;
                    this.fetchingUptime = false;
                }).catch(error => {
                    console.log(error);
                });
            },
            onRestartFailure: function() {
                let self = this;
                this.restartButtonClasses = 'btn-danger text-dark';
                this.restartButtonText = 'FAILURE';
                setTimeout( function() {
                    self.restarting = false;
                    self.restartButtonClasses = 'btn-dark text-light';
                    self.restartButtonText = 'Restart Server';
                    self.getStatus();
                }, 2000);
            },
            onRestartSuccess: function() {
                let self = this;
                this.restartButtonClasses = 'btn-success text-dark';
                this.restartButtonText = 'SUCCESS';
                setTimeout( function() {
                    self.restarting = false;
                    self.restartButtonClasses = 'btn-dark text-light';
                    self.restartButtonText = 'Restart Server';
                    self.getStatus();
                }, 2000);
            },
            restartContainer: function() {
                this.restarting = true;
                axios.post('/api/docker/services/dhcp/restart').then( (response) => {
                    (response.data.restarted) ? this.onRestartSuccess() : this.onRestartFailure();
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            }
        }
    }
</script>
