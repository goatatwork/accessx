<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Severity</th>
                        <th>Reporting Class@Function</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="activity-log-table-row" v-for="log in activityLogs" :key="log.id" :log="log"></tr>
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
        }
    }
</script>
