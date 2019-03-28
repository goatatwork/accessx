<template>
    <div class="row mb-3">
        <div class="col">

            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th colspan="12">
                            <span class="far fa-clipboard"></span>
                            Use the buttons to select which columns to see in the table below
                        </th>
                    </tr>
                    <tr>
                        <th class="p-0 border-0"><column-selector name="Name" :is-active="columns.name" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Service Address" :is-active="columns.service_address" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Billing Address" :is-active="columns.billing_address" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Package" :is-active="columns.package" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="ONT" :is-active="columns.ont" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Software" :is-active="columns.software" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Management IP" :is-active="columns.management_ip" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Aggregator" :is-active="columns.aggregator" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Slot" :is-active="columns.slot" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="Port" :is-active="columns.port" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="LEN" :is-active="columns.len" @toggled="toggleColumn"></column-selector></th>
                        <th class="p-0 border-0"><column-selector name="CircuitID" :is-active="columns.circuit_id" @toggled="toggleColumn"></column-selector></th>
                    </tr>
                </thead>
            </table>


            <table class="table table-sm table-borderless table-hover">
                <thead class="thead">
                    <tr>
                        <td colspan="12">
                            <label for="searchQuery" class="sr-only">Search Query</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-search">
                                        </span>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-sm" v-model="searchQuery" placeholder="Search">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th v-show="columns.name" scope="col" @click="sortBy('service_location.customer.customer_name')">
                            Name
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.service_address" scope="col" @click="sortBy('service_location.address1')">
                            Service Address
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.billing_address" scope="col" @click="sortBy('service_location.customer.billing_record.address1')">
                            Billing Address
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.package" scope="col" @click="sortBy('ont_profile.name')">
                            Package
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.ont" scope="col" @click="sortBy('ont_profile.ont_software.ont.model_number')">
                            ONT
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.software" scope="col" @click="sortBy('ont_profile.ont_software.version')">
                            Software
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.management_ip" scope="col" @click="sortBy('ip_address.address')">
                            Management IP
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.aggregator" scope="col" @click="sortBy('port.slot.aggregator.name')">
                            Aggregator
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.slot" scope="col" @click="sortBy('port.slot.slot_number')">
                            Slot
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.port" scope="col" @click="sortBy('port.port_number')">
                            Port
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.len" scope="col" @click="sortBy('len')">
                            LEN
                            <span class="fas fa-sort"></span>
                        </th>
                        <th v-show="columns.circuit_id" scope="col" @click="sortBy('circuit_id')">
                            CircuitID
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="pr" v-for="record in sortedRecords" :key="record.id" :record="record" :columns="columns"></tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    var Pr = Vue.extend(require('./ProvisioningRecord.vue'));

    var ColumnSelectorButton = Vue.extend(require('./ColumnSelectorButton.vue'));

    export default {
        props: {
            provisioningRecords: {},
        },

        components: {
            'pr': Pr,
            'column-selector': ColumnSelectorButton,
        },

        data() {
            return {
                columns: {
                    name: true,
                    service_address: false,
                    billing_address: false,
                    package: true,
                    ont: true,
                    software: true,
                    management_ip: true,
                    aggregator: true,
                    slot: true,
                    port: true,
                    len: true,
                    circuit_id: true,
                },
                sortKey: 'id',
                searchQuery: '',
                sortOrder: 'asc',
            }
        },

        computed: {
            filteredRecords: function() {
                let self = this;
                return self.provisioningRecords.filter(function(prov_rec) {
                    var searchRegex = new RegExp(self.searchQuery, 'i');

                    if (self.columns.name) {
                        if (searchRegex.test(prov_rec.service_location.customer.customer_name)) {
                            return searchRegex.test(prov_rec.service_location.customer.customer_name);
                        }
                    }

                    if (self.columns.service_location) {
                        if ( searchRegex.test(prov_rec.service_location.address1)) {
                            return  searchRegex.test(prov_rec.service_location.address1);
                        }
                    }
                });
            },
            sortedRecords() {
                return _.orderBy(this.filteredRecords, this.sortKey, this.sortOrder);
            }
        },

        mounted() {
            this.initializeColumns();
        },

        methods: {
            initializeColumns() {
                var records = _.filter(this.provisioningRecords, 'len');
                this.columns.len = (records.length) ? true : false;

                var records = _.filter(this.provisioningRecords, 'circuit_id');
                this.columns.circuit_id = (records.length) ? true : false;
            },
            sortBy: function(field) {
                if (field == this.sortKey) {
                    this.sortOrder = (this.sortOrder == 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortKey = field;
                    this.sortOrder = 'asc';
                }
                console.log('sortBy'+moment().format());
            },
            toggleColumn(column) {
                this.columns[column] = ! this.columns[column];
            },
        }
    }
</script>
