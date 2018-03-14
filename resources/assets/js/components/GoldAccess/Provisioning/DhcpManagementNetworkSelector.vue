<template>
    <div class="row">
        <div class="col-md-12">

            <div class="form-group">
                <label for="dhcp_shared_network_id">Management Network</label>
                <select class="form-control" name="dhcp_shared_network_id" @change="managementNetworkWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="network in management_networks" :value="network.id">{{ network.name }}</option>
                </select>
            </div>

            <div v-show="ip_addresses.length" class="form-group">
                <label for="ip_address_id">IP Address</label>
                <select class="form-control" name="ip_address_id" @change="ipAddressWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="ip in ip_addresses" :value="ip.id" :disabled="ip.has_provisioning_records">{{ ip.address }}</option>
                </select>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                management_networks: {},
                ip_addresses: {},
                // ont_profiles: {},
            }
        },

        created: function() {
            this.fetchManagementNetworks();
        },

        methods: {
            fetchManagementNetworks: function() {
                axios.get('/api/dhcp/dhcp_shared_networks').then(response => {
                    this.management_networks = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchIpAddresses: function(dhcpSharedNetworkId) {
                axios.get('/api/dhcp/dhcp_shared_networks/'+dhcpSharedNetworkId+'/ip_addresses').then(response => {
                    this.ip_addresses = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            managementNetworkWasSelected: function(dhcpSharedNetworkId) {
                this.ip_addresses = {};
                if (dhcpSharedNetworkId == 0) {
                    return;
                }
                this.fetchIpAddresses(dhcpSharedNetworkId);
            },
            ipAddressWasSelected: function(ipAddressId) {
                console.log('IP '+ipAddressId+' was selected.');
                EventBus.$emit('provisioning-ip-address-was-selected', ipAddressId);
                // this is the id value we need so do something usefull with it
            },
        }
    }
</script>
