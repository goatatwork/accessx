<template>
    <tr>
        <td v-show="columns.name" scope="col">{{ customerName }}</td>
        <td v-show="columns.service_address">{{ serviceAddress }}</td>
        <td v-show="columns.billing_address" scope="col">{{ billingAddress }}</td>
        <td v-show="columns.package" scope="col">{{ ontPackage }}</td>
        <td v-show="columns.ont" scope="col">{{ ontName }}</td>
        <td v-show="columns.software" scope="col">{{ ontSoftware }}</td>
        <td v-show="columns.management_ip" scope="col">{{ managementIp }}</td>
        <td v-show="columns.aggregator" scope="col">{{ aggregatorName }}</td>
        <td v-show="columns.slot" scope="col">{{ slotNumber }}</td>
        <td v-show="columns.port" scope="col">{{ portNumber }}</td>
        <td v-show="columns.len" scope="col">{{ record.len }}</td>
        <td v-show="columns.circuit_id" scope="col">{{ record.circuit_id }}</td>
    </tr>
</template>

<script>
    export default {
        props: {
            record: {},
            columns: {}
        },

        computed: {
            aggregatorName() {
                return this.record.port.slot.aggregator.name;
            },
            billingAddress() {
                return this.record.service_location.customer.billing_record.address1;
            },
            customerName() {
                return this.record.service_location.customer.customer_name;
            },
            managementIp() {
                return this.record.ip_address.address;
            },
            ontName() {
                return this.record.ont_profile.ont_software.ont.model_number;
            },
            ontPackage() {
                return this.record.ont_profile.name;
            },
            ontSoftware() {
                return this.record.ont_profile.ont_software.version;
            },
            portNumber() {
                return this.record.port.port_number;
            },
            serviceAddress() {
                return this.record.service_location.address1;
            },
            slotNumber() {
                return this.record.port.slot.slot_number;
            },
        },
    }
</script>
