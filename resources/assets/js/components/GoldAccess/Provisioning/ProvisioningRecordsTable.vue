<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th @click="sortBy('customer')">Customer <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('address')">Service Location <span class="fas fa-sort" @click="sortBy('address')"></span></th>
                        <th @click="sortBy('package')">Package <span class="fas fa-sort" @click="sortBy('package')"></span></th>
                        <th @click="sortBy('ont')">ONT <span class="fas fa-sort" @click="sortBy('ont')"></span></th>
                        <th @click="sortBy('management_ip')">Management IP <span class="fas fa-sort" @click="sortBy('management_ip')"></span></th>
                        <th @click="sortBy('port')">NetLocation <span class="fas fa-sort" @click="sortBy('port')"></span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="provisioning-record-table-row" v-for="record in provisioningRecordsSorted" :record="record" :key="record.id"></tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    var ProvisioningRecordTableRow = Vue.extend(require('./ProvisioningRecordTableRow.vue'));

    export default {
        props: {
            provisioningRecords: {}
        },

        data: function() {
            return {
                sortKey: 'id',
                sortOrder: 'asc'
            }
        },

        components: {
            'provisioning-record-table-row': ProvisioningRecordTableRow,
        },

        computed: {
            provisioningRecordsSorted: function() {
                return _.orderBy(this.provisioningRecords, this.sortKey, this.sortOrder);
            }
        },

        methods: {
            sortBy: function(field) {
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
