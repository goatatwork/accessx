<template>
    <div class="row">
        <div class="col">

            <div class="row mt-3 mb-3">
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

            <small>
                <div class="row mt-3 mb-3">
                    <div class="col">
                        Select which columns you want to see
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="customer_name_column_checkbox" v-model="showing.customer_name">
                            <label class="form-check-label" for="customer_name_column_checkbox">Customer Name</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="service_location_column_checkbox" v-model="showing.service_location">
                            <label class="form-check-label" for="service_location_column_checkbox">Service Location</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="package_column_checkbox" v-model="showing.package">
                            <label class="form-check-label" for="package_column_checkbox">Package</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ont_column_checkbox" v-model="showing.ont">
                            <label class="form-check-label" for="ont_column_checkbox">Ont</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ont_software_column_checkbox" v-model="showing.ont_software">
                            <label class="form-check-label" for="ont_software_column_checkbox">Software</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aggregator_column_checkbox" v-model="showing.aggregator">
                            <label class="form-check-label" for="aggregator_column_checkbox">Aggregator</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="slot_column_checkbox" v-model="showing.slot">
                            <label class="form-check-label" for="slot_column_checkbox">Slot</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="port_column_checkbox" v-model="showing.port">
                            <label class="form-check-label" for="port_column_checkbox">Port</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="management_ip_column_checkbox" v-model="showing.management_ip">
                            <label class="form-check-label" for="management_ip_column_checkbox">Management IP</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="len_column_checkbox" v-model="showing.len">
                            <label class="form-check-label" for="len_column_checkbox">LEN</label>
                        </div>
                    </div>
                </div>
            </small>



            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th v-show="showing.customer_name" @click="sortBy('customer')">Customer <span class="fas fa-sort"></span></th>
                        <th v-show="showing.service_location" @click="sortBy('address')">Service Location <span class="fas fa-sort" @click="sortBy('address')"></span></th>
                        <th v-show="showing.ont" @click="sortBy('ont')">ONT <span class="fas fa-sort" @click="sortBy('ont')"></span></th>
                        <th v-show="showing.ont_software" @click="sortBy('ont_software')">ONT Software <span class="fas fa-sort" @click="sortBy('ont_software')"></span></th>
                        <th v-show="showing.package" @click="sortBy('package')">Package <span class="fas fa-sort" @click="sortBy('package')"></span></th>
                        <th v-show="showing.aggregator" @click="sortBy('aggregator')">Aggregator <span class="fas fa-sort" @click="sortBy('aggregator')"></span></th>
                        <th v-show="showing.slot" @click="sortBy('slot')">Slot <span class="fas fa-sort" @click="sortBy('slot')"></span></th>
                        <th v-show="showing.port" @click="sortBy('port')">Port <span class="fas fa-sort" @click="sortBy('port')"></span></th>
                        <th v-show="showing.management_ip" @click="sortBy('management_ip')">Management IP <span class="fas fa-sort" @click="sortBy('management_ip')"></span></th>
                        <th v-show="showing.len" @click="sortBy('len')">LEN <span class="fas fa-sort" @click="sortBy('len')"></span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="provisioning-record-table-row" v-for="record in provisioningRecordsSorted" :record="record" :key="record.id" :showing="showing"></tr>
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
                sortOrder: 'asc',
                showing: {
                    customer_name: true,
                    service_location: false,
                    package: true,
                    ont: true,
                    ont_software: false,
                    aggregator: false,
                    slot: false,
                    port: false,
                    management_ip: true,
                    len: false
                }
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
