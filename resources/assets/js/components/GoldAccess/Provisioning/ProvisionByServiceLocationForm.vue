<template>
    <div class="row">
        <div class="col-md-12">

            <ont-selector></ont-selector>

            <aggregator-selector></aggregator-selector>

            <dhcp-management-network-selector></dhcp-management-network-selector>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="len">LEN</label>
                        <input type="text" name="len" class="form-control" v-model="form.len">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="circuit_id">Circuit ID</label>
                        <input type="text" name="circuit_id" class="form-control" v-model="form.circuit_id">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="body" class="form-control" v-model="form.notes"></textarea>
                    </div>
                    <div class="form-group pull-right">
                        <a href="#" class="cancel-button" @click="cancelForm()">Cancel</a>
                        <button class="btn btn-sm btn-success" @click="submitForm()">Okay</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var OntSelector = Vue.extend(require('./OntSelector.vue'));
    var AggregatorSelector = Vue.extend(require('./AggregatorSelector.vue'));
    var DhcpManagementNetworkSelector = Vue.extend(require('./DhcpManagementNetworkSelector.vue'));

    export default {
        props: {
            location: {},
        },

        components: {
            'ont-selector': OntSelector,
            'aggregator-selector': AggregatorSelector,
            'dhcp-management-network-selector': DhcpManagementNetworkSelector,
        },

        data: function() {
            return {
                form: {
                    service_location_id: this.location.id,
                    ont_profile_id: '',
                    port_id: '',
                    ip_address_id: '',
                    len: '',
                    circuit_id: '',
                    notes: '',
                }
            }
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            cancelForm: function() {
                this.resetForm();
                window.location.href = "/customers/"+this.location.customer_id;
            },
            initializeEventBus: function() {
                EventBus.$on('provisioning-profile-was-selected', function(profileId) {
                    this.form.ont_profile_id = profileId;
                }.bind(this));
                EventBus.$on('provisioning-port-was-selected', function(portId) {
                    this.form.port_id = portId;
                }.bind(this));
                EventBus.$on('provisioning-ip-address-was-selected', function(ipAddressId) {
                    this.form.ip_address_id = ipAddressId;
                }.bind(this));
            },
            resetForm: function() {
                this.form = {
                    service_location_id: this.location.id,
                    ont_profile_id: '',
                    port_id: '',
                    ip_address_id: '',
                    len: '',
                    circuit_id: '',
                    notes: '',
                }
            },
            submitForm: function() {
                axios.post('/api/provisioning', this.form).then( (response) => {
                    this.resetForm();
                    window.location.href = "/provisioning/service_locations/"+this.location.id+"/show";
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            }
        }
    }
</script>
