<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th colspan="5">

                            <button v-if="listening" class="btn btn-sm btn-success text-dark float-right" @click.prevent="disableLiveUpdates()">
                                <span class="fas fa-power-off"></span>
                                Live Updates Are On
                            </button>

                            <button v-if="!listening" class="btn btn-sm btn-secondary text-dark float-right" @click.prevent="enableLiveUpdates()">
                                <span class="fas fa-power-off"></span>
                                <span class="font-italic">Live Updates Are Off</span>
                            </button>

                        </th>
                    </tr>
                    <tr>
                        <th @click="sortBy('created_at')" nowrap>Created <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('level')" nowrap>Severity <span class="fas fa-sort"></span></th>
                        <th nowrap>Message</th>
                        <th nowrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="activity-log-table-row" v-for="log in activityLogsSorted" :key="log.id" :log="log"></tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    var ActivityLogTableRow = Vue.extend(require('./ActivityLogTableRow.vue'));

    export default {
        props: {
            activityLogs: {},
        },

        components: {
            'activity-log-table-row': ActivityLogTableRow,
        },

        data: function() {
            return {
                theLogs: this.activityLogs,
                sortKey: 'created_at',
                sortOrder: 'desc',
                listening: false,
            }
        },

        computed: {
            activityLogsSorted: function() {
                return _.orderBy(this.theLogs, this.sortKey, this.sortOrder);
            }
        },

        created: function() {
            this.listenForEcho();
        },

        methods: {
            disableLiveUpdates: function() {
                this.listening = false;
                window.Echo.leave('activity_logs');
            },
            enableLiveUpdates: function() {
                this.listening = true;
                this.listenForEcho();
            },
            listenForEcho: function() {
                this.listening = true;
                window.Echo.private('activity_logs')
                    .listen('ActivityWasLogged', ({activity_log}) => {
                        this.theLogs.push(activity_log);
                    });
            },
            sortBy: function(field) {
                console.log(field);
                if (field == this.sortKey) {
                    this.sortOrder = (this.sortOrder == 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortKey = field;
                    this.sortOrder = 'asc';
                }
            }
        }
    }
</script>
