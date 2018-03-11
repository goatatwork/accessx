<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th @click="sortBy('created_at')" nowrap>Created <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('level')" nowrap>Severity <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('calling_class')" nowrap>Reporting Class@Function <span class="fas fa-sort"></span></th>
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
                sortKey: 'created_at',
                sortOrder: 'desc'
            }
        },

        computed: {
            activityLogsSorted: function() {
                return _.orderBy(this.activityLogs, this.sortKey, this.sortOrder);
            }
        },

        methods: {
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
