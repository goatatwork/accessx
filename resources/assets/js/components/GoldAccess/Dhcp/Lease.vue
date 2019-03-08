<template>
    <tr class="dhcp-lease" :class="leaseClasses">
        <td>
            <span v-show="! expired" class="badge badge-success">Active</span>
            <span v-show="expired" class="badge badge-danger">Expired</span>
        </td>
        <td>
            <small>
                {{ expires }} <span v-show="expired">ago</span>
            </small>
        </td>
        <td>
            {{ lease.mac }}
        </td>
        <td>
            {{ lease.ip }}
        </td>
        <td>
            {{ lease.hostname }}
        </td>
        <td>
            {{ lease.client_id }}
        </td>
    </tr>
</template>

<script>
    var moment = require('moment');

    export default {
        props: {
            lease: {},
            timer: {
                type: Boolean,
                default() {
                    return true;
                }
            }
        },

        data() {
            return {
                expires: '',
                expired: false,
                run_clock: this.timer,
                leaseClasses: {
                    'font-weight-bold': true,
                    'font-italic': false,
                    'table-light': true,
                }
            }
        },

        computed: {
            expireTimeFormatted() {
                return moment.unix(this.lease.time).format('llll');
            },
        },

        mounted() {
            this.clock();
        },

        methods: {
            clock() {
                if (this.timer) {
                    var now = moment();
                    var lease_expires = moment.unix(this.lease.time);
                    var duration = moment.duration(lease_expires.diff(now));

                    this.expires = duration.hours()+' hours '+duration.minutes()+' minutes '+duration.seconds()+' seconds';

                    if (moment().isAfter(moment.unix(this.lease.time))) {
                        this.expired = true;
                        this.run_clock = true;
                        this.leaseClasses = {
                            'font-weight-bold': false,
                            'font-italic': true,
                            'table-light': true,
                        }
                    }

                }

                setTimeout(this.clock, 1000);
            }
        }
    }
</script>

<style>
.dhcp-lease {
    animation: all 2s;
}
</style>
