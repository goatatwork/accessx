<template>
    <div class="row">
        <div class="col">

            <div class="row mb-3">
                <div class="col text-right">
                    <toggle-switch default-state="on" @toggled="toggleClocks"></toggle-switch>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">
                                    Expires In <button class="fas fa-sort" @click="sortBy('time')"></button>
                                </th>
                                <th class="text-center">
                                    MAC <button class="fas fa-sort" @click="sortBy('mac')"></button>
                                </th>
                                <th class="text-center">
                                    IP <button class="fas fa-sort" @click="sortBy('ip')"></button>
                                </th>
                                <th class="text-center">
                                    Host Name <button class="fas fa-sort" @click="sortBy('hostname')"></button>
                                </th>
                                <th class="text-center">
                                    ClientID <button class="fas fa-sort" @click="sortBy('client_id')"></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr is="dhcp-lease" v-for="(lease, index) in sortedLeases" :lease="lease" :key="index" :timer="run_clocks">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var DhcpLease = Vue.extend(require('./Lease.vue'));

    export default {
        props: {
            leases: {},
        },

        components: {
            'dhcp-lease': DhcpLease
        },

        data() {
            return {
                allLeases: this.leases,
                run_clocks: true,
                sortByColumn: 'time',
                sortByDirection: 'asc'
            }
        },

        computed: {
            sortedLeases() {
                return _.orderBy(this.allLeases, [this.sortByColumn], [this.sortByDirection]);
            }
        },

        mounted() {
            this.listenForEcho();
        },

        methods: {
            listenForEcho: function() {
                this.listening = true;
                window.Echo.channel('dhcp_events')
                    .listen('DhcpEvent', (e) => {
                        this.renderEvent(e);
                    });
            },
            renderEvent(e) {
                if (e.dhcp_event) {
                    if (e.dhcp_event.event.ACTION == 'new') {

                        var newLease = {
                            time: e.dhcp_event.event.DNSMASQ_LEASE_EXPIRES,
                            mac: e.dhcp_event.event.HOSTMAC,
                            ip: e.dhcp_event.event.IP,
                            hostname: e.dhcp_event.event.HOSTNAME,
                            client_id: e.dhcp_event.event.DNSMASQ_CLIENT_ID,
                        }

                        this.allLeases.push(newLease);
                    }
                }
            },
            sortBy(column) {
                if (this.sortByColumn == column) {
                    this.sortByDirection = (this.sortByDirection == 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortByColumn = column;
                    this.sortByDirection = 'asc';
                }
            },
            toggleClocks(event) {
                if (event.new_state == 'on') {
                    this.run_clocks = true;
                }
                if (event.new_state == 'off') {
                    this.run_clocks = false;
                }
            }
        }
    }
</script>
