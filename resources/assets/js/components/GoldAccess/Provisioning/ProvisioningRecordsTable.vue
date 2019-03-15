<template>
    <div class="row">
        <div class="col">

            <div class="row mb-3">
                <div class="col">
                    <label for="searchQuery" class="sr-only">Search Query</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="fas fa-at">
                                </span>
                            </span>
                        </div>
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Search">
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th @click="sortBy('customer')">Customer <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('address')">Service Location <span class="fas fa-sort" @click="sortBy('address')"></span></th>
                        <th @click="sortBy('package')">Package <span class="fas fa-sort" @click="sortBy('package')"></span></th>
                        <th @click="sortBy('ont')">ONT <span class="fas fa-sort" @click="sortBy('ont')"></span></th>
                        <th @click="sortBy('management_ip')">Management IP <span class="fas fa-sort" @click="sortBy('management_ip')"></span></th>
                        <th @click="sortBy('len')">LEN <span class="fas fa-sort" @click="sortBy('len')"></span></th>
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
                searchQuery: '',
                sortKey: 'id',
                sortOrder: 'asc'
            }
        },

        components: {
            'provisioning-record-table-row': ProvisioningRecordTableRow,
        },

        computed: {
            provisioningRecordsFiltered: function() {
                let self = this;
                return self.provisioningRecords.filter(function(prov_rec) {
                    var searchRegex = new RegExp(self.searchQuery, 'i');
                    return searchRegex.test(prov_rec.customer) || searchRegex.test(prov_rec.address)
                });
            },
            provisioningRecordsSorted: function() {
                return _.orderBy(this.provisioningRecordsFiltered, this.sortKey, this.sortOrder);
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
